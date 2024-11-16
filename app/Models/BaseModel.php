<?php

namespace App\Models;

use App\Traits\DefaultActivityLogOptions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model implements BaseModelInterface
{
    use DefaultActivityLogOptions;

    /**
     * The attributes that are searchable in the query.
     *
     * @var array<int, string>
     */
    protected $searchables = [];

    /**
     * The custom searchables query.
     *
     * @return array
     */
    public function getCustomSearchables(): array
    {
        return [];
    }

    /**
     * The columns that are searchable in the query.
     *
     * @var array<string, string>
     */
    protected $searchableColumns = [];

    /**
     * The columns that are sortable in the query.
     *
     * @var array<int, string>
     */
    protected $sortableColumns = [];

    /**
     * The custom sortables query.
     *
     * @return array
     */
    public function getCustomSortables(): array
    {
        return [];
    }

    /**
     * Scope a query to search for a query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $keyword
     * @param  array|string|null $searchableColumns
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, string $keyword, array|string|null $searchableColumns = null): Builder
    {
        if (!$keyword) {
            return $query;
        }

        // Default searchable columns
        $searchables = $this->searchables;

        // Set custom searchable columns
        if (!empty($searchableColumns)) {
            // Check if the query param is a string
            if (is_string($searchableColumns)) {
                $searchableColumns = explode(',', $searchableColumns);
            }

            // Check included columns
            $searchables = collect($searchables)->filter(function ($searchable) use ($searchableColumns) {
                return in_array($searchable, $searchableColumns);
            });
        }

        return $query->where(function ($subQuery) use ($keyword, $searchables) {
            foreach ($searchables as $searchable) {
                $subQuery->orWhere($this->getTable() . '.' . $searchable, 'LIKE', "%{$keyword}%");
            }

            $subQuery->orWhere(function ($subQuery) use ($keyword) {
                foreach ($this->getCustomSearchables() as $callback) {
                    if (is_callable($callback)) {
                        $callback($subQuery, $keyword);
                    }
                }
            });
        });
    }

    /**
     * Scope a query to order by a column.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $sort
     * @param string $order
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfOrder(Builder $query, string $sort, string $order): Builder
    {
        if (empty($sort)) {
            return $query;
        }

        if (!$order) {
            $order = 'asc';
        }

        $customSortables = $this->getCustomSortables();

        if (!empty($customSortables[$sort]) && is_callable($customSortables[$sort])) {
            return tap($query, function ($query) use ($sort, $order, $customSortables) {
                $customSortables[$sort]($query, $order);
            });
        }

        if (in_array($sort, $this->sortableColumns)) {
            return $query->orderBy($this->getTable() . '.' . $sort, $order);
        }

        return $query;
    }

    /**
     * Scope a query to search for a query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  array $queryParams
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchColumns(Builder $query, array $queryParams): Builder
    {
        return $query->where(function ($query) use ($queryParams) {
            foreach ($this->searchableColumns as $column => $operator) {
                if (isset($queryParams[$column])) {
                    $query->where($column, $operator, $queryParams[$column]);
                }
            }
        });
    }
}
