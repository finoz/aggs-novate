<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UploadedImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'image', 'max:10240'],
        ]);

        $record = UploadedImage::create();

        $media = $record
            ->addMediaFromRequest('file')
            ->toMediaCollection('images');

        $url = $media->hasGeneratedConversion('medium')
            ? $media->getUrl('medium')
            : $media->getUrl();

        return response()->json([
            'url'   => $url,
            'thumb' => $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : $url,
        ]);
    }
}
