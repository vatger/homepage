<?php

namespace App\Livewire\Helpers;

use Illuminate\Contracts\Database\Eloquent\Builder as DBuilder;
use Illuminate\Database\Eloquent\Builder as EBuilder;
use Illuminate\Database\Query\Builder as QBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @internal array $searchable_fields
 * @internal bool $custom_name_filtering
 *
 */
trait SearchTrait
{
    private array $searchable_fields_internal = [];
    private bool $custom_name_filtering_internal = false;

    private function getSearchableFields(): array
    {
        if (property_exists($this, 'searchable_fields')) {
            return array_merge_recursive_distinct($this->searchable_fields_internal, $this->searchable_fields);
        }
        return $this->searchable_fields_internal;
    }

    private function getCustomNameFiltering(): bool
    {
        if (property_exists($this, 'custom_name_filtering')) {
            return $this->custom_name_filtering;
        }
        return $this->custom_name_filtering_internal;
    }

    protected function setSearchable(array $fields): void
    {
        // must be database attributes of the model
        $this->searchable_fields_internal = array_unique($fields);
    }

    protected function setCustomNameFiltering(): void
    {
        // add 'firstname' and 'lastname' and/or combinations of both to the search string
        $this->custom_name_filtering_internal = true;
    }

    protected function searchQueryModifier(QBuilder|EBuilder|DBuilder|null|array &$query, string $search_str): void
    {
        if (empty($query)) {
            return;
        }
        if (empty($this->getSearchableFields()) && !$this->getCustomNameFiltering()) {
            return;
        }
        $query = $query->where(function ($query) use ($search_str) {
            foreach ($this->getSearchableFields() as $i => $sf) {
                if ($i == 0) {
                    if (str_contains($sf, '.')) {
                        $sfp = explode('.', $sf);
                        $query = $query->whereRelation($sfp[0], $sfp[1], 'LIKE', '%' . $search_str . '%');
                    } else {
                        $query = $query->where($sf, 'LIKE', '%' . $search_str . '%');
                    }
                } else {
                    if (str_contains($sf, '.')) {
                        $sfp = explode('.', $sf);
                        $query = $query->orWhereRelation($sfp[0], $sfp[1], 'LIKE', '%' . $search_str . '%');
                    } else {
                        $query = $query->orWhere($sf, 'LIKE', '%' . $search_str . '%');
                    }
                }
            }
            if ($this->getCustomNameFiltering()) {
                foreach (explode(' ', $search_str) as $str) {
                    $query = $query->orWhere('firstname', 'LIKE', '%' . $str . '%');
                    $query = $query->orWhere('lastname', 'LIKE', '%' . $str . '%');
                }
            }
        });
    }

    protected function searchCollectionModifier(Collection &$collection, string $search_str): void
    {
        if ($this->getCustomNameFiltering()) {
            $collection = $collection->filter(function ($m) use ($search_str) {
                return str_contains($m->id, $search_str) ||
                    str_contains(strtolower($m->username), $search_str) ||
                    str_contains(strtolower($m->email), $search_str);
            });
        }
    }
}
