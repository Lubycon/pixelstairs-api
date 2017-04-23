<?php

namespace App\Http\Controllers\Tracker;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

// Jobs
use App\Jobs\Trackers\TrackerJob;

// Request
use App\Http\Requests\Tracker\TrackerPostRequest;

class TrackerController extends Controller
{
    public function create(TrackerPostRequest $request)
    {
        $this->dispatch(new TrackerJob($request->all()));
        return response()->success();
    }
}
