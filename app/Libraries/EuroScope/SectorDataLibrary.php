<?php

namespace App\Libraries\EuroScope;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class SectorDataLibrary
{
    /**
     * Get all links to GNG provided sectorpackages
     *
     * @return array
     */
    private static function grabSectorfileLinks()
    {
        $ch = curl_init('http://files.aero-nav.com/EDXX');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $raw = curl_exec($ch);
        curl_close($ch);

        $links = [];
        preg_match_all('/https:\/\/files.aero-nav.com\/[A-Z]{4}\/.+\\.zip/', $raw, $links, PREG_PATTERN_ORDER);

        return Arr::flatten($links);
    }

    /**
     * Handle single file download throuh curl
     *
     * @return string|bool
     */
    private static function downloadFile($link)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, false);
        curl_setopt($ch, CURLOPT_REFERER, 'http://files.aero-nav.com/EDXX');
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36',
            'Referer: http://files.aero-nav.com/EDXX',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Accept-Encoding: gzip, deflate',
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * Download and store SCT and ESE files from GNG
     *
     * @return void
     */
    private static function downloadSectorfiles()
    {
        $links = self::grabSectorfileLinks();

        $existingFiles = Storage::allFiles('euroscope/sectorfiles');
        Storage::delete($existingFiles);

        foreach ($links as $link) {
            if (Str::contains($link, ['update', 'airac'], true)) {
                continue;
            }

            if (!Storage::exists('euroscope/sectorfiles/' . Str::afterLast($link, '/'))) {
                Storage::put('euroscope/sectorfiles/' . Str::afterLast($link, '/'), self::downloadFile($link));

                $zip = new ZipArchive();
                if ($zip->open(storage_path('app') . '/euroscope/sectorfiles/' . Str::afterLast($link, '/')) === true) {
                    $extract = [];
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $fn = $zip->getNameIndex($i);
                        if (Str::endsWith($fn, ['.sct', 'ese'])) {
                            $extract[] = $fn;
                        }
                    }
                    $zip->extractTo(storage_path('app') . '/euroscope/sectorfiles', $extract);
                }
                $zip->close();

                Storage::delete('euroscope/sectorfiles/' . Str::afterLast($link, '/'));
            }
        }
    }

    /**
     * Combines parsed SCT files into one
     *
     * @return string
     */
    private static function combineSCTFiles()
    {
        $existingFiles = Storage::files('euroscope/sectorfiles', false);
        $sectorfileData = [];
        foreach ($existingFiles as $sctFile) {
            if (Str::endsWith($sctFile, '.sct')) {
                $sectorfileData[] = self::parseSectorFile(storage_path('app') . '/' . $sctFile);
            }
        }

        $result = $sectorfileData[0];
        for ($i = 1; $i < sizeof($sectorfileData); $i++) {
            foreach ($sectorfileData[$i] as $section => $data) {
                foreach ($data as $key => $value) {
                    if (!array_key_exists($key, $result[$section])) {
                        $result[$section][$key] = $value;
                    } else {
                        if ($result[$section][$key] != $value) {
                            $result[$section][$key] = $value;
                        }
                    }
                }
            }
        }

        return self::generateCombinedSectorfile($result);
    }

    /**
     * Combines paresd ese files into one
     *
     * @return string
     */
    private static function combineESEFiles()
    {
        $existingFiles = Storage::files('euroscope/sectorfiles', false);
        $eseData = [];
        foreach ($existingFiles as $eseFile) {
            if (Str::endsWith($eseFile, '.ese')) {
                $res = self::parseExtensionFile(storage_path('app') . '/' . $eseFile);
                $eseData[] = $res;
            }
        }
        $result = $eseData[0];
        for ($i = 1; $i < sizeof($eseData); $i++) {
            foreach ($eseData[$i] as $section => $data) {
                foreach ($data as $key => $value) {
                    if (!array_key_exists($key, $result[$section])) {
                        $result[$section][$key] = $value;
                    } else {
                        if ($result[$section][$key] != $value) {
                            $result[$section][$key] = $value;
                        }
                    }
                }
            }
        }

        return self::generateCombinedExtensionfile($result);
    }

    /**
     * Combine SCT and ESE files
     *
     * @return void
     */
    public static function combineSectorFiles()
    {
        self::downloadSectorfiles();

        $sctFileContent = self::combineSCTFiles();

        Storage::put('euroscope/sectorfiles/build/combined.sct', $sctFileContent);

        $eseFileContent = self::combineESEFiles();

        Storage::put('euroscope/sectorfiles/build/combined.ese', $eseFileContent);

        $zipFile = new ZipArchive();
        $zipFile->open(storage_path('app') . '/euroscope/sectorfiles/build/combined.zip', ZIPARCHIVE::CREATE);
        $zipFile->addFile(storage_path('app') . '/euroscope/sectorfiles/build/combined.sct', 'combined.sct');
        $zipFile->addFile(storage_path('app') . '/euroscope/sectorfiles/build/combined.ese', 'combined.ese');
        $zipFile->close();
    }

    /**
     * Parse an sct file to a data holding array
     *
     * @param  [type]  $filePath [description]
     * @param  boolean $isCustom [description]
     * @return [type]            [description]
     */
    public static function parseSectorFile($filePath, $isCustom = false)
    {
        $result = [
            'info' => [],
            'colors' => [],
            'vor' => [],
            'ndb' => [],
            'fixes' => [],
            'airport' => [],
            'runway' => [],
            'sid' => [],
            'star' => [],
            'artcc' => [],
            'artcc low' => [],
            'artcc high' => [],
            'geo' => [],
            'regions' => [],
            'airway high' => [],
            'airway low' => [],
        ];

        $activeSection = null;
        $currentSID = [];
        $currentSTAR = [];
        $currentARTCC = [];
        $currentGEO = [];
        $currentRegion = null;
        $currentRegionIterator = 0;

        foreach (file($filePath) as $line) {
            $line = trim($line);
            if (strlen($line) == 0) {
                continue;
            }

            switch ($line) {
                case '[INFO]':
                    $activeSection = 'info';
                    break;
                case '[VOR]':
                    $activeSection = 'vor';
                    break;
                case '[NDB]':
                    $activeSection = 'ndb';
                    break;
                case '[FIXES]':
                    $activeSection = 'fixes';
                    break;
                case '[AIRPORT]':
                    $activeSection = 'airport';
                    break;
                case '[RUNWAY]':
                    $activeSection = 'runway';
                    break;
                case '[SID]':
                    $activeSection = 'sid';
                    break;
                case '[STAR]':
                    $activeSection = 'star';
                    break;
                case '[ARTCC HIGH]':
                    // $activeSection = 'artcc high';
                    $activeSection = 'artcc';
                    break;
                case '[ARTCC]':
                    $activeSection = 'artcc';
                    break;
                case '[ARTCC LOW]':
                    // $activeSection = 'artcc low';
                    $activeSection = 'artcc';
                    break;
                case '[GEO]':
                    $activeSection = 'geo';
                    break;
                case '[REGIONS]':
                    $activeSection = 'regions';
                    break;
                case '[HIGH AIRWAY]':
                    $activeSection = 'airway high';
                    break;
                case '[LOW AIRWAY]':
                    $activeSection = 'airway low';
                    break;
                default:
                    # code...
                    break;
            }

            // Work with the line
            if (Str::startsWith($line, '[')) {
                continue;
            } // Skip Section Markers for further parsing
            if (Str::startsWith($line, ';')) {
                continue;
            } // Skip Comment Markers for further parsing

            if (Str::startsWith($line, '#define')) {
                $activeSection = null; // Colors have a own section in our data array
                // This line defines a color
                $ce = preg_split('/\s+/', $line, -1, PREG_SPLIT_NO_EMPTY);
                $result['colors'][$ce[1]] = $ce[2];
            }

            $ls = preg_split('/\s+/', $line, -1, PREG_SPLIT_NO_EMPTY);

            switch ($activeSection) {
                case 'info':
                    if (!$isCustom) {
                        $result['info'][] = $line;
                    }
                    break;
                case 'vor':
                    $result['vor'][$ls[0]] = ['freq' => $ls[1], 'lat' => $ls[2], 'lon' => $ls[3]];
                    break;
                case 'ndb':
                    $result['ndb'][$ls[0]] = ['freq' => $ls[1], 'lat' => $ls[2], 'lon' => $ls[3]];
                    break;
                case 'fixes':
                    $result['fixes'][$ls[0]] = ['lat' => $ls[1], 'lon' => $ls[2]];
                    break;
                case 'airport':
                    $result['airport'][$ls[0]] = ['twrfreq' => $ls[1], 'lat' => $ls[2], 'lon' => $ls[3], 'as' => $ls[4]];
                    break;
                case 'runway':
                    $result['runway'][$ls[8]][] = $line;
                    break;
                case 'sid':
                    if (sizeof($ls) == 8) {
                        $currentSID = ['icao' => $ls[0], 'rwy' => $ls[2], 'id' => $ls[3]];
                        $result['sid'][$ls[0]][$ls[2]][$ls[3]][] = [
                            'lat_from' => $ls[4],
                            'lon_from' => $ls[5],
                            'lat_to' => $ls[6],
                            'lon_to' => $ls[7],
                        ];
                    } else {
                        $result['sid'][$currentSID['icao']][$currentSID['rwy']][$currentSID['id']][] = [
                            'lat_from' => $ls[0],
                            'lon_from' => $ls[1],
                            'lat_to' => $ls[2],
                            'lon_to' => $ls[3],
                        ];
                    }
                    break;
                case 'star':
                    // Filter out holdings for now
                    if ($ls[sizeof($ls) - 1] == 'COLOR_Holding') {
                        break;
                    }
                    // Parse actual stars
                    if (sizeof($ls) == 4) {
                        // Append coordinates to current active STAR
                        $result['star'][$currentSTAR['icao']][$currentSTAR['id']][] = [
                            'lat_from' => $ls[0],
                            'lon_from' => $ls[1],
                            'lat_to' => $ls[2],
                            'lon_to' => $ls[3],
                        ];
                    } else {
                        // New STAR found
                        $starIdentIndices = sizeof($ls) - 4; // Last for line split items are coordinates
                        $starId = '';
                        for ($i = 1; $i < $starIdentIndices; $i++) {
                            $starId .= ' ' . $ls[$i];
                        }
                        $currentSTAR = ['icao' => $ls[0], 'id' => trim($starId)];
                        $result['star'][$currentSTAR['icao']][$currentSTAR['id']][] = [
                            'lat_from' => $ls[sizeof($ls) - 4],
                            'lon_from' => $ls[sizeof($ls) - 3],
                            'lat_to' => $ls[sizeof($ls) - 2],
                            'lon_to' => $ls[sizeof($ls) - 1],
                        ];
                    }
                    break;
                case 'artcc':
                    // If a color is defined for the airspace we need to extract that
                    if (sizeof($ls) > 4) {
                        // Maybe color definition at the end or artcc name at the beginning
                        if (preg_match('/^[WE]{1}[0-9]{3}\.[0-9]{2}\.[0-9]{2}\.[0-9]{3}/', $ls[sizeof($ls) - 1])) {
                            // Last index of the line split is a coordinate
                            $artccIdentIndices = sizeof($ls) - 4;
                            $artccIdent = '';
                            for ($i = 0; $i < $artccIdentIndices; $i++) {
                                $artccIdent .= ' ' . $ls[$i];
                            }
                            $currentARTCC = ['id' => trim($artccIdent)];
                            $result['artcc'][$currentARTCC['id']][] = [
                                'lat_from' => $ls[sizeof($ls) - 4],
                                'lon_from' => $ls[sizeof($ls) - 3],
                                'lat_to' => $ls[sizeof($ls) - 2],
                                'lon_to' => $ls[sizeof($ls) - 1],
                            ];
                        } else {
                            // Last index is a color definition
                            $artccIdentIndices = sizeof($ls) - 5;
                            if ($artccIdentIndices >= 1) {
                                $artccIdent = '';
                                for ($i = 0; $i < $artccIdentIndices; $i++) {
                                    $artccIdent .= ' ' . $ls[$i];
                                }
                                $currentARTCC = ['id' => trim($artccIdent), 'color' => $ls[sizeof($ls) - 1]];
                            } else {
                                $currentARTCC['color'] = $ls[sizeof($ls) - 1];
                            }
                            $result['artcc'][$currentARTCC['id']][] = [
                                'lat_from' => $ls[sizeof($ls) - 5],
                                'lon_from' => $ls[sizeof($ls) - 4],
                                'lat_to' => $ls[sizeof($ls) - 3],
                                'lon_to' => $ls[sizeof($ls) - 2],
                            ];
                        }
                    } else {
                        $result['artcc'][$currentARTCC['id']][] = [
                            'lat_from' => $ls[0],
                            'lon_from' => $ls[1],
                            'lat_to' => $ls[2],
                            'lon_to' => $ls[3],
                        ];
                    }
                    break;
                case 'geo':
                    if (!Str::startsWith($line, ';')) {
                        if (sizeof($ls) > 5) {
                            // New section
                            $geoIndices = sizeof($ls) - 5;
                            $geoIdent = '';
                            for ($i = 0; $i < $geoIndices; $i++) {
                                $geoIdent .= ' ' . $ls[$i];
                            }
                            $currentGEO['id'] = trim($geoIdent);
                        }
                        if (sizeof($ls) < 5) {
                            $result['geo'][$currentGEO['id']]['coords'][] = [
                                'lat_from' => $ls[sizeof($ls) - 4],
                                'lon_from' => $ls[sizeof($ls) - 3],
                                'lat_to' => $ls[sizeof($ls) - 2],
                                'lon_to' => $ls[sizeof($ls) - 1],
                            ];
                        } else {
                            $result['geo'][$currentGEO['id']]['coords'][] = [
                                'lat_from' => $ls[sizeof($ls) - 5],
                                'lon_from' => $ls[sizeof($ls) - 4],
                                'lat_to' => $ls[sizeof($ls) - 3],
                                'lon_to' => $ls[sizeof($ls) - 2],
                                'color' => $ls[sizeof($ls) - 1],
                            ];
                        }
                    }
                    break;
                case 'regions':
                    if (!Str::startsWith($line, ';')) {
                        if (Str::startsWith($line, 'REGIONNAME')) {
                            $rn = '';
                            for ($i = 1; $i < sizeof($ls); $i++) {
                                $rn .= ' ' . $ls[$i];
                            }
                            $rn = trim($rn);
                            if ($currentRegion === null) {
                                // New to this section
                                $currentRegionIterator = 0;
                                $currentRegion['name'] = $rn;
                                $currentRegion['regions'] = [];
                            } else {
                                if ($currentRegion['name'] != $rn) {
                                    $result['regions'][$currentRegion['name']] = $currentRegion['regions'];
                                    $currentRegion['name'] = $rn;
                                    $currentRegion['regions'] = [];
                                    $currentRegionIterator = 0;
                                }
                            }
                        } else {
                            if (sizeof($ls) == 3) {
                                $currentRegionIterator++;
                                $currentRegion['regions'][$currentRegionIterator]['color'] = $ls[0];
                                $currentRegion['regions'][$currentRegionIterator]['coords'][] = ['lat' => $ls[1], 'lon' => $ls[2]];
                            }
                            if (sizeof($ls) == 2) {
                                $currentRegion['regions'][$currentRegionIterator]['coords'][] = ['lat' => $ls[0], 'lon' => $ls[1]];
                            }
                        }
                    }
                    break;
                case 'airway high':
                    $result['airway high'][$ls[0]][] = $ls[1] . ' ' . $ls[2] . ' ' . $ls[3] . ' ' . $ls[4];
                    break;
                case 'airway low':
                    $result['airway low'][$ls[0]][] = $ls[1] . ' ' . $ls[2] . ' ' . $ls[3] . ' ' . $ls[4];
                    break;
                default:
                    break;
            }
        }

        if ($currentRegion !== null && !array_key_exists($currentRegion['name'], $result['regions'])) {
            $result['regions'][$currentRegion['name']] = $currentRegion['regions'];
        }

        // Clear buffers
        unset($currentSID);
        unset($currentSTAR);
        unset($currentARTCC);
        unset($currentGEO);
        unset($currentRegion);

        return $result;
    }

    /**
     * Generate SCT output
     *
     * @return string
     */
    public static function generateCombinedSectorfile($sctData)
    {
        $sctOutput = '; ==================================================' . PHP_EOL;
        $sctOutput .= '; VATSIM GERMANY SECTORFILE COMBINER' . PHP_EOL;
        $sctOutput .= '; This sectorfile has been generated by VATSIM Germany Sectorfile Combiner' . PHP_EOL;
        $sctOutput .= '; This file MUST NOT be distributed to anyone outside the VATSIM Network' . PHP_EOL;
        $sctOutput .= '; For use on the VATSIM Network ONLY.' . PHP_EOL;
        $sctOutput .= '; For FLIGHTSIMULATION use ONLY.' . PHP_EOL;
        $sctOutput .= '; ==================================================' . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
        // Build Info Section
        $sctOutput .= '; ==================================================' . PHP_EOL;
        $sctOutput .= '[INFO]' . PHP_EOL;
        $sctOutput .= '; ==================================================' . PHP_EOL;
        foreach ($sctData['info'] as $infoline) {
            $sctOutput .= $infoline . PHP_EOL;
        }
        $sctOutput .= PHP_EOL . '; ==================================================' . PHP_EOL;
        $sctOutput .= '; Color Definitions' . PHP_EOL;
        $sctOutput .= '; (BLUE x 65536) + (GREEN x 256) + RED' . PHP_EOL;
        $sctOutput .= '; ==================================================' . PHP_EOL;
        foreach ($sctData['colors'] as $cn => $cv) {
            $sctOutput .= '#define ' . $cn . ' ' . $cv . PHP_EOL;
        }
        // VOR Section
        $sctOutput .= PHP_EOL . '; ==================================================' . PHP_EOL;
        $sctOutput .= '[VOR]' . PHP_EOL;
        $sctOutput .= '; ==================================================' . PHP_EOL;
        foreach ($sctData['vor'] as $ident => $data) {
            $sctOutput .= $ident . ' ' . $data['freq'] . ' ' . $data['lat'] . ' ' . $data['lon'] . PHP_EOL;
        }
        // NDB Section
        $sctOutput .= PHP_EOL . '; ==================================================' . PHP_EOL;
        $sctOutput .= '[NDB]' . PHP_EOL;
        $sctOutput .= '; ==================================================' . PHP_EOL;
        foreach ($sctData['ndb'] as $ident => $data) {
            $sctOutput .= $ident . ' ' . $data['freq'] . ' ' . $data['lat'] . ' ' . $data['lon'] . PHP_EOL;
        }
        // FIXES Section
        $sctOutput .= PHP_EOL . '; ==================================================' . PHP_EOL;
        $sctOutput .= '[FIXES]' . PHP_EOL;
        $sctOutput .= '; ==================================================' . PHP_EOL;
        foreach ($sctData['fixes'] as $ident => $data) {
            $sctOutput .= $ident . ' ' . $data['lat'] . ' ' . $data['lon'] . PHP_EOL;
        }
        // AIRPORT Section
        $sctOutput .= PHP_EOL . '; ==================================================' . PHP_EOL;
        $sctOutput .= '[AIRPORT]' . PHP_EOL;
        $sctOutput .= '; ==================================================' . PHP_EOL;
        foreach ($sctData['airport'] as $icao => $data) {
            $sctOutput .= $icao . ' ' . $data['twrfreq'] . ' ' . $data['lat'] . ' ' . $data['lon'] . ' ' . $data['as'] . PHP_EOL;
        }
        // RUNWAY Section
        $sctOutput .= PHP_EOL . '; ==================================================' . PHP_EOL;
        $sctOutput .= '[RUNWAY]' . PHP_EOL;
        $sctOutput .= '; ==================================================' . PHP_EOL;
        foreach ($sctData['runway'] as $icao => $data) {
            $sctOutput .= '; ' . $icao . PHP_EOL;
            foreach ($data as $rwyLine) {
                $sctOutput .= $rwyLine . PHP_EOL;
            }
        }
        // SID / STAR SECTOR FILE FORMAT DEFINITION
        // An individual diagram consists of one or more lines in the sector file. Each line defines a single line segment in the diagram. The first line of the diagram definition contains the name of the diagram. The name field must be exactly 26 characters in length. If the name of the diagram is shorter than 26 characters, trailing spaces must be added to fill the 26 characters. After the first 26 characters, there can be one or more optional spaces, followed by the latitude and longitude of the start and end points of the line segment, followed by an optional color name or value. If no color name or value is given, the diagram will be drawn using the default SID or STAR color as defined in the radar client settings.
        // Subsequent lines in a diagram definition must have 26 spaces, followed by the latitude and longitude for the start and end points of the current line segment, followed by an optional color name or value. In other words, subsequent segment definitions are identical to the starting segment definition, except that only the starting segment contains the name of the diagram in the first 26 characters. VRC will continue reading lines and adding them to the current diagram definition until it encounters the start of a new diagram (signified by a name present in the first 26 characters) or the start of a new section.
        // SID Section
        $sctOutput .= PHP_EOL . '; ==================================================' . PHP_EOL;
        $sctOutput .= '[SID]' . PHP_EOL;
        $sctOutput .= '; ==================================================' . PHP_EOL;
        foreach ($sctData['sid'] as $icao => $runways) {
            $sctOutput .= '; ' . $icao . PHP_EOL;
            foreach ($runways as $runway => $sids) {
                $sctOutput .= '; ' . $runway . PHP_EOL;
                foreach ($sids as $sid => $coords) {
                    $firstLine = true; // New SID...
                    $sidIdentifier = $icao . ' SID ' . $runway . '  ' . $sid;
                    foreach ($coords as $coord) {
                        if ($firstLine) {
                            $sctOutput .= $sidIdentifier;
                            for ($i = 0; $i < 26 - strlen($sidIdentifier) + 1; $i++) {
                                // 26 chars ident + 1 additional whitespace
                                $sctOutput .= ' ';
                            }
                            $sctOutput .= $coord['lat_from'] . ' ' . $coord['lon_from'] . ' ' . $coord['lat_to'] . ' ' . $coord['lon_to'] . PHP_EOL;
                            $firstLine = false;
                        } else {
                            for ($i = 0; $i < 27; $i++) {
                                // 26 + 1 whitespaces
                                $sctOutput .= ' ';
                            }
                            $sctOutput .= $coord['lat_from'] . ' ' . $coord['lon_from'] . ' ' . $coord['lat_to'] . ' ' . $coord['lon_to'] . PHP_EOL;
                        }
                    }
                }
            }
        }
        // STAR Section
        $sctOutput .= PHP_EOL . '; ==================================================' . PHP_EOL;
        $sctOutput .= '[STAR]' . PHP_EOL;
        $sctOutput .= '; ==================================================' . PHP_EOL;
        foreach ($sctData['star'] as $icao => $stars) {
            $sctOutput .= '; ' . $icao . PHP_EOL;
            foreach ($stars as $star => $coords) {
                $firstLine = true; // New STAR...
                $starIdentifier = $icao . ' ' . $star;
                foreach ($coords as $coord) {
                    if ($firstLine) {
                        $sctOutput .= $starIdentifier;
                        for ($i = 0; $i < 26 - strlen($starIdentifier) + 1; $i++) {
                            // 26 chars ident + 1 additional whitespace
                            $sctOutput .= ' ';
                        }
                        $sctOutput .= $coord['lat_from'] . ' ' . $coord['lon_from'] . ' ' . $coord['lat_to'] . ' ' . $coord['lon_to'] . PHP_EOL;
                        $firstLine = false;
                    } else {
                        for ($i = 0; $i < 27; $i++) {
                            // 26 + 1 whitespaces
                            $sctOutput .= ' ';
                        }
                        $sctOutput .= $coord['lat_from'] . ' ' . $coord['lon_from'] . ' ' . $coord['lat_to'] . ' ' . $coord['lon_to'] . PHP_EOL;
                    }
                }
            }
        }
        // ARTCC Section
        $sctOutput .= PHP_EOL . '; ==================================================' . PHP_EOL;
        $sctOutput .= '[ARTCC]' . PHP_EOL;
        $sctOutput .= '; ==================================================' . PHP_EOL;
        foreach ($sctData['artcc'] as $id => $coords) {
            if (Str::startsWith($id, 'Restricted')) {
                continue;
            }

            $sctOutput .= '; ' . $id . PHP_EOL;
            $firstLine = true;
            foreach ($coords as $coord) {
                if ($firstLine) {
                    $sctOutput .= $id;
                    for ($i = 0; $i < 26 - strlen($id) + 1; $i++) {
                        // 26 chars ident + 1 additional whitespace
                        $sctOutput .= ' ';
                    }
                    $sctOutput .= $coord['lat_from'] . ' ' . $coord['lon_from'] . ' ' . $coord['lat_to'] . ' ' . $coord['lon_to'] . PHP_EOL;
                    $firstLine = false;
                } else {
                    for ($i = 0; $i < 27; $i++) {
                        // 26 + 1 whitespaces
                        $sctOutput .= ' ';
                    }
                    $sctOutput .= $coord['lat_from'] . ' ' . $coord['lon_from'] . ' ' . $coord['lat_to'] . ' ' . $coord['lon_to'] . PHP_EOL;
                }
            }
        }
        // GEO Section
        $sctOutput .= PHP_EOL . '; ==================================================' . PHP_EOL;
        $sctOutput .= '[GEO]' . PHP_EOL;
        $sctOutput .= '; ==================================================' . PHP_EOL;
        foreach ($sctData['geo'] as $id => $geo) {
            if (strlen($id) >= 26) {
                // Trim it down to max 26 chars
                $gid = substr($id, 0, 26);
            } else {
                $gid = $id;
            }
            $sctOutput .= '; ' . $gid . PHP_EOL;
            $firstLine = true;
            foreach ($geo['coords'] as $coord) {
                if ($firstLine) {
                    $sctOutput .= $gid;
                    for ($i = 0; $i < 26 - strlen($gid) + 1; $i++) {
                        // 26 chars ident + 1 additional whitespace
                        $sctOutput .= ' ';
                    }
                    if (array_key_exists('color', $coord)) {
                        $sctOutput .=
                            $coord['lat_from'] .
                            ' ' .
                            $coord['lon_from'] .
                            ' ' .
                            $coord['lat_to'] .
                            ' ' .
                            $coord['lon_to'] .
                            ' ' .
                            $coord['color'] .
                            PHP_EOL;
                    } else {
                        $sctOutput .= $coord['lat_from'] . ' ' . $coord['lon_from'] . ' ' . $coord['lat_to'] . ' ' . $coord['lon_to'] . PHP_EOL;
                    }
                    $firstLine = false;
                } else {
                    for ($i = 0; $i < 27; $i++) {
                        // 26 + 1 whitespaces
                        $sctOutput .= ' ';
                    }
                    if (array_key_exists('color', $coord)) {
                        $sctOutput .=
                            $coord['lat_from'] .
                            ' ' .
                            $coord['lon_from'] .
                            ' ' .
                            $coord['lat_to'] .
                            ' ' .
                            $coord['lon_to'] .
                            ' ' .
                            $coord['color'] .
                            PHP_EOL;
                    } else {
                        $sctOutput .= $coord['lat_from'] . ' ' . $coord['lon_from'] . ' ' . $coord['lat_to'] . ' ' . $coord['lon_to'] . PHP_EOL;
                    }
                }
            }
        }
        // REGIONS Section
        $sctOutput .= PHP_EOL . '; ==================================================' . PHP_EOL;
        $sctOutput .= '[REGIONS]' . PHP_EOL;
        $sctOutput .= '; ==================================================' . PHP_EOL;
        foreach ($sctData['regions'] as $id => $regions) {
            if (strlen($id) >= 26) {
                // Trim it down to max 26 chars
                $rid = substr($id, 0, 26);
            } else {
                $rid = $id;
            }
            $sctOutput .= '; Region ' . $rid . PHP_EOL;
            foreach ($regions as $region) {
                $firstLine = true;
                $sctOutput .= 'REGIONNAME ' . $rid . PHP_EOL;
                foreach ($region['coords'] as $coord) {
                    if ($firstLine) {
                        $sctOutput .= $region['color'];
                        for ($i = 0; $i < 26 - strlen($region['color']) + 1; $i++) {
                            // 26 chars ident + 1 additional whitespace
                            $sctOutput .= ' ';
                        }
                        $sctOutput .= $coord['lat'] . ' ' . $coord['lon'] . PHP_EOL;
                        $firstLine = false;
                    } else {
                        for ($i = 0; $i < 27; $i++) {
                            // 26 + 1 whitespaces
                            $sctOutput .= ' ';
                        }
                        $sctOutput .= $coord['lat'] . ' ' . $coord['lon'] . PHP_EOL;
                    }
                }
            }
        }
        // AIRWAYS Section
        $sctOutput .= PHP_EOL . '; ==================================================' . PHP_EOL;
        $sctOutput .= '[HIGH AIRWAY]' . PHP_EOL;
        $sctOutput .= '; ==================================================' . PHP_EOL;
        foreach ($sctData['airway high'] as $airway => $points) {
            foreach ($points as $p) {
                $sctOutput .= $airway . ' ' . $p . PHP_EOL;
            }
        }
        $sctOutput .= PHP_EOL . '; ==================================================' . PHP_EOL;
        $sctOutput .= '[LOW AIRWAY]' . PHP_EOL;
        $sctOutput .= '; ==================================================' . PHP_EOL;
        foreach ($sctData['airway low'] as $airway => $points) {
            foreach ($points as $p) {
                $sctOutput .= $airway . ' ' . $p . PHP_EOL;
            }
        }
        return $sctOutput;
    }

    public static function parseExtensionFile($filePath)
    {
        $result = [
            'freetext' => [],
            'sidsstars' => [],
            'positions' => [],
            'airspace' => [],
            'radar' => [],
            'radarholes' => [],
            'ground' => [],
        ];

        $rgIdent = substr(Str::afterLast($filePath, '/'), 0, 4);

        $activeSection = null;
        $currentAirspace = [];
        $currentRadarHole = [];

        foreach (file($filePath) as $line) {
            $line = trim($line);
            if (strlen($line) == 0) {
                continue;
            }

            switch ($line) {
                case '[FREETEXT]':
                    $activeSection = 'freetext';
                    break;
                case '[SIDSSTARS]':
                    $activeSection = 'sidsstars';
                    break;
                case '[POSITIONS]':
                    $activeSection = 'positions';
                    break;
                case '[AIRSPACE]':
                    $activeSection = 'airspace';
                    break;
                case '[RADAR]':
                    $activeSection = 'radar';
                    break;
                case '[GROUND]':
                    $activeSection = 'ground';
                    break;
                default:
                    break;
            }

            // Work with the line
            if (Str::startsWith($line, '[')) {
                continue;
            } // Skip Section Markers for further parsing

            $ls = preg_split('/:/', $line, -1, PREG_SPLIT_NO_EMPTY);

            switch ($activeSection) {
                case 'freetext':
                    if (sizeof($ls) == 3) {
                        $result['freetext'][$ls[0] . $ls[1]] = ['lat' => $ls[0], 'lon' => $ls[1], 'txt' => $ls[2]];
                    }
                    if (sizeof($ls) == 4) {
                        $result['freetext'][$ls[0] . $ls[1]] = ['lat' => $ls[0], 'lon' => $ls[1], 'txt' => $ls[3], 'grp' => $ls[2]];
                    }
                    break;
                case 'sidsstars':
                    // <type of route (SID or STAR)>:<airport of destination/departure>:<runway related to that route>:<routing name>:<route points>
                    if (sizeof($ls) == 5) {
                        $result['sidsstars'][$ls[3]] = ['type' => $ls[0], 'icao' => $ls[1], 'rwy' => $ls[2], 'name' => $ls[3], 'route' => $ls[4]];
                    }
                    break;
                case 'positions':
                    // <name of position>:<radio callsign>:<frequency>:<identifier>:<middle letter>:<prefix>:<suffix>:
                    // <not used>:<not used>:<A code start of range>:<A code end of range>[:<VIS center1 latitude>:<VIS center1 longitude>[: ... ]]
                    if (sizeof($ls) == 9) {
                        // No vis center defined
                        $result['positions'][$ls[0]] = [
                            'name' => $ls[0],
                            'callsign' => $ls[1],
                            'freq' => $ls[2],
                            'ident' => $ls[3],
                            'ml' => $ls[4],
                            'pre' => $ls[5],
                            'suf' => $ls[6],
                            'sqstart' => $ls[7],
                            'sqend' => $ls[8],
                        ];
                    }
                    if (sizeof($ls) > 9) {
                        // vis center defined
                        $vis = '';
                        for ($i = 9; $i < sizeof($ls); $i++) {
                            $vis .= ':' . $ls[$i];
                        }

                        $result['positions'][$ls[0]] = [
                            'name' => $ls[0],
                            'callsign' => $ls[1],
                            'freq' => $ls[2],
                            'ident' => $ls[3],
                            'ml' => $ls[4],
                            'pre' => $ls[5],
                            'suf' => $ls[6],
                            'sqstart' => $ls[7],
                            'sqend' => $ls[8],
                            'vis' => $vis,
                        ];
                    }
                    break;
                case 'airspace':
                    if (Str::startsWith($line, ['SECTOR', 'COPX', 'FIR_COPX', 'MSAW']) && !empty($currentAirspace)) {
                        $result['airspace'][$rgIdent][] = $currentAirspace;
                        $currentAirspace = [];
                    }
                    switch ($ls[0]) {
                        case 'SECTORLINE':
                            $currentAirspace = [
                                'type' => 'SECTORLINE',
                                'name' => $ls[1],
                            ];
                            break;
                        case 'DISPLAY':
                            $currentAirspace['display'][] = $line;
                            break;
                        case 'COORD':
                            $currentAirspace['coords'][] = $line;
                            break;
                        case 'CIRCLE_SECTORLINE':
                            $currentAirspace['coords'][] = $line;
                            break;
                        case 'SECTOR':
                            $currentAirspace = [
                                'type' => 'SECTOR',
                                'name' => $ls[1],
                                'lowerLimit' => $ls[2],
                                'upperLimit' => $ls[3],
                            ];
                            break;
                        case 'OWNER':
                            $currentAirspace['owner'] = $line;
                            break;
                        case 'ALTOWNER':
                            $currentAirspace['altowner'] = $line;
                            break;
                        case 'BORDER':
                            $currentAirspace['border'] = $line;
                            break;
                        case 'ACTIVE':
                            $currentAirspace['active'] = $line;
                            break;
                        case 'GUEST':
                            $currentAirspace['guest'] = $line;
                            break;
                        case 'DEPAPT':
                            $currentAirspace['depapt'] = $line;
                            break;
                        case 'ARRAPT':
                            $currentAirspace['arrapt'] = $line;
                            break;
                        case 'COPX':
                            $currentAirspace = [
                                'type' => 'COPX',
                                'copx' => $line,
                            ];
                            break;
                        case 'FIR_COPX':
                            $currentAirspace = [
                                'type' => 'FIR_COPX',
                                'copx' => $line,
                            ];
                            break;
                        case 'MSAW':
                            $currentAirspace = [
                                'type' => 'MSAW',
                                'name' => $ls[1],
                                'altitude' => $ls[2],
                            ];
                            break;
                        default:
                            break;
                    }
                    break;
                case 'radar':
                    switch ($ls[0]) {
                        case 'RADAR2':
                            $result['radar'][$ls[1]] = $line;
                            break;
                        case 'HOLE':
                            if (!empty($currentRadarHole)) {
                                $result['radarholes'][] = $currentRadarHole;
                                $currentRadarHole = [];
                            }
                            $currentRadarHole = [
                                'definition' => $line,
                            ];
                            break;
                        case 'COORD':
                            $currentRadarHole['coords'][] = $line;
                            break;
                        default:
                            break;
                    }
                    break;
                default:
                    break;
            }
        }

        if (!empty($currentAirspace)) {
            $result['airspace'][$rgIdent][] = $currentAirspace;
        }
        unset($currentAirspace);
        if (!empty($currentRadarHole)) {
            $result['radarholes'][] = $currentRadarHole;
        }
        unset($currentRadarHole);

        return $result;
    }

    public static function generateCombinedExtensionfile($eseData)
    {
        $eseOutput = '; ==================================================' . PHP_EOL;
        $eseOutput .= '; VATSIM GERMANY SECTORFILE COMBINER' . PHP_EOL;
        $eseOutput .= '; This sectorfile has been generated by VATSIM Germany Sectorfile Combiner.' . PHP_EOL;
        $eseOutput .= '; This file MUST NOT be distributed to anyone outside the VATSIM Network.' . PHP_EOL;
        $eseOutput .= '; For use on the VATSIM Network ONLY.' . PHP_EOL;
        $eseOutput .= '; For FLIGHTSIMULATION use ONLY.' . PHP_EOL;
        $eseOutput .= '; ==================================================' . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;

        $eseOutput .= '; ==================================================' . PHP_EOL;
        $eseOutput .= '[FREETEXT]' . PHP_EOL;
        $eseOutput .= '; ==================================================' . PHP_EOL;
        foreach ($eseData['freetext'] as $txt) {
            if (array_key_exists('grp', $txt)) {
                $eseOutput .= $txt['lat'] . ':' . $txt['lon'] . ':' . $txt['grp'] . ':' . $txt['txt'] . PHP_EOL;
            } else {
                $eseOutput .= $txt['lat'] . ':' . $txt['lon'] . ':' . $txt['txt'] . PHP_EOL;
            }
        }

        $eseOutput .= '; ==================================================' . PHP_EOL;
        $eseOutput .= '[SIDSSTARS]' . PHP_EOL;
        $eseOutput .= '; ==================================================' . PHP_EOL;
        foreach ($eseData['sidsstars'] as $s) {
            $eseOutput .= $s['type'] . ':' . $s['icao'] . ':' . $s['rwy'] . ':' . $s['name'] . ':' . $s['route'] . PHP_EOL;
        }

        $eseOutput .= '; ==================================================' . PHP_EOL;
        $eseOutput .= '[POSITIONS]' . PHP_EOL;
        $eseOutput .= '; ==================================================' . PHP_EOL;
        foreach ($eseData['positions'] as $pos) {
            $eseOutput .=
                $pos['name'] .
                ':' .
                $pos['callsign'] .
                ':' .
                $pos['freq'] .
                ':' .
                $pos['ident'] .
                ':' .
                $pos['ml'] .
                ':' .
                $pos['pre'] .
                ':' .
                $pos['suf'] .
                ':-:-:' .
                $pos['sqstart'] .
                ':' .
                $pos['sqend'];
            if (array_key_exists('vis', $pos)) {
                $eseOutput .= $pos['vis'];
            }
            $eseOutput .= PHP_EOL;
        }

        $eseOutput .= '; ==================================================' . PHP_EOL;
        $eseOutput .= '[AIRSPACE]' . PHP_EOL;
        $eseOutput .= '; ==================================================' . PHP_EOL;
        foreach ($eseData['airspace'] as $rgIdent => $rgData) {
            foreach ($rgData as $as) {
                if ($as['type'] == 'SECTORLINE') {
                    $eseOutput .= 'SECTORLINE:' . $rgIdent . $as['name'] . PHP_EOL;
                    foreach ($as['coords'] as $c) {
                        $eseOutput .= $c . PHP_EOL;
                    }
                    if (array_key_exists('display', $as)) {
                        foreach ($as['display'] as $d) {
                            $displaySplit = explode(':', $d);
                            $eseOutput .= $displaySplit[0];
                            for ($i = 1; $i < sizeof($displaySplit); $i++) {
                                $eseOutput .= ':' . $rgIdent . $displaySplit[$i];
                            }
                            $eseOutput .= PHP_EOL;
                        }
                    }
                }
                if ($as['type'] == 'SECTOR') {
                    $eseOutput .= 'SECTOR:' . $rgIdent . $as['name'] . ':' . $as['lowerLimit'] . ':' . $as['upperLimit'] . PHP_EOL;
                    if (array_key_exists('owner', $as)) {
                        $eseOutput .= $as['owner'] . PHP_EOL;
                    }
                    if (array_key_exists('altowner', $as)) {
                        $eseOutput .= $as['altowner'] . PHP_EOL;
                    }
                    $eseOutput .= str_replace(':', ':' . $rgIdent, $as['border']) . PHP_EOL;
                    if (array_key_exists('active', $as)) {
                        $eseOutput .= $as['active'] . PHP_EOL;
                    }
                    if (array_key_exists('guest', $as)) {
                        $eseOutput .= $as['guest'] . PHP_EOL;
                    }
                    if (array_key_exists('depapt', $as)) {
                        $eseOutput .= $as['depapt'] . PHP_EOL;
                    }
                    if (array_key_exists('arrapt', $as)) {
                        $eseOutput .= $as['arrapt'] . PHP_EOL;
                    }
                }
                if ($as['type'] == 'COPX' || $as['type'] == 'FIR_COPX') {
                    $copxSplit = explode(':', $as['copx']);
                    for ($i = 0; $i < sizeof($copxSplit); $i++) {
                        if ($i == 0) {
                            $eseOutput .= $copxSplit[$i];
                        } elseif ($i == 6 || $i == 7) {
                            $eseOutput .= ':' . $rgIdent . $copxSplit[$i];
                        } else {
                            $eseOutput .= ':' . $copxSplit[$i];
                        }
                    }
                    $eseOutput .= PHP_EOL;
                }
                if ($as['type'] == 'MSAW') {
                    $eseOutput .= 'MSAW:' . $as['name'] . ':' . $as['altitude'] . PHP_EOL;
                    foreach ($as['coords'] as $c) {
                        $eseOutput .= $c . PHP_EOL;
                    }
                }
            }
        }

        $eseOutput .= '; ==================================================' . PHP_EOL;
        $eseOutput .= '[RADAR]' . PHP_EOL;
        $eseOutput .= '; ==================================================' . PHP_EOL;
        foreach ($eseData['radar'] as $radarName => $radar) {
            $eseOutput .= $radar . PHP_EOL;
        }
        foreach ($eseData['radarholes'] as $hole) {
            $eseOutput .= $hole['definition'] . PHP_EOL;
            foreach ($hole['coords'] as $c) {
                $eseOutput .= $c . PHP_EOL;
            }
        }

        return $eseOutput;
    }

    /**
     * Method convertDMSToDecimal
     *
     * Convert a _latLng string into decimal coordinates
     *
     * @param $_latLng $_latLng The _latLng string
     *
     * @return void
     */
    public static function convertDMSToDecimal(string $_latLng)
    {
        $valid = false;
        $decimal_degrees = 0;
        $degrees = 0;
        $minutes = 0;
        $seconds = 0;
        $direction = 1;
        // Determine if there are extra periods in the input string
        $num_periods = substr_count($_latLng, '.');
        if ($num_periods > 1) {
            $temp = preg_replace('/\./', ' ', $_latLng, $num_periods - 1); // replace all but last period with delimiter
            $temp = trim(preg_replace('/[a-zA-Z]/', '', $temp)); // when counting chunks we only want numbers
            $chunk_count = count(explode(' ', $temp));
            if ($chunk_count > 2) {
                $_latLng = preg_replace('/\./', ' ', $_latLng, $num_periods - 1); // remove last period
            } else {
                $_latLng = str_replace('.', ' ', $_latLng); // remove all periods, not enough chunks left by keeping last one
            }
        }

        // Remove unneeded characters
        $_latLng = trim($_latLng);
        $_latLng = str_replace('º', ' ', $_latLng);
        $_latLng = str_replace('°', ' ', $_latLng);
        $_latLng = str_replace("'", ' ', $_latLng);
        $_latLng = str_replace('"', ' ', $_latLng);
        $_latLng = str_replace('  ', ' ', $_latLng);
        $_latLng = substr($_latLng, 0, 1) . str_replace('-', ' ', substr($_latLng, 1)); // remove all but first dash
        if ('' != $_latLng) {
            // DMS with the direction at the start of the string
            if (preg_match("/^([nsewoNSEWO]?)\s*(\d{1,3})\s+(\d{1,3})\s*(\d*\.?\d*)$/", $_latLng, $matches)) {
                $valid = true;
                $degrees = intval($matches[2]);
                $minutes = intval($matches[3]);
                $seconds = floatval($matches[4]);
                if ('S' == strtoupper($matches[1]) || 'W' == strtoupper($matches[1])) {
                    $direction = -1;
                }
            } elseif (
                preg_match(
                    // DMS with the direction at the end of the string
                    "/^(-?\d{1,3})\s+(\d{1,3})\s*(\d*(?:\.\d*)?)\s*([nsewoNSEWO]?)$/",
                    $_latLng,
                    $matches,
                )
            ) {
                $valid = true;
                $degrees = intval($matches[1]);
                $minutes = intval($matches[2]);
                $seconds = floatval($matches[3]);
                if ('S' == strtoupper($matches[4]) || 'W' == strtoupper($matches[4]) || $degrees < 0) {
                    $direction = -1;
                    $degrees = abs($degrees);
                }
            }
            if ($valid) {
                // A match was found, do the calculation
                $decimal_degrees = ($degrees + $minutes / 60 + $seconds / 3600) * $direction;
            } else {
                // Decimal degrees with a direction at the start of the string
                if (preg_match("/^([nsewNSEW]?)\s*(\d+(?:\.\d+)?)$/", $_latLng, $matches)) {
                    $valid = true;
                    if ('S' == strtoupper($matches[1]) || 'W' == strtoupper($matches[1])) {
                        $direction = -1;
                    }
                    $decimal_degrees = $matches[2] * $direction;
                } elseif (preg_match("/^(-?\d+(?:\.\d+)?)\s*([nsewNSEW]?)$/", $_latLng, $matches)) {
                    // Decimal degrees with a direction at the end of the string
                    $valid = true;
                    if ('S' == strtoupper($matches[2]) || 'W' == strtoupper($matches[2]) || $degrees < 0) {
                        $direction = -1;
                        $degrees = abs($degrees);
                    }
                    $decimal_degrees = $matches[1] * $direction;
                }
            }
        }
        if ($valid) {
            return $decimal_degrees;
        } else {
            return false;
        }
    }
}
