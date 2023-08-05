<?php

namespace App\Livewire\Profile;

use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SearchTrait;
use Livewire\Component;

class NotificationTab extends Component
{
    use PaginationTrait, SearchTrait;

    public function render()
    {
        $user = auth()->user();
        $notifications = $user?->notifications;

        return view('components.profile.notificationtab')->with(['notifications' => $notifications?->paginate(5), 'user' => $user]);
    }
}
