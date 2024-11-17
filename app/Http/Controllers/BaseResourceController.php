<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Repositories\BaseResourceRepository;
use App\Services\BaseResourceService;
use Illuminate\Http\Request;

abstract class BaseResourceController extends Controller
{
    /**
     * The base resource service.
     *
     * @var \App\Services\BaseResourceService
     */
    protected BaseResourceService $service;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Repositories\BaseResourceRepository  $repository
     * @return void
     */
    public function __construct(BaseResourceRepository $repository)
    {
        $this->service = new BaseResourceService($repository);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $relations = [];

        if (!empty($request->relations)) {
            if (is_array($request->relations)) {
                $relations = $request->relations;
            } else if (is_string($request->relations)) {
                $relations = explode(',', $request->relations);
            }
        }

        $result = $request->has('paginate') && ($request->paginate === 'false' || $request->paginate === '0')
            ? $this->service->list($request->all(), $relations)
            : $this->service->paginatedList($request->all(), $relations);

        return ResponseHelper::data($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        $result = $this->service->save($request->all());

        return ResponseHelper::success(trans('messages.successfully_created'), $result, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $id)
    {
        $relations = [];

        if (!empty($request->relations)) {
            if (is_array($request->relations)) {
                $relations = $request->relations;
            } else if (is_string($request->relations)) {
                $relations = explode(',', $request->relations);
            }
        }

        $result = $this->service->find($id, $relations);

        return ResponseHelper::data($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function patch(Request $request, int $id)
    {
        $result = $this->service->patch($id, $request->all());

        return ResponseHelper::success(trans('messages.successfully_updated'), $result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $this->service->delete($id);

        return ResponseHelper::success(trans('messages.successfully_deleted'));
    }
}
