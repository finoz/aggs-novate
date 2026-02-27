<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        $page = Page::where('slug', 'home')->firstOrFail();

        return view('pages.show', compact('page'));
    }

    public function show(string $slug): View
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        abort_unless($page->pubblicata, 404);

        return view('pages.show', compact('page'));
    }
}
