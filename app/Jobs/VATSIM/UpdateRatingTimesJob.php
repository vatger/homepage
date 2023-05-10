<?php

namespace App\Jobs\VATSIM;

use App\Libraries\VATSIM\APILibrary;
use App\Models\Membership\User\User;
use Brick\Math\Exception\DivisionByZeroException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UpdateRatingTimesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The cid that was last updated
     * @var int
     */
    private $_lastUpdatedCid = 0;

    /**
     * The amount of accounts in our database
     * @var int
     */
    private $_totalAccounts = 0;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_lastUpdatedCid = Cache::get('net.vatsim.api.ratings.lastupdated', 0);
        $this->_totalAccounts = User::query()->count();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('[UpdateRatingTimesJob]::Handle::Starting rating time updates. Starting with cid: ' . $this->_lastUpdatedCid + 1);
        // Determine the chunck size
        $cs = ceil($this->_totalAccounts / (24 * 60));
        if ($cs <= 10) {
            $cs = $this->_totalAccounts;
        }
        Log::info('[UpdateRatingTimesJob]::Handle::Chunk size: ' . $cs);
        $accountsToUpdate = User::where('id', '>', $this->_lastUpdatedCid)
            ->take($cs)
            ->get();
        foreach ($accountsToUpdate as $a) {
            if ($a != null) {
                $rt = APILibrary::RatingTimes($a);
                if ($rt !== false) {
                    $a->userData()->update([
                        'time_atc' => $rt->atc,
                        'time_pilot' => $rt->pilot,
                    ]);
                }
                Log::info('[UpdateRatingTimesJob]::Handle::Data::' . $a->id . '::' . json_encode($rt));
            }
        }
        if ($accountsToUpdate == null || $accountsToUpdate->count() < $cs) {
            Cache::put('net.vatsim.api.ratings.lastupdated', 0);
            Log::info('[UpdateRatingTimesJob]::Handle::Finished rating time updates. Full run completed. Resetting!');
        } else {
            Cache::put('net.vatsim.api.ratings.lastupdated', $accountsToUpdate->last()->id);
            Log::info('[UpdateRatingTimesJob]::Handle::Finished rating time updates with cid: ' . $accountsToUpdate->last()->id);
        }
    }

    /**
     * Gets called when Job fails
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        //TODO
    }
}
