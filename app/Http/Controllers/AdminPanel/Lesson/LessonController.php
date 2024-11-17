<?php

namespace App\Http\Controllers\AdminPanel\Lesson;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\BaseResourceController;
use App\Http\Requests\AdminPanel\Lesson\CreateLessonRequest;
use App\Http\Requests\AdminPanel\Lesson\ReorderLessonRequest;
use App\Http\Requests\AdminPanel\Lesson\UpdateLessonRequest;
use App\Services\LessonService;
use Illuminate\Support\Facades\DB;

class LessonController extends BaseResourceController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected LessonService $lessonService)
    {
        parent::__construct($lessonService->repository);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\AdminPanel\Lesson\CreateLessonRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateLessonRequest $request)
    {
        $results = $this->lessonService->saveLesson($request->validated());

        return ResponseHelper::success(trans('messages.successfully_created'), $results ?? [], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\AdminPanel\Lesson\UpdateLessonRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateLessonRequest $request, int $id)
    {
        return parent::patch($request, $id);
    }

    /**
     * Reorder lessons.
     *
     * @param  \App\Http\Requests\AdminPanel\Lesson\ReorderLessonRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reorder(ReorderLessonRequest $request)
    {
        $results = DB::transaction(function () use ($request) {
            return $this->lessonService->reorder($request->validated());
        });

        return ResponseHelper::success(trans('messages.successfully_updated'), $results ?? [], 200);
    }
}
