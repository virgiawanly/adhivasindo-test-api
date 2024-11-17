<?php

namespace App\Http\Controllers\AdminPanel\Tool;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\BaseResourceController;
use App\Http\Requests\AdminPanel\Tool\CreateToolRequest;
use App\Http\Requests\AdminPanel\Tool\UpdateToolRequest;
use App\Services\ToolService;
use Illuminate\Support\Facades\DB;

class ToolController extends BaseResourceController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected ToolService $toolService)
    {
        parent::__construct($toolService->repository);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\AdminPanel\Tool\CreateToolRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateToolRequest $request)
    {
        $results = DB::transaction(function () use ($request) {
            return $this->toolService->saveTool($request->validated());
        });

        return ResponseHelper::success(trans('messages.successfully_created'), $results ?? [], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\AdminPanel\Tool\UpdateToolRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateToolRequest $request, int $id)
    {
        $results = DB::transaction(function () use ($request, $id) {
            return $this->toolService->patchTool($id, $request->validated());
        });

        return ResponseHelper::success(trans('messages.successfully_updated'), $results ?? [], 200);
    }
}
