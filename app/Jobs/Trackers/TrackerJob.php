<?php

namespace App\Jobs\Trackers;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Tracker;
use Log;

class TrackerJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $requests;
    public $tracker;

    public function __construct($requests)
    {
        $this->requests = $requests;
    }

    public function handle()
    {
        Log::info('tracker start');

        $this->tracker = Tracker::create([
            "uuid" => $this->requests['uuid'],
            "current_url" => $this->requests['currentUrl'],
            "prev_url" => $this->requests['prevUrl'],
            "action" => $this->requests['action'],
        ]);

        Log::info('tracker end');
    }
}
