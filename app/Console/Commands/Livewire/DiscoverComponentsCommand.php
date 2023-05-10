<?php

namespace App\Console\Commands\Livewire;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\LivewireComponentsFinder;
use ReflectionClass;
use SplFileInfo;

class DiscoverComponentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'livewire:discover';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Discover Livewire components with included submodules.';

    /**
     * A list of installed modules
     *
     * @var array
     */
    private $modules;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->modules = File::directories(base_path('modules'));
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        app(LivewireComponentsFinder::class)->build();

        $this->info('Livewire auto-discovery manifest rebuilt!');

        if (file_exists(base_path('bootstrap/cache/livewire-components.php'))) {
            $discoveries = require_once base_path('bootstrap/cache/livewire-components.php');

            foreach ($this->modules as $module) {
                $fs = new Filesystem();

                $module = collect($fs->allFiles(base_path('modules/atciss')))
                    ->map(function (SplFileInfo $file) {
                        return app()->getNamespace() .
                            Str::of($file->getPathname())
                                ->after(base_path() . '/modules/atciss/app/')
                                ->replace(['/', '.php'], ['\\', ''])
                                ->__toString();
                    })
                    ->filter(function (string $class) {
                        return is_subclass_of($class, Component::class) && !(new ReflectionClass($class))->isAbstract();
                    })
                    ->mapWithKeys(function ($class) {
                        return [$class::getName() => $class];
                    });

                $discoveries = array_merge($discoveries, $module->toArray());
            }

            foreach ($discoveries as $key => $value) {
                $this->info($key . ' => ' . $value);
            }

            $fs->put(base_path('bootstrap/cache/livewire-components.php'), '<?php return ' . var_export($discoveries, true) . ';', true);
        }
    }
}
