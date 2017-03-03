<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Classes\Ems;
use Abort;

class EmsController extends Controller
{
    public $ems;

    public function __construct(){
        $this->ems = new Ems();
    }
    public function get(Request $request)
    {
        $query = $request->query();
        if( !isset($query['countryCode']) ) Abort::Error('0051','countryCode is required');
        if( !isset($query['totalWeight']) ) Abort::Error('0051','totalWeight is required');

        $result = $this->ems->request($query['countryCode'],$query['totalWeight']);
        return response()->success( $result );
    }
}
