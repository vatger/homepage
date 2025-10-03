<?php

namespace App\Livewire\Administration;

use App\Libraries\LimesurveyLibrary;
use App\Livewire\Helpers\NotyTrait;
use App\Livewire\Helpers\PaginationTrait;
use App\Models\Membership\SurveyKey;
use App\Models\Membership\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

class SurveyPage extends Component
{
    use NotyTrait, PaginationTrait;

    public $selected_survey;

    public $selected_selection;

    private $selections = [
        [
            'id' => 1,
            'name' => 'Alle User',
        ],
        [
            'id' => 2,
            'name' => 'Wahlberechtigt EDWW',
        ],
        [
            'id' => 3,
            'name' => 'Wahlberechtigt EDGG',
        ],
        [
            'id' => 4,
            'name' => 'Wahlberechtigt EDMM',
        ],
        [
            'id' => 5,
            'name' => 'Vollmitglied VATGER',
        ],
        [
            'id' => 6,
            'name' => 'Vollmitglied VATGER und VATSIM nicht inaktiv',
        ],

    ];

    private LimesurveyLibrary $ls;

    public function boot()
    {
        try {
            $this->ls = new LimesurveyLibrary;
        } catch (\Exception $e) {
            session()->flash('status', 'Survey failed');
            $this->redirect(route('administration.dashboard'));
        }
    }

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        $this->authorize('survey');

        return view('pages.admin.survey')->with([
            'surveys' => $this->ls->list_surveys(),
            'keys' => SurveyKey::paginate(100),
            'selections' => json_decode(json_encode($this->selections)),
        ]);
    }

    public function create_keys(): void
    {
        $users = collect();

        switch ($this->selected_selection) {
            case 1:
                $users = User::lazy()->collect();
                break;

            case 2:
                $users = User::with(['vatgerDetails', 'fir'])
                    ->lazy()
                    ->filter(fn (User $u) => $u->fir?->slug == 'EDWW' && $u->vatgerDetails->is_fir_voter)
                    ->collect();
                break;
            case 3:
                $users = User::with(['vatgerDetails', 'fir'])
                    ->lazy()
                    ->filter(fn (User $u) => $u->fir?->slug == 'EDGG' && $u->vatgerDetails->is_fir_voter)
                    ->collect();
                break;
            case 4:
                $users = User::with(['vatgerDetails', 'fir'])
                    ->lazy()
                    ->filter(fn (User $u) => $u->fir?->slug == 'EDMM' && $u->vatgerDetails->is_fir_voter)
                    ->collect();
                break;
            case 5:
                $users = User::with(['vatgerDetails'])
                    ->lazy()
                    ->filter(fn (User $u) => $u->vatgerDetails?->active_vatger_member_at != null)
                    ->collect();
                break;
            case 6:
                $users = User::with(['vatgerDetails', 'vatsimDetails'])
                    ->lazy()
                    ->filter(fn (User $u) => $u->vatgerDetails?->active_vatger_member_at != null && $u->vatsimDetails?->rating_atc > 0)
                    ->collect();
                break;
            default:
                $this->showNoty('Not implemented', 'error');
                break;
        }
        $count = $users->count();
        $this->showNoty("Found $count users. Creating tokens...", 'success', 5 * 60000);
        try {
            $data = $this->ls->add_participants($this->selected_survey, $users);
            $count = collect($data)->count();
            $this->showNoty("Created $count tokens.", 'success', 5 * 60000);
        } catch (\Exception $e) {
            $this->showNoty('Failed to create tokens. Forgot to create participant table?', 'error', 20000);
        }
    }
}
