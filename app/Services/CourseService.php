<?php

namespace App\Services;

use App\Enums\CourseStatus;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CourseService extends BaseResourceService
{
    /**
     * Create a new service instance.
     *
     * @param  \App\Repositories\Interfaces\CourseRepositoryInterface  $repository
     * @return void
     */
    public function __construct(CourseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get repository instance.
     *
     * @return \App\Repositories\Interfaces\CourseRepositoryInterface
     */
    public function repository(): CourseRepositoryInterface
    {
        return $this->repository;
    }

    /**
     * Generate slug.
     *
     * @param  string $title
     * @return string
     */
    protected function generateSlug(string $title)
    {
        return Str::slug($title, '-') . '-' . time();
    }

    /**
     * Create a new resource.
     *
     * @param array $payload
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function saveCourse(array $payload): Model
    {
        $slug = $this->generateSlug($payload['name']);

        // Make sure the slug is unique
        while (!empty($this->repository()->findBySlug($slug))) {
            $slug = $this->generateSlug($payload['name']);
        }

        // Set slug payload
        $payload['slug'] = $slug;

        // Set status
        $payload['status'] = !empty($payload['status'])
            ? $payload['status']
            : CourseStatus::Draft->value;

        // Save image
        if (!empty($payload['image']) && $payload['image'] instanceof UploadedFile) {
            $payload['image'] = $payload['image']->store('courses');
        }

        // Create course
        $course = $this->repository->save($payload);

        // Create competencies
        if (!empty($payload['competencies']) && is_array($payload['competencies'])) {
            foreach ($payload['competencies'] as $competency) {
                $course->competencies()->create([
                    'name' => $competency['name'],
                ]);
            }
        }

        // Create tools relationship
        if (!empty($payload['tool_ids']) && is_array($payload['tool_ids'])) {
            foreach ($payload['tool_ids'] as $tool) {
                $course->tools()->attach($tool);
            }
        }

        return $course;
    }

    /**
     * Update a resource.
     *
     * @param int $id
     * @param array $payload
     */
    public function patchCourse(int $id, array $payload): Model
    {
        $course = $this->repository->find($id);

        // Check if the slug should be regenerated
        if (!empty($payload['regenerate_slug']) && $payload['name'] !== $course->name) {
            $slug = $this->generateSlug($payload['name']);

            // Make sure the slug is unique
            while (!empty($this->repository()->findBySlug($slug, $course->id))) {
                $slug = $this->generateSlug($payload['name']);
            }

            // Set slug payload
            $payload['slug'] = $slug;
        }

        // Update image (if uploaded)
        if (!empty($payload['image']) && $payload['image'] instanceof UploadedFile) {
            $payload['image'] = $payload['image']->store('courses');
        }

        // Update course
        $course->update($payload);

        // Set status
        $payload['status'] = !empty($payload['status'])
            ? $payload['status']
            : CourseStatus::Draft->value;

        // Sync competencies
        $competencyIds = [];
        if (!empty($payload['competencies']) && is_array($payload['competencies'])) {
            foreach ($payload['competencies'] as $competency) {
                if (!empty($competency['id'])) {
                    $findCompetency = $course->competencies()->find($competency['id']);

                    if (!empty($findCompetency)) {
                        $findCompetency->update([
                            'name' => $competency['name'],
                        ]);
                    }

                    $competencyIds[] = $competency['id'];
                } else {
                    $competency = $course->competencies()->create([
                        'name' => $competency['name'],
                    ]);

                    $competencyIds[] = $competency->id;
                }
            }
        }

        // Remove unused competencies
        $course->competencies()->whereNotIn('id', $competencyIds)->delete();

        // Sync tools relationship
        if (!empty($payload['tool_ids']) && is_array($payload['tool_ids'])) {
            foreach ($payload['tool_ids'] as $tool) {
                $course->tools()->sync($tool);
            }
        }

        return $course;
    }

    /**
     * Delete a course and its chapters.
     *
     * @param int $id
     * @return bool
     */
    public function deleteCourse(int $id): bool
    {
        $course = $this->repository->find($id);

        $course->chapters()->delete();

        return $course->delete();
    }
}
