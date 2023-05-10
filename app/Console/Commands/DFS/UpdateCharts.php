<?php

namespace App\Console\Commands\DFS;

use App\Libraries\DFS\ChartLibrary;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class UpdateCharts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dfs:charts {--cycle}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update our cached dfs charts storage.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->option('cycle')) {
            Cache::forget('org.vatsim-germany.navigation.aerodromes.charts.dfs');
            ChartLibrary::loadDFSCharts(true);
        } else {
            $clearCache = $this->confirm('Do you want to clear all current charts?', false);
            if ($clearCache) {
                Cache::forget('org.vatsim-germany.navigation.aerodromes.charts.dfs');
                $this->info('Cache \'org.vatsim-germany.navigation.aerodromes.charts.dfs\' cleared!');
            }
            $this->info('Updating charts from DFS AIP!');
            $this->newline(1);
            ChartLibrary::loadDFSCharts(false, $this->output);
            $this->newline(1);
            $this->info('Updated charts from DFS AIP!');
        }

        return 0;
    }
}
