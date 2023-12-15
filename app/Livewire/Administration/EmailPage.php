<?php

namespace App\Livewire\Administration;

use App\Libraries\LimesurveyLibrary;
use App\Livewire\Helpers\NotyTrait;
use App\Models\Membership\User\User;
use App\Models\SurveyKey;
use Livewire\Attributes\Layout;
use Livewire\Component;

class EmailPage extends Component
{
    use NotyTrait;
    private $users = [];
    public $emails = [];

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        $this->users = User::all();
        $this->emails = [];
        foreach ($this->users as $user) {
            $this->emails[] = (object) [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'change' => 1 == 2 ? true : false,
                'create' => 1 == 1 ? true : false,
            ];
        }

        return view('pages.admin.email')->with([
            'emails' => $this->emails,
        ]);
    }
    public function change(string $id)
    {
        $this->showNoty($id);
    }
    public function create(string $id)
    {
        $this->showNoty($id);
    }
}
