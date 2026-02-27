<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    public function chiSiamo(): View
    {
        return view('pages.chi-siamo');
    }

    public function contatti(): View
    {
        return view('pages.contatti');
    }
}
