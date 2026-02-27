<?php

namespace App\Http\Controllers;

use App\Models\Avviso;
use Illuminate\View\View;

class AvvisoController extends Controller
{
    public function index(): View
    {
        $avvisi = Avviso::publicati()
            ->orderByDesc('data_pubblicazione')
            ->paginate(10);

        return view('avvisi.index', compact('avvisi'));
    }

    public function show(Avviso $avviso): View
    {
        // Blocca l'accesso agli avvisi non pubblicati
        abort_unless($avviso->pubblicato, 404);

        return view('avvisi.show', compact('avviso'));
    }
}
