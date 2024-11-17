<?php

namespace App\Services;

use App\Repositories\Interfaces\ToolRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ToolService extends BaseResourceService
{
    /**
     * Create a new service instance.
     *
     * @param  \App\Repositories\Interfaces\ToolRepositoryInterface  $repository
     * @return void
     */
    public function __construct(ToolRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get repository instance.
     *
     * @return \App\Repositories\Interfaces\ToolRepositoryInterface
     */
    public function repository(): ToolRepositoryInterface
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
    public function saveTool(array $payload): Model
    {
        $slug = $this->generateSlug($payload['name']);

        // Make sure the slug is unique
        while (!empty($this->repository()->findBySlug($slug))) {
            $slug = $this->generateSlug($payload['name']);
        }

        // Set slug payload
        $payload['slug'] = $slug;

        // Save image
        if (!empty($payload['image']) && $payload['image'] instanceof UploadedFile) {
            $payload['image'] = $payload['image']->store('tools');
        } else {
            $payload['image'] = null;
        }

        // Create tool
        $tool = $this->repository->save($payload);

        return $tool;
    }

    /**
     * Update a resource.
     *
     * @param int $id
     * @param array $payload
     */
    public function patchTool(int $id, array $payload): Model
    {
        $tool = $this->repository->find($id);

        // Check if the slug should be regenerated
        if (!empty($payload['regenerate_slug']) && $payload['name'] !== $tool->name) {
            $slug = $this->generateSlug($payload['name']);

            // Make sure the slug is unique
            while (!empty($this->repository()->findBySlug($slug))) {
                $slug = $this->generateSlug($payload['name']);
            }

            // Set slug payload
            $payload['slug'] = $slug;
        }

        // Update image (if uploaded)
        if (!empty($payload['image']) && $payload['image'] instanceof UploadedFile) {
            $payload['image'] = $payload['image']->store('tools');
        } else {
            $payload['image'] = $tool->image;
        }

        // Update tool
        $tool->update($payload);

        return $tool;
    }
}
