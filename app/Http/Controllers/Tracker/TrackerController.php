<?php

namespace App\Http\Controllers\Tracker;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

//use App\Http\Requests\Tracker\TrackerCreateRequest;
// Jobs
use App\Jobs\Trackers\TrackerJob;

class TrackerController extends Controller
{
    public function create(Request $request)
    {
        $this->dispatch(new TrackerJob($request->all()));
        return response()->success();
    }
}
