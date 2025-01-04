<?php

namespace App\Livewire\Administration;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\Form;

class BanForm extends Form
{
    public string $endDate = '';

    public bool $permanent = false;

    public bool $teamspeak = true;

    public bool $forum = true;

    public bool $homepage = true;

    public bool $otherServices = true;

    public string $reason = '';

    public function __construct(Component $component, $propertyName)
    {
        parent::__construct($component, $propertyName);
        $this->endDate = Carbon::now()->addDay()->roundHour()->format('Y-m-d H:i');
    }
}
