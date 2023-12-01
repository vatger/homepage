<?php

namespace App\Livewire;

use App\Libraries\VikunjaLibrary;
use App\Livewire\Helpers\NotyTrait;
use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SearchTrait;
use App\Models\Membership\User\User;
use App\Models\Navigation\Aerodrome;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use phpDocumentor\Reflection\Types\Integer;

class SupportPage extends Component
{
    use NotyTrait;

    public int $chosen_sup_type = 0;
    public int $chosen_area = 0;
    public string $name = '';
    public string $mail = '';
    public string $cid = '';
    public string $subject = '';
    public string $content = '';

    public function mount()
    {
        $user = Auth::user();

        if ($user) {
            $this->name = $user->username;
            $this->mail = $user->email;
            $this->cid = $user->id;
        }
    }
    #[Layout('layouts.master')]
    public function render()
    {
        $user = Auth::user();

        return view('pages.support')->with([
            'supporttype' => [
                (object) ['name' => 'Feature Request', 'id' => '1'],
                (object) ['name' => 'Bug Report', 'id' => '2'],
                (object) ['name' => 'Zugangsdaten', 'id' => '3'],
                (object) ['name' => 'Sonstiges', 'id' => '4'],
            ],
            'areas' => [
                (object) ['id' => '1', 'name' => 'Forum', 'supporttypes' => ['1', '2']],
                (object) ['id' => '2', 'name' => 'Homepage', 'supporttypes' => ['2', '3']],
                (object) ['id' => '3', 'name' => 'Knowledgebase', 'supporttypes' => ['2', '3']],
                (object) ['id' => '4', 'name' => 'DMS', 'supporttypes' => ['1', '3']],
                (object) ['id' => '5', 'name' => 'E-Mail', 'supporttypes' => ['1', '3']],
            ],

            'user' => $user,
        ]);
    }
    public function send()
    {
        if ($this->chosen_sup_type == 0) {
            $this->showNoty('Bitte Supporttyp auswählen', 'error');
            return;
        }

        if ($this->chosen_area == 0) {
            $this->showNoty('Bitte Bereich auswählen', 'error');
            return;
        }

        if (empty($this->mail)) {
            $this->showNoty('Bitte Mailadresse eingeben', 'error');
            return;
        } else {
            if (!filter_var($this->mail, FILTER_VALIDATE_EMAIL)) {
                $this->showNoty('Bitte gültige Mailadresse eingeben', 'error');
                return;
            }
        }

        if (empty($this->subject)) {
            $this->showNoty('Bitte Betreff eingeben', 'error');
            return;
        }

        if (empty($this->content)) {
            $this->showNoty('Bitte Nachricht eingeben', 'error');
            return;
        }

        if (empty($this->name)) {
            $this->showNoty('Bitte Namen eingeben', 'error');
            return;
        }

        $L = new VikunjaLibrary();
        $result = $L->create_task($this->subject, $this->content, "$this->name $this->cid", $this->mail, $this->chosen_sup_type, $this->chosen_area);
        /*$this->showNoty('Hat geklappt', 'success');

        if ($result) {
            $this->showNoty('Hat geklappt', 'success');
        } else {
            $this->showNoty('Hat nicht geklappt', 'error');
        }*/
    }
}
