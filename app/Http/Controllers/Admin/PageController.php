<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        $pages = Page::orderBy('ordinamento')->orderBy('titolo')->get();

        return view('admin.pages.index', compact('pages'));
    }

    public function create(): View
    {
        $page = new Page();

        return view('admin.pages.create', compact('page'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'slug'        => ['required', 'string', 'max:255', 'unique:pages,slug', 'regex:/^[a-z0-9\-]+$/'],
            'titolo'      => ['required', 'string', 'max:255'],
            'contenuto'   => ['nullable', 'string'],
            'pubblicata'  => ['boolean'],
            'in_menu'     => ['boolean'],
            'ordinamento' => ['integer', 'min:0'],
        ]);

        $data['pubblicata'] = $request->boolean('pubblicata');
        $data['in_menu']    = $request->boolean('in_menu');
        $data['contenuto']  = $data['contenuto'] ? json_decode($data['contenuto'], true) : null;

        Page::create($data);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Pagina creata con successo.');
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $data = $request->validate([
            'slug'        => ['required', 'string', 'max:255', 'unique:pages,slug,' . $page->id, 'regex:/^[a-z0-9\-]+$/'],
            'titolo'      => ['required', 'string', 'max:255'],
            'contenuto'   => ['nullable', 'string'],
            'pubblicata'  => ['boolean'],
            'in_menu'     => ['boolean'],
            'ordinamento' => ['integer', 'min:0'],
        ]);

        $data['pubblicata'] = $request->boolean('pubblicata');
        $data['in_menu']    = $request->boolean('in_menu');
        $data['contenuto']  = $data['contenuto'] ? json_decode($data['contenuto'], true) : null;

        $page->update($data);

        return redirect()->route('admin.pages.edit', $page)
            ->with('success', 'Pagina aggiornata.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $request->validate([
            'order'   => ['required', 'array'],
            'order.*' => ['integer', 'min:0'],
        ]);

        foreach ($request->input('order') as $id => $ord) {
            Page::where('id', (int) $id)->update(['ordinamento' => $ord]);
        }

        return back()->with('success', 'Ordine aggiornato.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Pagina eliminata.');
    }
}
