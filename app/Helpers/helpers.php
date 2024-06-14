<?php

use Illuminate\Http\JsonResponse;

if (!function_exists('jsonResponse')) {
    /**
     * Return a JSON response with a consistent format.
     *
     * @param string $message
     * @param mixed|null $data
     * @param int $code
     * @param int $statusCode
     * @return JsonResponse
     */
    function jsonResponse(string $message, mixed $data = null, int $code = 200, int $statusCode = 200): JsonResponse
    {
        $response = [
            'message' => $message,
            'data' => $data,
            'code' => $code,
        ];

        return response()->json($response, $statusCode);
    }
}
