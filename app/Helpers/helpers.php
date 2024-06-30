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
    function jsonResponse(string $message, mixed $data = null, int $code = null): JsonResponse
    {
        $response = [
            'message' => $message,
            'data' => $data,
            'code' => $code ?? 200, // Default to 200 if $code is null
        ];

        return response()->json($response, $code ?? 200); // Use 200 if $code is null
    }
}
