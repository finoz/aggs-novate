<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avviso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AvvisoController extends Controller
{
    public function index(): View
    {
        $avvisi = Avviso::orderByDesc('data_pubblicazione')->paginate(20);

        return view('admin.avvisi.index', compact('avvisi'));
    }

    public function create(): View
    {
        return view('admin.avvisi.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'titolo'              => ['required', 'string', 'max:255'],
            'contenuto'           => ['required', 'string'],
            'data_pubblicazione'  => ['required', 'date'],
            'pubblicato'          => ['boolean'],
        ]);

        $data['pubblicato'] = $request->boolean('pubblicato');

        Avviso::create($data);

        return redirect()
            ->route('admin.avvisi.index')
            ->with('success', 'Avviso creato con successo.');
    }

    public function edit(Avviso $avviso): View
    {
        return view('admin.avvisi.edit', compact('avviso'));
    }

    public function update(Request $request, Avviso $avviso): RedirectResponse
    {
        $data = $request->validate([
            'titolo'              => ['required', 'string', 'max:255'],
            'contenuto'           => ['required', 'string'],
            'data_pubblicazione'  => ['required', 'date'],
            'pubblicato'          => ['boolean'],
        ]);

        $data['pubblicato'] = $request->boolean('pubblicato');

        $avviso->update($data);

        return redirect()
            ->route('admin.avvisi.index')
            ->with('success', 'Avviso aggiornato.');
    }

    public function destroy(Avviso $avviso): RedirectResponse
    {
        $avviso->delete();

        return redirect()
            ->route('admin.avvisi.index')
            ->with('success', 'Avviso eliminato.');
    }
}
