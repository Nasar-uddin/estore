<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if($request->expectsJson()){
            if($exception instanceof NotFoundHttpException){
                return response([
                    "msg"=>"Page not found"
                ],Response::HTTP_NOT_FOUND);
            }
            else if($exception instanceof QueryException){
                return response([
                    "msg"=>"Data is not correct"
                ],Response::HTTP_FORBIDDEN);
            }else{
                return response([
                    "msg"=>"Somthing went wrong"
                ],Response::HTTP_NOT_FOUND);
            }
        }
        return parent::render($request, $exception);
    }
}
