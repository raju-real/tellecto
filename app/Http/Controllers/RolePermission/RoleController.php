<?php

namespace App\Http\Controllers\RolePermission;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolePermission\RoleRequest;
use App\Models\RolePermission\Action;
use App\Models\RolePermission\Role;
use App\Services\DeleteService;
use App\Services\ResponseService;
use App\Services\RolePermission\RoleService;
use Illuminate\Http\JsonResponse;


class RoleController extends Controller
{

    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return $this->roleService->fetchList();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        try {
            return $this->roleService->storeRole($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        try {
            return ResponseService::successResponse($role);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $role): JsonResponse
    {
        try {
            return $this->roleService->updateRole($request, $role);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Action $action)
    {

        try {
            return DeleteService::deleteOrRestore($action);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function allRoles()
    {
        try {
            return $this->roleService->getAll();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }
}
