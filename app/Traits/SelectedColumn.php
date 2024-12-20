<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait SelectedColumn
{
    public function scopeSelectedColumn(Builder $query, bool $paginated = true): Builder
    {
        return $query->select(
            collect($this->getFillable())
                ->flip()
                ->when($paginated, function ($query1) {
                    return $query1->except(['created_by', 'updated_by', 'deleted_by']);
                })
                ->when($paginated == false, function ($query1) {
                    return $query1->except(['created_by', 'updated_by', 'deleted_by', 'status']);
                })
                ->keys()
                ->merge('id')
                ->map(function ($column) {
                    return $this->getTable() . '.' . $column;
                })
                ->toArray()
        );
    }

    public function scopeIsActive(Builder $query): Builder
    {
        return $query->where($this->getTable() . '.' . 'status', true);
    }
}

