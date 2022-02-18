<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseJson
{
    /**
     * @param mixed $data
     * @param int   $code
     * @param int   $options
     *
     * @return JsonResponse
     */
    public static function responseJson(mixed $data = [], int $code = Response::HTTP_OK, int $options = 0): JsonResponse
    {
        $response = [
            'data' => $data,
            'meta' => [
                'code' => $code
            ]
        ];

        return new JsonResponse(data: $response, status: $code, options: $options);
    }

    /**
     * @param              $type
     * @param string       $message
     * @param string|array $description
     * @param int          $code
     *
     * @return JsonResponse
     */
    public static function responseJsonError(
        $type, string $message,
        string|array $description = '',
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR
    ): JsonResponse
    {
        $response = [
            'error' => [
                'error_type' => $type,
                'error_message' => $message,
                'error_description' => $description,
            ],
            'meta' => [
                'code' => $code,
            ],
        ];

        return new JsonResponse($response, $code);
    }
}
