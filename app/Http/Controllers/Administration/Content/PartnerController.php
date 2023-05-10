<?php

namespace App\Http\Controllers\Administration\Content;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::query()->paginate(15);

        return view('administration.content.partner.index')->with(['partners' => $partners]);
    }

    public function viewAll()
    {
        $partners = Partner::all();

        return view('homepage.general.partners.index')->with(['partners' => $partners]);
    }

    public function getPartnersPaginated(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }

        return Partner::query()->paginate(15);
    }

    public function getPartnerSearch(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }

        return Partner::query()
            ->where('name', 'LIKE', '%' . $request->get('search_param') . '%')
            ->get();
    }

    public function findPartnerById(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }

        return Partner::query()->findOrFail($request->get('id'));
    }

    public function submitPartner(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }

        $request->validate([
            'name' => 'required',
            'image' => 'required',
            'link' => 'required',
        ]);

        $partner = Partner::query()->find($request->post('id'));

        // Check if the partner exists -> then edit
        if ($partner) {
            $partner->update([
                'name' => $request->post('name'),
                'logo_url' => $request->post('image'),
                'link_url' => $request->post('link'),
                'description_de' => $request->post('text_de'),
                'description_en' => $request->post('text_en'),
            ]);

            return $partner;
        }

        // Else, create new
        return Partner::query()->create([
            'created_by' => Auth::user()->id,
            'name' => $request->post('name'),
            'logo_url' => $request->post('image'),
            'link_url' => $request->post('link'),
            'description_de' => $request->post('text_de'),
            'description_en' => $request->post('text_en'),
        ]);
    }

    public function removePartner(Request $request)
    {
        if (!$request->ajax()) {
            abort(503, 'Method not supported');
        }

        $p = Partner::query()->findOrFail($request->get('id'));
        return $p->delete();
    }
}
