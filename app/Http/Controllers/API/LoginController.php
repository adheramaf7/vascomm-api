<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use ResponseFormatter;

    function login(LoginRequest $request)
    {
        $payload = $request->validated();

        $user = User::where('email', $payload['email'])->first();

        if (is_null($user)) {
            return $this->formatResponse(code: 422, message: __('auth.failed'));
        }

        if (!Hash::check($payload['password'], $user->password)) {
            return $this->formatResponse(code: 422, message: __('auth.failed'));
        }

        $token = $user->createToken('token')->accessToken;

        $data = [
            'token' => $token,
            'user'  => UserResource::make($user)->toArray($request),
        ];

        return $this->formatResponse(message: 'Login Success', data: $data);
    }

    function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return $this->formatResponse(message: 'Logout Success');
    }
}
