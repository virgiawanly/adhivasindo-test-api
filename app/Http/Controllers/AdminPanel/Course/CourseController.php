<?php

namespace App\Http\Controllers\AdminPanel\Course;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\BaseResourceController;
use App\Http\Requests\AdminPanel\Course\CreateCourseRequest;
use App\Http\Requests\AdminPanel\Course\UpdateCourseRequest;
use App\Services\CourseService;
use Illuminate\Support\Facades\DB;

class CourseController extends BaseResourceController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CourseService $courseService)
    {
        parent::__construct($courseService->repository);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\AdminPanel\Course\CreateCourseRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateCourseRequest $request)
    {
        $results = DB::transaction(function () use ($request) {
            return $this->courseService->saveCourse($request->validated());
        });

        return ResponseHelper::success(trans('messages.successfully_created'), $results ?? [], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\AdminPanel\Course\UpdateCourseRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCourseRequest $request, int $id)
    {
        $results = DB::transaction(function () use ($request, $id) {
            return $this->courseService->patchCourse($id, $request->validated());
        });

        return ResponseHelper::success(trans('messages.successfully_updated'), $results ?? [], 200);
    }
}
