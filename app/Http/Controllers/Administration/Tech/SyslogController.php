<?php

namespace App\Http\Controllers\Administration\Tech;

use App\Http\Controllers\Controller;
use App\Models\SysLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SyslogController extends Controller
{
    public function index()
    {
        $this->authorize('tech-access');

        $logs = SysLog::paginate(15);
        return $this->prepareView('administration.tech.syslog')->with('logs', $logs);
    }

    public function getSyslogPaginated(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }
        $this->authorize('tech-access');

        return SysLog::orderByDesc('created_at')->paginate(15);
    }

    public function getSyslogSearch(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }
        $this->authorize('tech-access');

        $date = Carbon::createFromFormat('d.m.Y', $request->get('search_param'));

        return SysLog::whereDate('created_at', 'LIKE', '%' . $date->format('Y-m-d') . '%')
            ->orderByDesc('created_at')
            ->get();
    }

    public function getSyslogInfo(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }
        $this->authorize('tech-access');

        return SysLog::find($request->get('syslogid'));
    }
}
