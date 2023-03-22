<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\APIController;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class RegisterAPIController extends APIController
{
    /**
     * Зарегистрировать пользователя.
     *
     * @param RegisterRequest $request
     *
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $user = new User(Arr::except($validatedData, ['password']));
        $user->password = Hash::make(Arr::get($validatedData, 'password'));
        $user->save();

        return $this->sendSuccess(__('rest.user_registered_success'), $user);
    }
}
