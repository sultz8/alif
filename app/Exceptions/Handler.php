<?php

namespace App\Exceptions;

use App\Library\Traits\Responsible;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class Handler extends ExceptionHandler
{
    use Responsible;

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
     * Convert an authentication exception into a response.
     *
     * @param  Request  $request
     * @param  AuthenticationException  $exception
     *
     * @return JsonResponse|RedirectResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson() ? $this->sendError(__('auth.unauthenticated'), ResponseAlias::HTTP_UNAUTHORIZED) : redirect()->guest(url('/login'));
    }

    /**
     * Prepare a JSON response for the given exception.
     *
     * @param  Request  $request
     * @param  Throwable  $e
     * @return JsonResponse
     */
    protected function prepareJsonResponse($request, Throwable $e)
    {
        return $this->sendError(
            __('rest.internal_server_error'),
            $this->isHttpException($e) ? $e->getStatusCode() : ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
            config('app.debug') ? $this->convertExceptionToArray($e) : null,
            $this->isHttpException($e) ? $e->getHeaders() : []
        );
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof ModelNotFoundException && $request->wantsJson())
            return $this->sendError(__('rest.entry_not_found'), ResponseAlias::HTTP_NOT_FOUND);

        if ($e instanceof ValidationException && $request->wantsJson())
            return $this->sendError(__('rest.data_is_invalid'), ResponseAlias::HTTP_UNPROCESSABLE_ENTITY, $e->validator->errors()->messages());

        if ($e instanceof AuthorizationException && $request->wantsJson())
            return $this->sendError(__('rest.access_denied'), ResponseAlias::HTTP_FORBIDDEN);

        return parent::render($request, $e);
    }
}
