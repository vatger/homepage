<?php

namespace App\Console\Commands\Euroscope;

use App\Libraries\EuroScope\SectorDataLibrary;
use Illuminate\Console\Command;

class CombineSectorfiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'euroscope:sectorcombine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Combine all GNG provided sectorfiles into ONE';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        SectorDataLibrary::combineSectorFiles();
        return 0;
    }
}
