<?php

namespace App\Http\Livewire\Profile;

use App\Http\Livewire\Helpers\PaginationTrait;
use App\Http\Livewire\Helpers\SearchTrait;
use Livewire\Component;

class Notificationlist extends Component
{
    use PaginationTrait, SearchTrait;

    public function render()
    {
        $user = auth()->user();
        $notifications = $user?->notifications;

        return view('components.profile.notificationlist_lw')->with(['notifications' => $notifications?->paginate(5), 'user' => $user]);
    }
}
