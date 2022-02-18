<?php

namespace App\Exceptions;

use App\Helpers\ResponseJson;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * @param \Illuminate\Http\Request $request
     * @param Throwable                $e
     *
     * @throws Throwable
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        return $this->handleApiException($request, $e);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Throwable                $e
     *
     * @throws Throwable
     *
     * @return JsonResponse
     */
    private function handleApiException(Request $request, Throwable $e): JsonResponse
    {
        return match(true) {
            $e instanceof AuthorizationException => ResponseJson::responseJsonError(
                type: 'AuthorizationException', message: $e->getMessage(), code: 403
            ),
            $e instanceof AuthenticationException => ResponseJson::responseJsonError(
                type: 'AuthenticationException', message: $e->getMessage(), code: 401
            ),
            $e instanceof ValidationException => ResponseJson::responseJsonError(
                'ValidationException', $e->getMessage(), $e->errors(), 422
            ),
            $e instanceof ModelNotFoundException => ResponseJson::responseJsonError(
                type: 'ModelNotFoundException', message: $e->getMessage(), code: 404
            ),
            $e instanceof \PDOException => ResponseJson::responseJsonError(
                class_basename($e), 'Bad Request', 'Error executing SQL query', 400
            ),
            $e instanceof BadRequestHttpException => ResponseJson::responseJsonError(
                type: class_basename($e), message: $e->getMessage(), code: 400
            ),
            $e->getCode() === 500 => ResponseJson::responseJsonError(
                'InternalServerError',
                $e->getMessage(),
                'Unknown error occurred. Try to refresh the page and repeat actions.'
            ),
            $e instanceof \Exception => ResponseJson::responseJsonError(
                class_basename($e), $e->getMessage(), 'Server Error'
            ),
            default => parent::render($request, $e),
        };
    }
}
