<?php

namespace App\Http\Controllers\Service\Tracker;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

// Jobs
use App\Jobs\Trackers\TrackerJob;

// Request
use App\Http\Requests\Service\Tracker\TrackerPostRequest;

class TrackerController extends Controller
{
    /**
     * @SWG\Post(
     *   path="/tracker",
     *   summary="tracker",
     *   operationId="tracker",
     *   tags={"/Tracker"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Sign in into web site",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/tracker")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    public function create(TrackerPostRequest $request)
    {
        $this->dispatch(new TrackerJob($request->all()));
        return response()->success();
    }
}
