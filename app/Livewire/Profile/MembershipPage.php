<?php

namespace App\Livewire\Profile;

use App\Models\Membership\StaffNameFormat;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class MembershipPage extends Component
{
    #[Url]
    public string $tab = 'profile';

    public string $staff_name_format = StaffNameFormat::FullName->value;

    public function mount(): void
    {
        $this->staff_name_format = auth()->user()->staffDetails?->staff_name_format?->value
            ?? StaffNameFormat::FullName->value;
    }

    #[Layout('layouts.master')]
    public function render(): View
    {
        $user = auth()->user();

        return view('pages.membership')->with(['user' => $user, 'tab' => $this->tab]);
    }

    public function sel(string $sel): void
    {
        $this->tab = $sel;
    }

    public function updatedStaffNameFormat(string $value): void
    {
        $format = StaffNameFormat::tryFrom($value);
        $staffDetails = auth()->user()->staffDetails;

        if (! $format || ! $staffDetails) {
            $this->staff_name_format = StaffNameFormat::FullName->value;

            return;
        }

        $staffDetails->staff_name_format = $format;
        $staffDetails->save();
    }
}
