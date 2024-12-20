<?php

use Illuminate\Http\JsonResponse;

if (!function_exists('exceptionResponse')) {
    function exceptionResponse(string $message = 'Something is wrong!'): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'code' => 400,
            'message' => $message
        ]);
    }
}

if (!function_exists('permissionDenied')) {
    function permissionDenied(string $message = 'You have no permission to access this activity!'): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'code' => 403,
            'message' => $message
        ]);
    }
}

if (!function_exists('failedResponse')) {
    function failedResponse(string $message = 'Operation failed. Something is wrong!'): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'code' => 400,
            'message' => $message
        ]);
    }
}

if (!function_exists('guardFailed')) {
    function guardFailed(string $message = 'Your are not eligible for this activity!'): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'code' => 400,
            'message' => $message
        ]);
    }
}

if (!function_exists('successResponse')) {
    function successResponse(string $status = null, int $code = null, $data = [], string $message = 'Information has been saved successfully!'): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);
    }
}

if (!function_exists('successMessageResponse')) {
    function successMessageResponse(string $status = null, int $code = null, string $message = 'Information has been saved successfully!'): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'code' => $code,
            'message' => $message
        ]);
    }
}

if (!function_exists('updateMessageResponse')) {
    function updateMessageResponse(string $status = null, int $code = null, string $message = 'Information has been updated successfully!'): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'code' => $code,
            'message' => $message
        ]);
    }
}

if (!function_exists('getAllResponse')) {
    function getAllResponse(string $status = null, int $code = null, $data = []): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'code' => $code,
            'data' => $data
        ]);
    }
}

if (!function_exists('updateResponse')) {
    function updateResponse(string $status = null, int $code = null, $data = [], string $message = 'Information has been updated successfully!'): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);
    }
}

if (!function_exists('showResponse')) {
    function showResponse(string $status = null, int $code = null, $data = []): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'code' => $code,
            'data' => $data
        ]);
    }
}

if (!function_exists('deleteResponse')) {
    function deleteResponse(string $status = null, int $code = null, string $message = 'Information has been deleted successfully!'): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'code' => $code,
            'message' => $message
        ]);
    }
}

if (!function_exists('notAvailableResponse')) {
    function notAvailableResponse(string $status = null, int $code = null, string $message = 'Not Available!'): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'code' => 200,
            'message' => $message
        ]);
    }
}

if (!function_exists('apiResponse')) {
    function apiResponse(string $status = null, int $code = null, $data = []): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'code' => $code,
            'data' => $data
        ]);
    }
}

if (!function_exists('paginationResponse')) {
    function paginationResponse($status = 'success', $code = null, $data = [], $showPerPage = 50): JsonResponse
    {
        if ($showPerPage == 'All') {
            $data = $data->paginate($data->count());
        } elseif ($showPerPage < -1 || $showPerPage == 0) {
            $size = 10;
            $data = $data->paginate($size);
        } else {
            $size = (int)$showPerPage;
            $data = $data->paginate($size);
        }

        return response()->json([
            'status' => $status,
            'code' => $code,
            'data' => $data
        ]);
    }
}

if (!function_exists('brandProductResponse')) {
    function brandProductResponse($status = 'success', $brand = null, $products = [], $showPerPage = 50): JsonResponse
    {
        if ($showPerPage == 'All') {
            $products = $products->paginate($products->count());
        } elseif ($showPerPage < -1 || $showPerPage == 0) {
            $size = 10;
            $products = $products->paginate($size);
        } else {
            $size = (int)$showPerPage;
            $products = $products->paginate($size);
        }

        return response()->json([
            'status' => $status,
            'brand' => $brand,
            'products' => $products
        ]);
    }
}



