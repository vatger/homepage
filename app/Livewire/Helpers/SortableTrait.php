<?php

namespace App\Livewire\Helpers;

use Illuminate\Contracts\Database\Eloquent\Builder as DBuilder;
use Illuminate\Database\Eloquent\Builder as EBuilder;
use Illuminate\Database\Query\Builder as QBuilder;

trait SortableTrait
{
    public $sort_by = '';
    public $sort_order = 'asc';
    private array $sortable_fields_internal = [];

    private function checkAllowedSortableFiled(string $field): bool
    {
        if (property_exists($this, 'sortable_fields')) {
            $this->sortable_fields_internal = array_merge_recursive_distinct($this->sortable_fields_internal, $this->sortable_fields);
        }
        return !in_array($field, $this->sortable_fields_internal, true);
    }

    public function sortBy($field): void
    {
        if ($this->checkAllowedSortableFiled($field)) {
            abort(400, "[SortableTrait] Not sortable by $field");
        }
        $this->sort_order = $this->sort_by === strval($field) ? $this->reverseSort() : 'asc';
        $this->sort_by = $field;
    }

    public function getSortIconClasses($field): string
    {
        if ($this->sort_by != $field) {
            return 'minus';
        }
        if ($this->sort_order == 'asc') {
            return 'chevron-down';
        } else {
            return 'chevron-up';
        }
    }

    protected function setSortable(array $fields): void
    {
        $this->sortable_fields_internal = array_unique($fields);
    }

    protected function setInitialSortOrder(string $field, string $order): void
    {
        if ($this->checkAllowedSortableFiled($field)) {
            abort(400, "[SortableTrait] Not sortable by $field");
        }
        if (!in_array($order, ['asc', 'desc'], true)) {
            abort(400, "[SortableTrait] $order not a valid oder");
        }
        $this->sort_by = $field;
        $this->sort_order = $order;
    }

    protected function sortQueryModifier(QBuilder|EBuilder|DBuilder &$query): void
    {
        if (empty($this->sort_by)) {
            return;
        }
        if ($this->sort_order == 'asc') {
            $query = $query->orderBy($this->sort_by);
        }
        if ($this->sort_order == 'desc') {
            $query = $query->orderByDesc($this->sort_by);
        }
    }

    private function reverseSort(): string
    {
        return $this->sort_order === 'asc' ? 'desc' : 'asc';
    }
}
