<?php

namespace App\Repositories;

use App\Models\Tool;
use App\Repositories\Interfaces\ToolRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class ToolRepository extends BaseResourceRepository implements ToolRepositoryInterface
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Tool();
    }

    /**
     * Find data by slug.
     *
     * @param  string $slug
     * @param  int|null $excludeId
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findBySlug(string $slug, int|null $excludeId = null): ?Model
    {
        return $this->model
            ->where('slug', $slug)
            ->when(!empty($excludeId), function ($query) use ($excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->first();
    }
}
