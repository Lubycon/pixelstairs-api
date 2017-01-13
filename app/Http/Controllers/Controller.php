<?php

/**
 * @SWG\Swagger(
 *     schemes={"http"},
 *     host="dev-api@mittycompany.com",
 *     basePath="/v1",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Walter Mitty Dev API Document",
 *         termsOfService="",
 *         @SWG\Contact(
 *             email="daniel@mittycompany.com"
 *         ),
 *         @SWG\License(
 *             name="Private License"
 *         )
 *     ),
 *     @SWG\ExternalDocumentation(
 *         description="Find out more about my website",
 *         url="http://www.mittycompany.com"
 *     )
 * )
 */

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Abort;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


}
