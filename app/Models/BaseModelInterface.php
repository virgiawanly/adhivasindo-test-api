<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

interface BaseModelInterface
{
    /**
     * The custom searchables query.
     *
     * @return array
     */
    public function getCustomSearchables(): array;

    /**
     * The custom sortables query.
     *
     * @return array
     */
    public function getCustomSortables(): array;

    /**
     * Scope a query to search for a query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $keyword
     * @param  array|string|null $searchableColumns
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, string $keyword, array|string|null $searchableColumns = null): Builder;

    /**
     * Scope a query to order by a column.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $sort
     * @param string $order
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfOrder(Builder $query, string $sort, string $order): Builder;

    /**
     * Scope a query to search for a query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  array $queryParams
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchColumns(Builder $query, array $queryParams): Builder;
}
