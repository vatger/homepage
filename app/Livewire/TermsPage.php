<?php

namespace App\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;

class TermsPage extends Component
{


    public function boot(): void
    {
        $this->gdpr = Storage::get('public/policies/gdpr.html');
        $this->imprint = Storage::get('public/policies/imprint.html');
        $this->termsofuse = Storage::get('public/policies/termsofuse.html');
        $this->satzung = Storage::url('public/policies/satzung.pdf');
        $this->gdpr_date = Carbon::createFromTimestamp(Storage::lastModified('public/policies/gdpr.html'));
        $this->imprint_date = Carbon::createFromTimestamp(Storage::lastModified('public/policies/imprint.html'));
        $this->termsofuse_date = Carbon::createFromTimestamp(Storage::lastModified('public/policies/termsofuse.html'));
        $this->satzung_date = Carbon::createFromTimestamp(Storage::lastModified('public/policies/satzung.pdf'));
    }

    #[Layout('layouts.master')]
    public function render()
    {
        return view('pages.policy_check')->with([
            'gdpr' => $this->gdpr,
            'imprint' => $this->imprint,
            'termsofuse' => $this->termsofuse,
            'satzung' => $this->satzung,
            'gdpr_date' => $this->gdpr_date,
            'imprint_date' => $this->imprint_date,
            'termsofuse_date' => $this->termsofuse_date,
            'satzung_date' => $this->satzung_date,
            'user_settings' => Auth::user()?->settings,
        ]);
    }

    public function accept(string $type): void
    {
        switch ($type) {
            case 'gdpr':
                Auth::user()->settings->update(['gdpr_agreed_at' => Carbon::now()]);
                break;
            case 'imprint':
                Auth::user()->settings->update(['imprint_agreed_at' => Carbon::now()]);
                break;
            case 'termsofuse':
                Auth::user()->settings->update(['termsofuse_agreed_at' => Carbon::now()]);
                break;
            case 'satzung':
                Auth::user()->settings->update(['satzung_agreed_at' => Carbon::now()]);
                break;
            default:
        }
    }

    public function decline(string $type): void
    {
        switch ($type) {
            case 'gdpr':
                Auth::user()->settings->update(['gdpr_agreed_at' => null]);
                break;
            case 'imprint':
                Auth::user()->settings->update(['imprint_agreed_at' => null]);
                break;
            case 'termsofuse':
                Auth::user()->settings->update(['termsofuse_agreed_at' => null]);
                break;
            case 'satzung':
                Auth::user()->settings->update(['satzung_agreed_at' => null]);
                break;
            default:
        }
    }
}
