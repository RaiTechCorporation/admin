<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Media;
use App\Http\Resources\MediaResource;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'folder' => 'nullable|string',
            'disk' => 'nullable|string|in:public,s3',
        ]);

        $file = $request->file('file');
        $disk = $request->input('disk', 'public');
        $folder = $request->input('folder', 'media');

        $path = $file->store($folder, $disk);

        $media = Media::create([
            'path' => $path,
            'disk' => $disk,
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        return new MediaResource($media);
    }

    public function show($id)
    {
        $media = Media::findOrFail($id);
        return new MediaResource($media);
    }
}
