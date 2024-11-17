<?php

namespace App\Http\Controllers\AdminPanel\Chapter;

use App\Helpers\ResponseHelper;
use App\Services\ChapterService;
use App\Http\Controllers\BaseResourceController;
use App\Http\Requests\AdminPanel\Chapter\CreateChapterRequest;
use App\Http\Requests\AdminPanel\Chapter\UpdateChapterRequest;
use App\Http\Requests\AdminPanel\Chapter\ReorderChapterRequest;
use App\Services\LessonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChapterController extends BaseResourceController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected ChapterService $chapterService, protected LessonService $lessonService)
    {
        parent::__construct($chapterService->repository);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\AdminPanel\Chapter\CreateChapterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateChapterRequest $request)
    {
        $results = $this->chapterService->saveChapter($request->validated());

        return ResponseHelper::success(trans('messages.successfully_created'), $results ?? [], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\AdminPanel\Chapter\UpdateChapterRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateChapterRequest $request, int $id)
    {
        return parent::patch($request, $id);
    }

    /**
     * Reorder chapters.
     *
     * @param  \App\Http\Requests\AdminPanel\Chapter\ReorderChapterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reorder(ReorderChapterRequest $request)
    {
        $results = DB::transaction(function () use ($request) {
            return $this->chapterService->reorder($request->validated());
        });

        return ResponseHelper::success(trans('messages.successfully_updated'), $results ?? [], 200);
    }

    /**
     * Get lessons of a chapter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $chapterId
     * @return \Illuminate\Http\JsonResponse
     */
    public function lessons(Request $request, int $chapterId)
    {
        $result = $request->has('paginate') && ($request->paginate === 'false' || $request->paginate === '0')
            ? $this->lessonService->listByChapter($chapterId, $request->all())
            : $this->lessonService->paginatedListByChapter($chapterId, $request->all());

        return ResponseHelper::data($result);
    }
}
