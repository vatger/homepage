<?php

namespace App\Console\Commands\Navigation;

use App\Libraries\EuroScope\SectorDataLibrary;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use JsonException;

class GenerateFIRBoundaries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nav:boundaries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate FIR Boundaries';

    /**
     * Path of the FIR Boundaries definition file.
     *
     * @var string
     */
    private $defFilePath = 'navigation/sectors/FIRBoundaries.dat';

    /**
     * Euroscope ESE File to generate local sectors from.
     *
     * @var string
     */
    private $eseFilePath = 'euroscope/sectorfiles/build/combined.ese';

    /**
     * Euroscope Sector Definition file.
     *
     * @var string
     */
    private $sctFilePath = 'euroscope/sectorfiles/build/combined.sct';

    /**
     * Output path for the converted sectors.
     *
     * @var string
     */
    private $sectorOutputFile = 'navigation/sectors/fir_boundaries.json';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $output = [];
        if (Storage::exists($this->defFilePath)) {
            $defFile = Storage::get($this->defFilePath);
            $this->info('Definition file read');
            $sectors = [];
            $currentSector = [];
            foreach (explode("\n", $defFile) as $key => $line) {
                $line = str_replace("\r", '', $line);
                $lineSplit = explode('|', $line);
                if (10 == sizeof($lineSplit)) {
                    if ($key > 0) {
                        $sectors[] = $currentSector;
                    }
                    $currentSector = [
                        'icao' => $lineSplit[0],
                        'isOceanic' => $lineSplit[1],
                        'isExtension' => $lineSplit[2],
                        'points' => [],
                    ];
                } else {
                    if ('' != $line) {
                        $currentSector['points'][] = [$lineSplit[0], $lineSplit[1]];
                    }
                }
            }
            if (!empty($currentSector)) {
                $sectors[] = $currentSector;
            }

            $this->info('Total general sector count: ' . sizeof($sectors));
            $output['general'] = $sectors;
        } else {
            $this->error('Unable to locate definition file at: ' . $this->defFilePath);
        }
        // Gather ARTCC information from sct file
        if (Storage::exists($this->sctFilePath)) {
            $this->info('Started reading from .sct file');
            $sctFile = SectorDataLibrary::parseSectorFile(storage_path('app') . '/' . $this->sctFilePath);
            $output['vatger']['artcc'] = $sctFile['artcc'];
            $this->info('Finished reading .sct file.');
            $this->info('Found ' . sizeof($sctFile['artcc']) . ' ARTCC sections.');
        }
        //  Build local sectors from local .ese file
        if (Storage::exists($this->eseFilePath)) {
            $this->info('Started reading from .ese file');
            $eseFile = SectorDataLibrary::parseExtensionFile(storage_path('app') . '/' . $this->eseFilePath);
            $sectorlines = [];
            $sectors = [];
            foreach ($eseFile['airspace']['comb'] as $id => $sector) {
                if ($sector['type'] == 'SECTORLINE') {
                    $sectorlines[] = $sector;
                } elseif ($sector['type'] == 'SECTOR') {
                    // $sector['name'] = mb_convert_encoding($sector['name'], 'UTF-8', 'ISO-8859-1');
                    $sectors[] = $sector;
                }
            }
            $this->info('Finished reading .ese file.');
            $this->info('Found ' . sizeof($eseFile['positions']) . ' ATC positions.');
            $this->info('Found ' . sizeof($sectors) . ' ATC airspaces.');
            $output['vatger']['positions'] = $eseFile['positions'];
            $output['vatger']['airspace']['lines'] = $sectorlines;
            $output['vatger']['airspace']['sectors'] = $sectors;
        }
        try {
            $outputString = json_encode($output, JSON_PRETTY_PRINT | JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR);
            $outputString = str_replace('\ufffd', '_', $outputString);
            Storage::put($this->sectorOutputFile, $outputString);
        } catch (JsonException $e) {
            $this->info(json_last_error());
        }
    }
}
