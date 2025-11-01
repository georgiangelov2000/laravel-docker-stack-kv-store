<?php
declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ApiResponse
{
    public static function success(
        string $message,
        mixed $data = null,
        int $status = Response::HTTP_OK
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public static function error(
        string $message,
        mixed $errors = null,
        int $status = Response::HTTP_BAD_REQUEST
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $status);
    }

    public static function created(string $message, mixed $data = null): JsonResponse
    {
        return self::success($message, $data, Response::HTTP_CREATED);
    }

    public static function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return self::error($message, null, Response::HTTP_NOT_FOUND);
    }

    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return self::error($message, null, Response::HTTP_UNAUTHORIZED);
    }

    public static function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return self::error($message, null, Response::HTTP_FORBIDDEN);
    }

    public static function validationError(string $message, mixed $errors): JsonResponse
    {
        return self::error($message, $errors, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}