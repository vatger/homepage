<?php

namespace App\Livewire;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Layout;
use Livewire\Component;

class RequiredCoursesPage extends Component
{
    #[Layout('layouts.master')]
    public function render()
    {
        $data = Cache::remember('RequiredCoursesPage.courses.data', 1, function () {
            return json_decode(Http::get('https://raw.githubusercontent.com/VATGER-ATD/required-courses/main/courses.json')->body());
        });
        $courses = collect($data);
        //dd($courses);
        if ($courses->count() < 2) return Redirect::back()->withErrors('Error');


        return view('pages.required-courses')->with(['courses' => $courses]);
    }
}
