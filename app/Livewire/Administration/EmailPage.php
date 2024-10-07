<?php

namespace App\Livewire\Administration;

use App\Libraries\MailcowLibrary;
use App\Livewire\Helpers\NotyTrait;
use App\Models\Membership\User;
use App\Models\Membership\UserStaffDetail;
use App\Notifications\BasicNotification;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

class EmailPage extends Component
{
    use NotyTrait;

    protected array $emails = [];
    public string $newmail = '';
    public string $cid = '';

    public function mount(): void
    {
        $users = [];
        $users = User::permission('mail.use')->get();

        $usd = UserStaffDetail::query()
            ->where('staff_email_created', true)
            ->whereNotIn(
                'user_id',
                collect($users)
                    ->map(fn($u) => $u->id)
                    ->flatten()
                    ->toArray(),
            )
            ->get();

        foreach ($usd as $item) {
            $users[] = User::findOrFail($item->user_id);
        }

        foreach ($users as $user) {
            if ($user->staffDetails) {
                $mail = strtolower("$user->firstname.$user->lastname@vatger.de");
                $this->emails[] = (object)[
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->staffDetails->staff_email_created ? $user->staffDetails->staff_email : $mail,
                    'change' => $user->staffDetails->staff_email_created,
                    'create' => $user->staffDetails->staff_email_created,
                    'deletion_date' => $user->staffDetails->delete_staff_email_at
                        ? date('d.m.Y H:i:s', strtotime($user->staffDetails->delete_staff_email_at))
                        : '',
                ];
            }
        }
    }

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        $this->authorize('mail.manage');
        return view('pages.admin.email');
    }

    public function change(string $id, string $email): void
    {
        $this->newmail = $email;
        $this->cid = $id;
    }

    public function save(): void
    {
        if (!filter_var($this->newmail, FILTER_VALIDATE_EMAIL)) {
            $this->showNoty('Bitte gültige E-Mail Adresse erfassen.', 'error');
            return;
        }

        $this->newmail = explode('@', $this->newmail)[0] . '@vatger.de';

        foreach ($this->emails as $email) {
            if ($email->id == $this->cid) {
                $email->email = $this->newmail;
                $user = User::find($this->cid);
                $user->staffDetails->staff_email = $email->email;
                $user->staffDetails->save();
            }
        }
    }

    public function create(string $id): void
    {
        $mailcreated = false;
        foreach ($this->emails as $email) {
            if ($email->id == $id) {
                $user = User::find($id);

                $pwd = 'V' . Str::random(24) . '!';

                $mailcreated = MailcowLibrary::create_email("$email->email", "$user->username", $pwd);
                if (!$mailcreated) {
                    $this->showNoty('Email konnte nicht angelegt werden');
                } else {
                    $this->showNoty('Email erfolgreich angelegt');
                    $user->staffDetails->staff_email = $email->email;
                    $email->change = $email->create = $user->staffDetails->staff_email_created = true;
                    $user->staffDetails->save();

                    $user->notify(
                        new BasicNotification(
                            'Deine VATSIM Germany E-Mail Adresse',
                            "Es wurde eine VATSIM Germany E-Mail-Adresse für dich angelegt. Diese lautet:
                    <code>$email->email</code>.
                    Das Initialpasswort lautet: 
                    <code>$pwd</code>
                    Du wirst nach dem ersten Login aufgefordert dein Passwort zu ändern. Bitte beachte auch die Hinweise in der Dokumentation bezüglich des ersten Login",
                            'Tech Leitung',
                            'hier gehts zur Doku',
                            strval(config('mailcow.doku-url')),
                            Carbon::now()->addDays(14),
                            Carbon::now()->addDays(365),
                        ),
                    );
                }
            }
        }
    }
}
