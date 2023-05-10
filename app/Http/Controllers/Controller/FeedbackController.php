<?php

namespace App\Http\Controllers\Controller;

use App\Http\Controllers\Controller;
use App\Libraries\VATSIM\DataFeedLibrary;
use App\Models\Feedback\ControllerFeedback;
use App\Models\Membership\User\User;
use App\Models\Navigation\Station;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Returns view containing feedback-creation form
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        return view('homepage.controllers.feedback.atcfb');
    }

    /**
     * Stores submitted ATC-Feedback
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request): mixed
    {
        $validated = $request->validate([
            'user-cid' => 'required|exists:membership_accounts,id',
            'subject' => 'required|string',
            'feedback' => 'required|string',
            'report-date' => 'required|date',
        ]);

        if (Auth::user()->id == $validated['user-cid']) {
            return redirect()
                ->back()
                ->withErrors("Error, you can't give feedback to yourself.");
        }

        $sid = false;
        $sid = Station::where('ident', $validated['subject'])->first()->id;
        if ($sid === false || $sid === null) {
            return redirect()
                ->back()
                ->withErrors('Error: The provided ATC station is unkown to our system.');
        }

        $cf = ControllerFeedback::create([
            'reporter_id' => Auth::user()->id,
            //'station_id' => $validated['subject'], // This will crash
            'station_id' => $sid,
            'controller_id' => $validated['user-cid'],
            'feedback' => $validated['feedback'],
            'report_date' => $validated['report-date'],
        ]);

        if (!$cf) {
            return redirect()
                ->back()
                ->withErrors('An error occured. Please try again.');
        }

        return redirect()
            ->route('controllers.feedback')
            ->withSuccess('Thank you for your feedback!');
    }

    /**
     * Returns boolean value depending on the existance of the user.
     *
     * @param Request $request
     * @return ResponseFactory|Response|Application|int
     */
    public function checkDoesUserExist(Request $request): Application|ResponseFactory|Response|int
    {
        $usr = User::query()->find($request->get('cid'));

        if ($usr) {
            return $usr->id;
        } else {
            return response('user not found', 404);
        }
    }
}
