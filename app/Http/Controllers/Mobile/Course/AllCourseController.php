<?php

namespace App\Http\Controllers\Mobile\Course;

use App\Enums\CourseStatus;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Services\CourseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AllCourseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CourseService $courseService) {}

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $params['status'] = CourseStatus::Published->value;

        $result = $request->has('paginate') && ($request->paginate === 'false' || $request->paginate === '0')
            ? $this->courseService->list($params)
            : $this->courseService->paginatedList($params);

        return ResponseHelper::data($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $result = $this->courseService->findPublished($id);

        return ResponseHelper::data($result);
    }

    /**
     * Enroll a course.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function enrollCourse(int $id)
    {
        $results = $this->courseService->enrollCourse(Auth::id(), $id);

        return ResponseHelper::data(['course' => $results]);
    }
}
