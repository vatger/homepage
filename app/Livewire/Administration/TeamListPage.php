<?php

namespace App\Livewire\Administration;

use App\Livewire\Helpers\NotyTrait;
use App\Livewire\Helpers\PaginationTrait;
use App\Models\Groups\Team;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class TeamListPage extends Component
{
    use NotyTrait, PaginationTrait;

    #[Url]
    public string $search = '';

    public string $new_name = '';

    public function boot(): void
    {
        Auth::user()->can('membership.teams.view');
    }

    #[Layout('layouts.admin.admin-master')]
    public function render(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        // todo improve
        $limitedselection = false;
        $teams = Team::where('name', 'LIKE', '%'.Str::of($this->search)->trim().'%')->get();
        if (! Auth::user()->hasPermissionTo('membership.teams.view')) {
            $teams = $teams->filter(function ($team) {
                return Gate::allows('membership.teams.edit.members.subteam-check', $team);
            });
            $limitedselection = true;
        }

        return view('pages.admin.teams')->with([
            'teams' => $teams->paginate(100),
            'limited_selection' => $limitedselection,
        ]);
    }

    public function create_team(): void
    {
        $this->authorize('membership.teams.edit');
        try {
            $t = new Team;
            $t->name = $this->new_name;
            $t->save();
        } catch (\Exception $e) {
            $this->showNoty('Fehler bei der Erstellung', 'error');
        }
    }
}
