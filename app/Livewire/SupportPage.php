<?php

namespace App\Livewire;

use App\Libraries\OSTicketLibrary;
use App\Libraries\VikunjaLibrary;
use App\Livewire\Helpers\NotyTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Layout;
use Livewire\Component;

class SupportPage extends Component
{
    use NotyTrait;

    public string $token = '';
    public int $chosen_sup_type = 0;
    public int $chosen_area = 0;
    public string $name = '';
    public string $mail = '';
    public string $cid = '';
    public string $subject = '';
    public string $content = '';

    public function mount(bool $success = false)
    {
        $user = Auth::user();

        if ($user) {
            $this->name = $user->username;
            $this->mail = $user->email;
            $this->cid = $user->id;
        }
    }
    #[Layout('layouts.master')]
    public function render()
    {
        $user = Auth::user();

        return view('pages.support')->with([
            'supporttype' => [
                (object) ['name' => 'Feature Request', 'id' => '1', 'areas' => ['1', '2']],
                (object) ['name' => 'Bug Report', 'id' => '2', 'areas' => ['1', '2']],
                (object) ['name' => __('support.text-error-kb'), 'id' => '3', 'areas' => ['2', '3', '4', '5', '6', '8']],
                (object) ['name' => __('support.text-credentials'), 'id' => '4', 'areas' => ['1', '2', '3']],
                (object) ['name' => __('support.text-others'), 'id' => '5', 'areas' => ['1', '2', '3', '4', '5', '6', '7', '8']],
                (object) ['name' => 'ATCO / IVAO Rating Transfer', 'id' => '6', 'areas' => ['4']],
            ],
            'areas' => [
                (object) ['id' => '1', 'name' => 'Tech'],
                (object) ['id' => '2', 'name' => 'NAV'],
                (object) ['id' => '3', 'name' => 'Event'],
                (object) ['id' => '4', 'name' => 'ATC Training Department'],
                (object) ['id' => '5', 'name' => 'Pilot Training Department'],
                (object) ['id' => '6', 'name' => __('support.text-pilot-rep')],
                (object) ['id' => '7', 'name' => __('support.text-director')],
                (object) ['id' => '8', 'name' => __('support.text-others')],
            ],

            'user' => $user,
        ]);
    }
    private function choose_system(): string
    {
        $ret = 'T';
        if ($this->chosen_area == '1') {
            if ($this->chosen_sup_type == '1' || $this->chosen_sup_type == '2') {
                $ret = 'V';
            }
        }
        if ($this->chosen_sup_type == '3') {
            $ret = 'V';
        }
        return $ret;
    }
    public function send()
    {
        if (empty($this->token)) {
            $this->showNoty(__('support.text-missing-captcha'), 'error');
            return;
        }

        $captchaResp = Http::asForm()
            ->post('https://hcaptcha.com/siteverify', [
                'response' => $this->token,
                'secret' => env('HCAPTCHA_SECRET'),
            ])
            ->object();

        if (!$captchaResp->success) {
            $this->showNoty(__('support.text-error-captcha'), 'error');
            return;
        }

        if ($this->chosen_sup_type == 0) {
            $this->showNoty(__('support.text-missing-supporttype'), 'error');
            return;
        }

        if ($this->chosen_area == 0) {
            $this->showNoty(__('support.text-missing-area'), 'error');
            return;
        }

        if (empty($this->mail)) {
            $this->showNoty(__('support.text-missing-mail'), 'error');
            return;
        } else {
            if (!filter_var($this->mail, FILTER_VALIDATE_EMAIL)) {
                $this->showNoty(__('support.text-wrong-mail'), 'error');
                return;
            }
        }

        if (empty($this->subject)) {
            $this->showNoty(__('support.missing-subject'), 'error');
            return;
        }

        if (empty($this->content)) {
            $this->showNoty(__('support.missing-content'), 'error');
            return;
        }

        if (empty($this->name)) {
            $this->showNoty(__('support.text-missing-name'), 'error');
            return;
        }

        if ($this->choose_system() == 'T') {
            $result = OSTicketLibrary::create_ticket(
                $this->name,
                $this->mail,
                $this->subject,
                $this->content,
                $this->chosen_sup_type,
                $this->chosen_area,
            );
        } else {
            $L = VikunjaLibrary::get_instance();
            $result = $L->create_task($this->subject, $this->content, $this->cid, $this->chosen_sup_type, $this->chosen_area);
        }

        if ($result) {
            return redirect(request()->header('Referer'))->with('success', __('support.text-success'));
        } else {
            $this->showNoty(__('support.text-fail'), 'error');
        }
    }
}
