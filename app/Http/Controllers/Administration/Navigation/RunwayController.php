<?php

namespace App\Http\Controllers\Administration\Navigation;

use App\Http\Controllers\Controller;
use App\Models\Navigation\Aerodrome;
use App\Models\Navigation\Runway;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RunwayController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Saves a runway model to database
     * @param Request
     * @param Aerodrome
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }
        if (!$request->has('adid')) {
            abort(403, 'Method not allowed');
        }
        $this->authorize('create', Runway::class);

        $validated = $request->validate([
            'rwyIdent' => 'string',
            'rwyHdg' => 'integer',
            'rwyWidth' => 'integer',
            'rwyLength' => 'integer',
            'rwyType' => 'integer|min:1|max:6',
            'rwyThreshold' => 'string|nullable',
        ]);

        $runway = Runway::create([
            'aerodrome_id' => $request->post('adid'),
            'ident' => $validated['rwyIdent'],
            'heading' => $validated['rwyHdg'],
            'threshold' => $validated['rwyThreshold'],
            'width' => $validated['rwyWidth'],
            'length' => $validated['rwyLength'],
            'surface_type' => $validated['rwyType'],
        ]);

        return $runway;
    }

    /**
     * Updates a runway model in database
     * @param Request
     * @param Aerodrome
     * @return Runway
     */
    public function update(Request $request, Aerodrome $aerodrome): Runway
    {
        $this->authorize('update', $aerodrome);

        $validated = $request->validate([
            'rwyId' => 'required|exists:navigation_runways,id',
            'rwyIdent' => 'string',
            'rwyHdg' => 'integer',
            'rwyWidth' => 'integer',
            'rwyLength' => 'integer',
            'rwyType' => 'integer|min:1|max:6',
            'rwyThreshold' => 'string|nullable',
            'rwyOpposite' => 'nullable',
        ]);

        $runway = Runway::findOrFail($validated['rwyId']);
        $runway->update([
            'aerodrome_id' => $aerodrome->id,
            'ident' => $validated['rwyIdent'],
            'heading' => $validated['rwyHdg'],
            'threshold' => $validated['rwyThreshold'],
            'width' => $validated['rwyWidth'],
            'length' => $validated['rwyLength'],
            'surface_type' => $validated['rwyType'],
            'opposite_id' =>
                $validated['rwyOpposite'] > 0 && Runway::where('id', $validated['rwyOpposite'])->exists() ? $validated['rwyOpposite'] : null,
        ]);
        return $runway->fresh();
    }

    public function delete(Request $request, Aerodrome $aerodrome, Runway $runway): bool
    {
        $this->authorize('delete', $runway);

        if ($aerodrome->runways->contains($runway)) {
            $runway->delete();
        }
        return true;
    }
}
