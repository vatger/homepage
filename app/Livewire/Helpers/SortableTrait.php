<?php

namespace App\Livewire\Helpers;

use Illuminate\Contracts\Database\Eloquent\Builder as DBuilder;
use Illuminate\Database\Eloquent\Builder as EBuilder;
use Illuminate\Database\Query\Builder as QBuilder;

trait SortableTrait
{
    public $sort_by = '';
    public $sort_order = 'asc';
    private array $sortable_fields = [];

    public function sortBy($field): void
    {
        if (!in_array($field, $this->sortable_fields, true)) {
            abort(400, "[SortableTrait] Not sortable by $field");
        }
        $this->sort_order = $this->sort_by === strval($field) ? $this->reverseSort() : 'asc';
        $this->sort_by = $field;
    }

    public function getSortIconClasses($field): array
    {
        if ($this->sort_by != $field) {
            return ['mdi', 'mdi-sort'];
        }
        if ($this->sort_order == 'asc') {
            return ['mdi', 'mdi-sort-ascending'];
        } else {
            return ['mdi', 'mdi-sort-descending'];
        }
    }

    protected function setSortable(array $fields): void
    {
        $this->sortable_fields = array_unique($fields);
    }

    protected function setInitialSortOrder(string $field, string $order): void
    {
        if (!in_array($field, $this->sortable_fields, true)) {
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
