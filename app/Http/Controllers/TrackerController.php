<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Tracker\TrackerCreateRequest;

class TrackerController extends Controller
{
    public function create(TrackerCreateRequest $request)
    {
        return response()->success();
    }
}
