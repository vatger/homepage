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

class SurveyPage extends Component
{
    public $selected_survey;
    public $selected_selection;

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        $ls = new LimesurveyLibrary();
        return view('pages.admin.survey')->with(['surveys' => $ls->list_surveys(), 'keys' => SurveyKey::all()]);
    }
}
