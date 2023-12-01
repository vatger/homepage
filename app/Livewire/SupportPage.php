<?php

namespace App\Livewire;

use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SearchTrait;
use App\Models\Membership\User\User;
use App\Models\Navigation\Aerodrome;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use phpDocumentor\Reflection\Types\Integer;

class SupportPage extends Component
{
    use PaginationTrait, SearchTrait;

    public $chosen_sup_type = 0;
    public $chosen_area = 0;

    #[Layout('layouts.master')]
    public function render()
    {
        $user = auth()->user();
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
}
