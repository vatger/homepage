<?php

namespace App\Livewire\Administration;

use App\Libraries\LimesurveyLibrary;
use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SortableTrait;
use App\Models\SurveyKey;
use App\OpenApi\Models\ApiLog;
use App\OpenApi\Models\ApiToken;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use const Fp\id;

class SurveyPage extends Component
{
    public $selected_survey;
    public $selected_selection;

    private $selections = [
        (object) [
            'id' => 1,
            'name' => 'Wahlberechtigt VATGER',
        ],
        (object) [
            'id' => 2,
            'name' => 'Wahlberechtigt EDWW',
        ],
        (object) [
            'id' => 3,
            'name' => 'Wahlberechtigt EDFF',
        ],
        (object) [
            'id' => 4,
            'name' => 'Wahlberechtigt EDMM',
        ],
        (object) [
            'id' => 5,
            'name' => 'Vollmitglied VATGER',
        ],
    ];

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        $ls = new LimesurveyLibrary();
        return view('pages.admin.survey')->with(['surveys' => $ls->list_surveys(), 'keys' => SurveyKey::all(), 'selections' => $this->selections]);
    }
}
