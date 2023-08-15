<?php

namespace App\Livewire\Administration;

use App\Livewire\Helpers\PaginationTrait;
use App\Models\Groups\Team;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class TeamListPage extends Component
{
    use PaginationTrait;

    #[Url]
    public string $search = '';

    #[Layout('layouts.admin-master')]
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $teams = Team::where('name', 'LIKE', '%' . Str::of($this->search)->trim() . '%')->get();
        return view('pages.admin.teams')->with(['teams' => $teams->paginate()]);
    }
}
