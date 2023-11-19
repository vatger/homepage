<?php

namespace App\Livewire\Administration;

use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SortableTrait;
use App\OpenApi\Models\ApiLog;
use App\OpenApi\Models\ApiToken;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class SurveyPage extends Component
{
    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        return view('pages.admin.survey');
    }
}
