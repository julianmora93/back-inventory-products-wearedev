<?php

namespace App\Http\Responses;

class ApiResponse
{
    public static function success($data, $message = null)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ]);
    }

    public static function error($message, $statusCode = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $statusCode);
    }
}
