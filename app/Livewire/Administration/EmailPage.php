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
    public $newmail = '';
    public string $cid = '';

    public function mount()
    {
        $this->users = User::permission('mail.use')->get();
        foreach ($this->users as $user) {
            if ($user->staffDetails) {
                $this->emails[] = (object) [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->staffDetails->staff_email,
                    'change' => $user->staffDetails->staff_email_created,
                    'create' => $user->staffDetails->staff_email_created,
                ];
            }
        }
    }
    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        return view('pages.admin.email');
    }
    public function change(string $id, string $email)
    {
        $this->newmail = $email;
        $this->cid = $id;
    }
    public function save()
    {
        foreach ($this->emails as $email) {
            if ($email->id == $this->cid) {
                $email->email = $this->newmail;
            }
        }
    }
}
