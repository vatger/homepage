<?php

namespace App\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;

class TermsPage extends Component
{
    private string $gdpr;
    private string $imprint;
    private string $termsofuse;
    private string $satzung;
    private Carbon $gdpr_date;
    private Carbon $imprint_date;
    private Carbon $termsofuse_date;
    private Carbon $satzung_date;

    public function boot(): void
    {
        $this->gdpr = Storage::get('policies/gdpr.html');
        $this->imprint = Storage::get('policies/imprint.html');
        $this->termsofuse = Storage::get('policies/termsofuse.txt');
        $this->satzung = Storage::get('policies/satzung.txt');
        $this->gdpr_date = Carbon::createFromTimestamp(Storage::lastModified('policies/gdpr.html'));
        $this->imprint_date = Carbon::createFromTimestamp(Storage::lastModified('policies/imprint.html'));
        $this->termsofuse_date = Carbon::createFromTimestamp(Storage::lastModified('policies/imprint.html'));
        $this->satzung_date = Carbon::createFromTimestamp(Storage::lastModified('policies/imprint.html'));
    }

    #[Layout('layouts.master')]
    public function render()
    {
        return view('pages.terms')->with([
            'gdpr' => $this->gdpr,
            'imprint' => $this->imprint,
            'termsofuse' => $this->termsofuse,
            'satzung' => $this->satzung,
            'gdpr_date' => $this->gdpr_date,
            'imprint_date' => $this->imprint_date,
            'termsofuse_date' => $this->termsofuse_date,
            'satzung_date' => $this->satzung_date,
        ]);
    }

    public function accept(string $type): void
    {
        switch ($type) {
            case 'gdpr':
                Auth::user()->settings->update(['gdpr_agreed_at' => Carbon::now()]);
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
