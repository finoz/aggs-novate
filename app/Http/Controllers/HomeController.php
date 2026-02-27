<?php

namespace App\Http\Controllers;

use App\Models\Avviso;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Mostra gli ultimi 3 avvisi pubblicati nella home
        $avvisi = Avviso::publicati()
            ->orderByDesc('data_pubblicazione')
            ->limit(3)
            ->get();

        return view('home', compact('avvisi'));
    }
}
