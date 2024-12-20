<?php

namespace App\Http\Controllers\RolePermission;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolePermission\ActionRequest;
use App\Models\RolePermission\Action;
use App\Services\DeleteService;
use App\Services\ResponseService;
use App\Services\RolePermission\ActionService;
use Illuminate\Http\JsonResponse;

class ActionController extends Controller
{

    protected ActionService $actionService;

    public function __construct(ActionService $actionService)
    {
        $this->actionService = $actionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return $this->actionService->fetchList();

        } catch (\Exception $exception) {
            return failedResponse($exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ActionRequest $request)
    {
        try {
            return $this->actionService->save($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Action $action): JsonResponse
    {
        try {
            return ResponseService::successResponse($action);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ActionRequest $request, Action $action)
    {
        try {
            return $this->actionService->update($request, $action);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Action $action): JsonResponse
    {
        return DeleteService::deleteOrRestore($action);
    }

    public function allActions(Action $action): JsonResponse
    {
        try {
            return $this->actionService->getAll();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }
}
