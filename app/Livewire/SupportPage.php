<?php

namespace App\Livewire;

use App\Libraries\OSTicketLibrary;
use App\Libraries\VikunjaLibrary;
use App\Livewire\Helpers\NotyTrait;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use function Symfony\Component\Translation\t;


class SupportPage extends Component
{
    use NotyTrait;

    public string $token = '';
    #[Url]
    public int $chosen_area = 0;
    #[Url]
    public int $chosen_sup_type = 0;

    public ?object $selected_area = null;
    public ?object $selected_type = null;

    public string $name = '';
    public string $mail = '';
    public string $cid = '';
    public string $subject = '';
    public string $content = '';


    public static function getData(?int $area_id = null, ?int $type_id = null): array|object
    {
        try {
            $data = File::get(storage_path("app/configurations/support.json"));
        } catch (FileNotFoundException $e) {
            return [];
        }
        $json = json_decode($data);
        $counter_area = 1;
        $counter_type = 1;
        foreach ($json as $area) {
            $area->id = $counter_area++;
            if (property_exists($area, 'trans')) {
                $area->name = __($area->trans);
            }
            foreach ($area->types as $type) {
                $type->id = $counter_type++;
                if (property_exists($type, 'trans')) {
                    $type->name = __($type->trans);
                }
                if ($type_id != null && $type->id == $type_id) {
                    return $type;
                }
            }
            if ($area_id != null && $area->id == $area_id) {
                return $area;
            }
        }
        return $json;
    }


    public function mount(bool $success = false)
    {
        $user = Auth::user();

        if ($user) {
            $this->name = $user->username;
            $this->mail = $user->email;
            $this->cid = $user->id;
        }
    }

    public function updating($property, $value)
    {
        if ($property === 'chosen_area') {
            $this->chosen_sup_type = 0;
        }
    }

    public function test()
    {
        $this->selected_area = $this->chosen_area == 0 ? null : self::getData($this->chosen_area);
        $this->selected_type = $this->chosen_sup_type == 0 ? null : self::getData($this->chosen_area, $this->chosen_sup_type);
    }

    #[Layout('layouts.master')]
    public function render()
    {
        $user = Auth::user();

        $areas = self::getData();

        $this->test();


        return view('pages.support')->with([
            'areas' => $areas,
            'selected_area' => $this->selected_area,
            'selected_type' => $this->selected_type,
            'user' => $user,
        ]);
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
                'secret' => config('hcaptcha.secret')
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

        $result = null;

        if ($this->selected_type?->system == 'T') {
            $result = OSTicketLibrary::create_ticket(
                "$this->name ($this->cid)",
                $this->mail,
                $this->subject,
                "Anfrage von: $this->name ($this->cid),\n------------------------------\n \n $this->content",
                $this->selected_type->topic_id
            );
        }
        if ($this->selected_type?->system == 'V') {
            $L = VikunjaLibrary::get_instance();
            $result = $L->create_task(
                $this->subject,
                $this->content,
                $this->cid,
                $this->selected_type->project_id,
                $this->selected_type->label,
            );
        }

        if ($result) {
            return redirect(request()->header('Referer'))->with('success', __('support.text-success'));
        } else {
            $this->showNoty(__('support.text-fail'), 'error');
        }
    }
}
