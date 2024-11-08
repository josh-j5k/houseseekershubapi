<?php

namespace App\Traits;

use Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;



/**
 * @param string $type
 * @param string $message
 * @param array|string|null|AnonymousResourceCollection|\Illuminate\Support\Collection $data
 * @param int $statusCode
 * 
 * @return JsonResponse
 */

trait ResponseTrait
{
    public static function response(string $type, string $message, $data = null, int $statusCode = 200): JsonResponse
    {
        if (is_null($data)) {

            return response()->json([
                'status' => $type,
                'message' => $message
            ], $statusCode);
        }

        return response()->json([
            'status' => $type,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
}