<?php

namespace App\Exceptions;

use Log;
use Exception;

use App\Exceptions\UserNotFound;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Validation\ValidationException;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\LengthRequiredHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

use Carbon\Carbon;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    private $customCode;
    private $devMsg;

    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    public function report(Exception $e)
    {
        return parent::report($e);
    }

    public function render($request, Exception $e)
    {
        if(env('APP_DEBUG')){ //for develop
            return parent::render($request , $e);
        }else{ //for provision
            $getCustom = $this->getCustomCode($e);
            if(!is_null($getCustom)){
                $customCode = is_object($getCustom) ? $getCustom->customCode : $getCustom;
                $devMsg = is_object($getCustom) ? $getCustom->devMsg : null;
                return response()->error([
                    "code" => $customCode,
                    "devMsg" => $devMsg
                ]);
            }
            return $this->lastResponse($request, $e); //for provide, make 9999 error
        }
    }
    public function lastResponse($request, Exception $e)
    {
        $exception = (object)array(
            "httpStatusCode" => $this->getExceptionHTTPStatusCode($e),
            "msg" => Carbon::now()->toDateTimeString()." -> error occur time. plz send to daniel this time. & Error Msg -> ".$this->getJsonMessage($e),
        );
        $status = [
            'httpCode' => $exception->httpStatusCode,
            'code' => '9999',
            'devMsg' => $exception->msg
        ];
        return response()->error($status);
    }

    private function getCustomCode($e){
        switch ($e) {
            case $e instanceof CustomException:                    return json_decode($e->getMessage()); break;
            case $e instanceof BadRequestHttpException:            return '0040';  break;
            case $e instanceof UnauthorizedHttpException:          return '0041';  break;
            case $e instanceof AccessDeniedHttpException:          return '0043';  break;
            case $e instanceof NotFoundHttpException:              return '0044';  break;
            case $e instanceof ConflictHttpException:              return '0046';  break;
            case $e instanceof LengthRequiredHttpException:        return '0047';  break;
            case $e instanceof UnsupportedMediaTypeHttpException:  return '0050';  break;
            case $e instanceof UnprocessableEntityHttpException:   return '0051';  break;
            case $e instanceof MethodNotAllowedHttpException:      return '0052';  break;
            case $e instanceof TooManyRequestsHttpException:       return '0053';  break;
            case $e instanceof ModelNotFoundException:             return '0054';  break;
            case $e instanceof FatalErrorException:                return '0070';  break;
            case $e instanceof ServiceUnavailableHttpException:    return '0074';  break;
            default: return null; break;
        }
    }
    protected function getJsonMessage($e){
        return method_exists($e, 'getMessage') ? $e->getMessage() : 500;
    }
    protected function getExceptionHTTPStatusCode($e){
        return method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
    }
 }
