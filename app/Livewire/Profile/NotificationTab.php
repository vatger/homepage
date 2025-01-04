<?php

namespace App\Livewire\Profile;

use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SearchTrait;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationTab extends Component
{
    use PaginationTrait, SearchTrait;

    public bool $unread = false;

    public function render()
    {
        $user = auth()->user();
        if (! $this->unread) {
            $notifications = $user?->notifications;
        } else {
            $notifications = $user?->unreadNotifications;
        }

        return view('components.profile.notificationtab')->with(['notifications' => $notifications?->paginate(), 'user' => $user]);
    }

    public function notification_click(string $id)
    {
        $n = Auth::user()
            ->notifications()
            ->findOrFail($id);
        $n->read_at ? $n->markAsUnread() : $n->markAsRead();
    }
}
