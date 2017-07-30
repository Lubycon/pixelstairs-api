<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @SWG\Swagger(
 *   basePath="/v1",
 *   @SWG\Info(
 *     title="Pixel Stairs API",
 *     version="1.2.0",
 *     @SWG\Contact(
 *       name="daniel kim",
 *       email="bboyzepot@gmail.com",
 *     ),
 *   ),
 * )
 */

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


}
