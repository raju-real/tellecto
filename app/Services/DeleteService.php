<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class DeleteService
{
    public static function deleteOrRestore($data): JsonResponse
    {
        $previous_data = $data->status;
        $data->update(['status' => $previous_data == 0]);
        if ($previous_data == 1) {
            return ResponseService::deleteSuccessResponse();
        }
        return ResponseService::restoreSuccessResponse();
    }

    public static function softDelete($data): JsonResponse
    {
        $data->delete();
        return ResponseService::deleteSuccessResponse();
    }
}
