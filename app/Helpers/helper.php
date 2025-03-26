<?php

use App\Models\User;

if (! function_exists('authUser')) {
    function authUser($guard = null): null | User
    {
        return auth($guard)->user();
    }
}

if (! function_exists('jsonResponse')) {
    function jsonResponse(array | object $data = [], ?string $message = '', $status = 200): \Illuminate\Http\JsonResponse
    {
        $response = [];

        if ($data)  $response['data'] = $data;

        if ($message) $response['message'] = $message;

        return response()->json($response, $status);
    }
}

