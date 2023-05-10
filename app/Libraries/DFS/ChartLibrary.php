<?php

namespace App\Libraries\DFS;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ChartLibrary
{
    /**
     * A listing of aerodromes available on the public aip
     *
     * @var array
     */
    private static $dfsAerodromes = [
        'EDFQ',
        'EDMA',
        'EDBH',
        'EDAB',
        'EDQD',
        'EDDB',
        'EDVE',
        'EDDW',
        'EDQC',
        'EDTD',
        'EDLW',
        'EDDC',
        'EDDL',
        'EDME',
        'EDWE',
        'EDDE',
        'EDFH',
        'EDDF',
        'EDNY',
        'EDQG',
        'EDDH',
        'EDHI',
        'EDDV',
        'EDQT',
        'EDAH',
        'EDQM',
        'EDSB',
        'EDVK',
        'EDHK',
        'EDDK',
        'EDTL',
        'EDAC',
        'EDDP',
        'EDHL',
        'EDBM',
        'EDFM',
        'EDJA',
        'EDTM',
        'EDLN',
        'EDDM',
        'EDDG',
        'EDBN',
        'EDLV',
        'EDDN',
        'EDMO',
        'EDLP',
        'EDDR',
        'EDAZ',
        'EDTY',
        'EDOP',
        'EDGS',
        'EDMS',
        'EDDS',
        'EDXW',
        'EDWI',
        'EDRZ',
    ];

    /**
     * Hexadicimal coded step size for normal update runs
     *
     * @var string
     */
    private static $stepSize = '1000'; // 4096 in HEX

    public static function loadDFSCharts($cycle, $output = null)
    {
        $_stepSize = $cycle ? 'FFFF' : self::$stepSize;
        return self::_getCharts($_stepSize, $output);
    }

    private static function _getCharts($stepSize, $output = null)
    {
        $icaos = '';
        foreach (self::$dfsAerodromes as $key => $value) {
            $icaos .= $value . '|';
        }
        $icaos = '[' . substr($icaos, 0, \strlen($icaos) - 1) . ']';

        $charts = Cache::get('org.vatsim-germany.navigation.aerodromes.charts.dfs', []);
        $lastUpdateStep = Cache::get('org.vatsim-germany.navigation.aerodromes.charts.updateStep', '0000');

        $start = \hexdec($lastUpdateStep) > \hexdec('0000') ? $lastUpdateStep : '0000';
        $end = \hexdec($lastUpdateStep) < \hexdec('FFFF') - \hexdec($stepSize) ? \dechex(\hexdec($lastUpdateStep) + \hexdec($stepSize)) : 'FFFF';

        $lastUpdateStep = \hexdec($end) < \hexdec('FFFF') ? $end : '0000';

        Cache::put('org.vatsim-germany.navigation.aerodromes.charts.updateStep', $lastUpdateStep);

        if ($output != null) {
            $output->info('Updating from ' . $start . ' till ' . $end);
            $pBar = $output->createProgressBar(\hexdec($end) - \hexdec($start));
            $pBar->start();
        }

        for ($i = \hexdec($start) + 1; $i <= \hexdec($end); $i++) {
            // if($output != null) $output->info('i: ' . $i . ' | HEX: ' . \dechex($i));
            if ($i <= \hexdec('000F')) {
                $link = 'https://aip.dfs.de/BasicIFR/pages/P0000' . \dechex($i) . '.html';
            } elseif ($i <= \hexdec('00FF')) {
                $link = 'https://aip.dfs.de/BasicIFR/pages/P000' . \dechex($i) . '.html';
            } elseif ($i <= \hexdec('0FFF')) {
                $link = 'https://aip.dfs.de/BasicIFR/pages/P00' . \dechex($i) . '.html';
            } else {
                $link = 'https://aip.dfs.de/BasicIFR/pages/P0' . \dechex($i) . '.html';
            }
            // if($output != null) $output->info('Looking for page: ' . $link);
            $response = self::_trace_url($link);
            // dd($response);
            preg_match('#\<meta.*?content="[0-9]*\; url=([^"]+)"\s*\/\>#', $response[0], $links);
            if (sizeof($links) == 2) {
                $newlink = 'https://aip.dfs.de/BasicIFR/' . substr($links[1], 3);
                // print $newlink;
                $response = self::_trace_url($newlink);
            }

            // We have a page
            $resultHtml = $response[0];

            $chartName = '';
            try {
                // if(preg_match('/<h2>(.*?)'.$icaos.'(.*?)<\/h2>/', $resultHtml, $chartNames)) {
                if (preg_match('/<h2>AD 2 ' . $icaos . '(.*?)<\/h2>/', $resultHtml, $chartNames)) {
                    $chartName = substr($chartNames[0], 4, strlen($chartNames[0]) - 9);

                    $chart = new DFSChart(explode(' ', $chartName, 2)[0], $chartName, date('ym'), date('Y/m/d'), $response[1]);
                    if ($output != null) {
                        $output->info('Found chart: ' . $chart->name);
                    }
                    $charts[$chart->id] = $chart;
                } elseif (\preg_match('/ERROR - Requested page not found !/', $response[0]) !== false) {
                    if ($output != null) {
                        $pBar->advance();
                    }
                    continue;
                }
            } catch (\Exception $e) {
                Log::error('[ChartLibrary::_getCharts] Failed parsing chartNames! ' . $link);
            }
            if ($output != null) {
                $pBar->advance();
            }
        }
        if ($output != null) {
            $pBar->finish();
            $output->info('Found ' . count($charts) . ' charts!');
        }

        Cache::forever('org.vatsim-germany.navigation.aerodromes.charts.dfs', $charts);
        return $charts;
    }

    private static function _trace_url($url)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $result = curl_exec($ch);
        $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);

        return [$result, $url];
    }
}
