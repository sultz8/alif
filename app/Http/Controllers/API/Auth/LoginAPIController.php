<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\APIController;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class LoginAPIController extends APIController
{
    use ThrottlesLogins;

    /**
     * Войти в приложение
     *
     * @param  LoginRequest  $request
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login (LoginRequest $request): JsonResponse
    {
        if ($this->attempt($request)) {
            $this->clearLoginAttempts($request);

            $token = $request->user()->createToken('auth_token');
            $token->accessToken->save();

            event(new Login(auth()->getDefaultDriver(), auth()->guard()->user(), false));

            return $this->sendSuccess(__('auth.successfully_authenticated'), [
                'access_token' => $token->plainTextToken,
                'token_type' => 'Bearer',
                'expiration_at' => !is_null(config('sanctum.expiration')) ? $token->accessToken->created_at->addMinutes(config('sanctum.expiration'))->timestamp : null,
            ]);
        }

        throw ValidationException::withMessages([
            'email' => [__('auth.failed')],
        ]);
    }

    /**
     * Выйти из приложения
     *
     * @param  Request  $request
     *
     * @return JsonResponse
     */
    public function logout (Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        event(new Logout(auth()->getDefaultDriver(), auth()->guard()->user()));

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  Request  $request
     *
     * @return bool
     */
    protected function attempt (Request $request): bool
    {
        $user = User::firstWhere('email', $request->email);

        if (!$user instanceof User) {
            return false;
        }

        auth()->guard()->setUser($user);

        if (!app('hash')->check($request->password, $user->password)) {
            return false;
        }

        return true;
    }

    /**
     * Получить логин пользователя
     *
     * @return string
     */
    protected function username (): string
    {
        return 'email';
    }
}
