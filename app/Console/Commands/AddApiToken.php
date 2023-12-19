<?php

namespace App\Console\Commands;

use App\OpenApi\Controllers\ApiController;
use App\OpenApi\Models\ApiRouteToken;
use App\OpenApi\Models\ApiToken;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class AddApiToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vatger:add-api-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to add an API Token';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $description = $this->ask('description?');

        $this->info(json_encode(ApiController::collect_paths()));
        do {
            $var = $this->ask('allowed route_id [enter one or leave empty]?');
            if (!empty($var)) {
                $route_ids[] = $var;
            }
        } while (!empty($var));

        $expiration = $this->ask('expiration date [needed]?');

        $t = new ApiToken();
        $t->token = Str::random(16);
        $t->description = $description;
        $t->valid_till = Carbon::parse($expiration);
        $t->save();
        foreach ($route_ids as $route_id) {
            $rt = new ApiRouteToken();
            $rt->token_id = $t->id;
            $rt->route_id = $route_id;
            $rt->save();
        }
    }
}
