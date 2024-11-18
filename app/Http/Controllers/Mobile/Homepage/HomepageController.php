<?php

namespace App\Http\Controllers\Mobile\Homepage;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Services\CourseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomepageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CourseService $courseService) {}

    /**
     * Get homepage data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHomepageData(Request $request)
    {
        $popularCourses = $this->courseService->getPopularCourses($request->popular_courses_size ?? $request->size ?? 10);
        $recentlyAddedCourses = $this->courseService->getRecentlyAddedCourses($request->recently_added_courses_size ?? $request->size ?? 10);
        $userRecentlyAccessedCourses = $this->courseService->getUserRecentlyAccessedCourses(Auth::id(), $request->user_recently_accessed_courses_size ?? $request->size ?? 10);

        return ResponseHelper::data([
            'popular_courses' => $popularCourses,
            'recently_added_courses' => $recentlyAddedCourses,
            'user_recently_accessed_courses' => $userRecentlyAccessedCourses
        ]);
    }
}
