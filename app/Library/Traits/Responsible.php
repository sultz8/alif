<?php

namespace App\Library\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait Responsible
{
    /**
     * JSON ответ для успешных действий
     *
     * @param  string  $message
     * @param  mixed|null  $data
     * @param  int  $status
     * @param  array  $headers
     *
     * @return JsonResponse
     */
    public function sendSuccess(string $message, mixed $data = null, int $status = Response::HTTP_OK, array $headers = []): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'success' => true
        ], $status, $headers);
    }

    /**
     * JSON ответ для ошибок
     *
     * @param  string  $message
     * @param  int  $status
     * @param  mixed|null  $data
     * @param  array  $headers
     *
     * @return JsonResponse
     */
    public function sendError(string $message, int $status, mixed $data = null, array $headers = []): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'success' => false,
        ], $status, $headers);
    }
}
