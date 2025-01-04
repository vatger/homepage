<?php

namespace App\Http\Controllers\Administration\Content;

use App\Http\Controllers\Controller;
use App\Models\Filebase\MediaFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->authorize('media.viewAny');

        $media = MediaFile::paginate(15);

        return view('administration.content.media.index')->with(['media' => $media]);
    }

    public function show(Request $request, string $mediaFilePath)
    {
        $mediaFile = MediaFile::where('link', str_replace('_', ' ', $mediaFilePath))->firstOrFail();

        $this->authorize('media.view', $mediaFile);

        $fullpath = storage_path('app').'/'.$mediaFile->path;
        if (Storage::exists($mediaFile->path)) {
            $file = File::get($fullpath);
            $type = File::mimeType($fullpath);

            $response = Response::make($file, 200);
            $response->header('Content-Type', $type);

            return $response;
        }
        abort(404, 'File not found on disk.');
    }

    public function showPublic(Request $request, string $mediaFilePath)
    {
        $mediaFile = MediaFile::where('link', str_replace('_', ' ', $mediaFilePath))->firstOrFail();

        $fullpath = storage_path('app').'/'.$mediaFile->path;
        if ($mediaFile->approved && Storage::exists($mediaFile->path)) {
            $file = File::get($fullpath);
            $type = File::mimeType($fullpath);

            $response = Response::make($file, 200);
            $response->header('Content-Type', $type);

            return $response;
        }
        abort(403, $mediaFile->approved ? 'File not found' : 'File not approved by administration.');
    }

    public function store(Request $request)
    {
        if (! $request->ajax()) {
            abort(403, 'Method not supported');
        }

        $this->authorize('media.create');

        $request->validate([
            'mediaName' => 'required|string',
            'mediaFile' => 'required|file|max:10000',
            'mediaLicense' => 'required|accepted',
        ]);

        $rngname = $request->file('mediaFile')->hashName();
        $fileext = $request->file('mediaFile')->extension();

        $link = Carbon::now()->utc()->timestamp.$rngname;

        $path = Storage::putFileAs('media/'.$this->_user->id, $request->file('mediaFile'), $rngname);

        $mediaFile = MediaFile::create([
            'user_id' => $this->_user->id,
            'path' => $path,
            'name' => $request->mediaName,
            'ext' => $fileext,
            'link' => $link,
            'approved' => true, // AUTO APPROVE until we need to change it
        ]);

        return json_encode($mediaFile);
    }

    public function update(Request $request, MediaFile $mediaFile)
    {
        if (! $request->ajax()) {
            abort(403, 'Method not supported');
        }

        $this->authorize('media.update', $mediaFile);

        if (! Storage::exists($mediaFile->path)) {
            Storage::delete($mediaFile->path);
            $mediaFile->delete();

            return json_encode(['message' => 'Datei nicht gefunden. Datenbankeintrag gelöscht!']);
        }

        if ($request->has('toggleStatus')) {
            $mediaFile->approved = ! $mediaFile->approved;
        }

        $mediaFile->save();

        return json_encode(['message' => 'Datenbankeintrag bearbeitet.']);
    }

    public function delete(Request $request, MediaFile $mediaFile)
    {
        if (! $request->ajax()) {
            abort(403, 'Method not supported');
        }

        $this->authorize('media.delete', $mediaFile);

        if (Storage::exists($mediaFile->path)) {
            Storage::delete($mediaFile->path);
        }
        $mediaFile->delete();

        return json_encode(true);
    }

    public function getMediaPaginated(Request $request)
    {
        if (! $request->ajax()) {
            abort(403, 'Method not supported');
        }

        $this->authorize('media.viewAny');

        return MediaFile::paginate(15);
    }

    public function getMediaBySearch(Request $request)
    {
        if (! $request->ajax()) {
            abort(403, 'Method not supported');
        }

        $this->authorize('media.viewAny');

        return MediaFile::where('name', 'LIKE', '%'.$request->get('search_param').'%')->get();
    }
}
