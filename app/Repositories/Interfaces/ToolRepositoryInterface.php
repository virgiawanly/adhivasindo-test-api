<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface ToolRepositoryInterface extends BaseResourceRepositoryInterface
{
    /**
     * Find data by slug.
     *
     * @param  string $slug
     * @param  int|null $excludeId
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findBySlug(string $slug, int|null $excludeId = null): ?Model;
}
