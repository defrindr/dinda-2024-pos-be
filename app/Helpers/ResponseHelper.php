<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Throwable;

class ResponseHelper
{
  protected static function response(mixed $content, int $statusCode)
  {
    // help debuging
    if (config('app.env') !== 'production') {
      $content = array_merge($content, [
        'timestamps' => time(),
        'environment' => config('app.env'),
        'request' => request()->all(),
      ]);
    }

    return response()->json($content, $statusCode);
  }

  public static function validationError(MessageBag $errors)
  {
    $body = ['message' => $errors->first(), 'errors' => $errors, 'typeError' => 'validation-error'];
    return static::response($body, 422);
  }

  public static function error(Throwable $throwable)
  {
    $body = ['message' => $throwable->getMessage(), 'trace' => $throwable->getTrace()];
    return static::response($body, 500);
  }

  public static function unAuthorization()
  {
    $body = ['message' => 'UnAuthorization'];
    return static::response($body, 403);
  }
  public static function unAuthencation()
  {
    $body = ['message' => 'UnAuthenticated'];
    return static::response($body, 401);
  }

  public static function badRequest($message = '')
  {
    $body = ['message' => $message ?? 'Bad Request, Please check your input.'];
    return static::response($body, 400);
  }

  public static function success($body)
  {
    return static::response($body, 200);
  }

  public static function successWithData($data, $message = 'Success get data')
  {
    return static::response(['message' => $message, 'data' => $data], 200);
  }
}
