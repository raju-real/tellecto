<?php

namespace App\Http\Controllers\RolePermission;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolePermission\PermissionRequest;
use App\Models\RolePermission\Permission;
use App\Services\DeleteService;
use App\Services\ResponseService;
use App\Services\RolePermission\PermissionService;
use Illuminate\Http\JsonResponse;

;

class PermissionController extends Controller
{


    protected PermissionService $permissionService;

    /**
     * Display a listing of the resource.
     */

    public function __construct(PermissionService $permission)
    {
        $this->permissionService = $permission;
    }

    public function index()
    {

        try {
            return $this->permissionService->fetchList();

        } catch (\Exception $exception) {
            return failedResponse($exception->getMessage());
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionRequest $request)
    {

        try {
            return $this->permissionService->storePermission($request);

        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $permission = Permission::selectedColumn()
            ->with('parent', 'permission_action.action')
            ->find($id);
        return ResponseService::successResponse($permission);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionRequest $request, Permission $permission)
    {

        try {
            return $this->permissionService->update($request, $permission);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission): JsonResponse
    {
        return DeleteService::deleteOrRestore($permission);
    }
}
