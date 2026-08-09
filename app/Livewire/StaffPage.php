<?php

namespace App\Livewire;

use App\Models\Groups\Team;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Livewire\Attributes\Layout;
use Livewire\Component;

class StaffPage extends Component
{
    #[Layout('layouts.master')]
    public function render(): View
    {
        $teams = Team::query()
            ->where('show', true)
            ->with([
                'users' => function (BelongsToMany $users): void {
                $users->wherePivot('show', true);
                },
                'users.staffDetails.user',
            ])
            ->orderBy('order')
            ->get();

        $locale = app()->getLocale();

        $teams->each(function (Team $team) use ($locale): void {
            $team->setAttribute(
                'display_title',
                $locale === 'de'
                    ? ($team->title_de ?: $team->title_en ?: $team->name)
                    : ($team->title_en ?: $team->title_de ?: $team->name),
            );
            $team->setAttribute(
                'display_email',
                $team->email ? self::obfuscatedEmail($team->email) : null,
            );

            $team->users->each(function (object $user) use ($locale): void {
                $membership = $user->pivot;
                $user->setAttribute(
                    'membership_title',
                    $locale === 'de'
                        ? ($membership->title_de ?: $membership->title_en)
                        : ($membership->title_en ?: $membership->title_de),
                );
            });
        });

        $staffTeams = $teams->filter(
            fn (Team $team): bool => (int) $team->order <= 99,
        );
        $extendedStaffTeams = $teams->filter(
            fn (Team $team): bool => (int) $team->order > 99,
        );

        return view('pages.staff', [
            'teams' => $teams,
            'staffTeams' => $staffTeams,
            'extendedStaffTeams' => $extendedStaffTeams,
        ]);
    }

    public static function obfuscatedEmail(string $email): string
    {
        return str_replace('@', ' [AT] ', $email);
    }
}
