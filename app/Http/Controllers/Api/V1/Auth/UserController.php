<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\Api\V1\{StoreUserRequest, UpdateUserRequest};

final class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());

        return jsonResponse(
            data: [
                'user' => $user,
                'token' => [
                    'type' => 'bearer',
                    'value' => JWTAuth::fromUser($user),
                    'expires_in' => JWTAuth::factory()->getTTL() * 60,
                ]
            ],
            message: 'User created successfully',
            status: 201
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        return jsonResponse(message: 'User updated successfully');
    }
}
