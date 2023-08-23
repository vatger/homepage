<?php

namespace App\Livewire\Administration;

use App\Livewire\Helpers\PaginationTrait;
use App\Models\Groups\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class TeamListPage extends Component
{
    use PaginationTrait;

    #[Url]
    public string $search = '';

    public function boot(): void
    {
        Auth::user()->can('membership.teams.view');
    }

    #[Layout('layouts.admin-master')]
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        //todo improve
        $limitedselection = false;
        $teams = Team::where('name', 'LIKE', '%' . Str::of($this->search)->trim() . '%')->get();
        if (!Auth::user()->hasPermissionTo('membership.teams.view')) {
            $teams = $teams->filter(function ($team) {
                return Gate::allows('membership.teams.edit.members.subteam-check', $team);
            });
        }
        return view('pages.admin.teams')->with([
            'teams' => $teams->paginate(),
            'limited_selection' => $limitedselection,
        ]);
    }
}
