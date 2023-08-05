<?php

namespace App\Livewire\Helpers;

use Illuminate\Contracts\Database\Eloquent\Builder as DBuilder;
use Illuminate\Database\Eloquent\Builder as EBuilder;
use Illuminate\Database\Query\Builder as QBuilder;
use Illuminate\Support\Collection;

trait SearchTrait
{
    private array $searchable_fields = [];
    private bool $custom_name_filtering = false;

    protected function setSearchable(array $fields): void
    {
        // must be database attributes of the model
        $this->searchable_fields = array_unique($fields);
    }

    protected function setCustomNameFiltering(): void
    {
        // add 'firstname' and 'lastname' and/or combinations of both to the search string
        $this->custom_name_filtering = true;
    }

    protected function searchQueryModifier(QBuilder|EBuilder|DBuilder &$query, string $search_str): void
    {
        if (empty($this->searchable_fields) && !$this->custom_name_filtering) {
            return;
        }
        $query = $query->where(function ($query) use ($search_str) {
            foreach ($this->searchable_fields as $i => $sf) {
                if ($i == 0) {
                    $query = $query->where($sf, 'LIKE', '%' . $search_str . '%');
                } else {
                    $query = $query->orWhere($sf, 'LIKE', '%' . $search_str . '%');
                }
            }
            if ($this->custom_name_filtering) {
                foreach (explode(' ', $search_str) as $str) {
                    $query = $query->orWhere('firstname', 'LIKE', '%' . $str . '%');
                    $query = $query->orWhere('lastname', 'LIKE', '%' . $str . '%');
                }
            }
        });
    }

    protected function searchCollectionModifier(Collection &$collection, string $search_str): void
    {
        if ($this->custom_name_filtering) {
            $collection = $collection->filter(function ($m) use ($search_str) {
                return str_contains($m->id, $search_str) ||
                    str_contains(strtolower($m->username), $search_str) ||
                    str_contains(strtolower($m->email), $search_str);
            });
        }
    }
}
