<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\Business\AgentRequest;
use App\Services\Business\AgentService;

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

    public function agentList() {
        try {
            return $this->agentService->agentList();
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
    public function update(AgentRequest $request, int $id)
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
    public function destroy(string $id)
    {
        //
    }
}
