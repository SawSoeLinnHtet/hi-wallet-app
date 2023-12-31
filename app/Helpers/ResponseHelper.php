<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function success($message, $data)
    {
        return response()->json(
            [
                'result' => 1,
                'message' => $message,
                'data' => $data
            ]
        );
    }

    public static function fail($message, $data)
    {
        return response()->json(
            [
                'result' => 0,
                'message' => $message,
                'data' => $data
            ]
        );
    }
}
