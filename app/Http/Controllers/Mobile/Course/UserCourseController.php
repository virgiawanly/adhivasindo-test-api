<?php

namespace App\Http\Controllers\Mobile\Course;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\Course\UpdateLessonProgressRequest;
use App\Services\CourseService;
use App\Services\LessonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCourseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CourseService $courseService, protected LessonService $lessonService) {}

    /**
     * Get user's courses.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userCourses(Request $request)
    {
        $results = $this->courseService->useCoursePaginatedList(Auth::id(), $request->all());

        return ResponseHelper::data(['courses' => $results]);
    }

    /**
     * Get user's courses.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewCourse(int $id)
    {
        $results = $this->courseService->viewUserCourse(Auth::id(), $id);

        return ResponseHelper::data(['course' => $results]);
    }

    /**
     * Mark lesson as completed or incomplete.
     *
     * @param  \App\Http\Requests\Mobile\Course\UpdateLessonProgressRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLessonProgress(UpdateLessonProgressRequest $request)
    {
        $this->lessonService->updateUserLessonProgress(Auth::id(), $request->lesson_id, $request->is_completed);

        return ResponseHelper::success(trans('messages.successfully_updated'));
    }
}
