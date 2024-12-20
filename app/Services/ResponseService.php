<?php


namespace App\Services;

use Illuminate\Http\JsonResponse;

class ResponseService
{
    public static function successResponse($data): JsonResponse
    {
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => null,
            'errors' => null,
            'data' => $data
        ], 200);
    }

    public static function emptyResponse(): JsonResponse
    {
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Data not available',
            'errors' => null,
            'data' => []
        ], 200);
    }

    public static function errorResponse($exception, $status = 400): JsonResponse
    {
        return response()->json([
            'code' => $status,
            'status' => 'failed',
            'message' => 'Exception Occurred!',
            'error' => $exception->getMessage(),
            'data' => null
        ], $status);
    }

    public static function queryResultNotFoundResponse($exception): JsonResponse
    {
        return response()->json([
            'code' => 400,
            'status' => 'failed',
            'message' => 'No query results for request',
            'error' => $exception->getMessage(),
            'data' => null
        ], 400);
    }

    public static function emptyBodyResponse(): JsonResponse
    {
        return response()->json([
            'code' => 400,
            'status' => 'failed',
            'message' => null,
            'errors' => 'Empty Request Data',
            'data' => null,
        ], 400);
    }

    public static function invalidResponse($validator): JsonResponse
    {
        return response()->json([
            'code' => 422,
            'status' => 'failed',
            'message' => 'Validation Error',
            'errors' => $validator->errors(),
            'data' => null,
        ], 422);
    }

    public static function createSuccessResponse($data, $type = "data"): JsonResponse
    {
        return response()->json([
            'code' => 201,
            'status' => 'success',
            'message' => $type . ' created successfully.',
            'errors' => null,
            'data' => $data
        ], 201);
    }

    public static function updateSuccessResponse($data, $type = "data"): JsonResponse
    {
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => $type . ' updated successfully.',
            'errors' => null,
            'data' => $data
        ], 200);
    }

    public static function deleteSuccessResponse(): JsonResponse
    {
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'errors' => null,
            'message' => 'Deleted successfully',
            'data' => null
        ], 200);
    }

    public static function restoreSuccessResponse(): JsonResponse
    {
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'errors' => null,
            'message' => 'Restored successfully',
            'data' => null
        ], 200);
    }

    public static function notFoundResponse($id): JsonResponse
    {
        return response()->json([
            'code' => 400,
            'status' => 'failed',
            'message' => null,
            'error' => 'No Data found against id: ' . $id,
            'data' => null,
        ], 400);
    }

    public static function unAuthenticateAPIResponse($exception): JsonResponse
    {
        return response()->json([
            'code' => 403,
            'status' => 'failed',
            'message' => null,
            'error' => 'User is not authorized',
            'data' => null,
        ], 403);
    }

    public static function notFoundURLResponse($exception): JsonResponse
    {
        return response()->json([
            'code' => 404,
            'status' => 'failed',
            'message' => null,
            'error' => 'URL Not Found',
            'data' => null,
        ], 404);
    }

    public static function registrationSuccessResponse($type): JsonResponse
    {
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => $type . ' Registered Successfully',
            'error' => null,
            'data' => null,
        ], 200);
    }

    public static function globalResponse($status, $message, $code, $data = null): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'status' => $status,
            'message' => $message,
            'data' => $data ?? null,
            'error' => null,
        ], $code);
    }

    public static function paginatedResponse($data): JsonResponse
    {
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => null,
            'data' => $data,
            'error' => null,
        ], 200);
    }
}
