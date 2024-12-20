<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AgentRequest;
use App\Http\Requests\Admin\PasswordChangeRequest;
use App\Services\Admin\AgentService;

class AgentController extends Controller
{
    protected AgentService $agentService;

    public function __construct(AgentService $agentService)
    {
        $this->agentService = $agentService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return $this->agentService->fetchAllAgent();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function allAgent()
    {
        try {
            return $this->agentService->allAgent();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AgentRequest $request)
    {

        try {
            return $this->agentService->storeAgent($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return $this->agentService->agentById($id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AgentRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->agentService->updateAgent($request, $id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
//        try {
//            return $this->agentService->deleteBusiness($id);
//        } catch (\Exception $exception) {
//            return exceptionResponse($exception->getMessage());
//        }
    }

    public function changeAgentPassword(PasswordChangeRequest $request, $id)
    {
        try {
            return $this->agentService->changeAgentPassword($request, $id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }
}
