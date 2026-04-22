<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoticeController extends Controller
{
    public function index(): View
    {
        $notices = Notice::orderBy('ordinamento')->orderBy('id', 'desc')->get();

        return view('admin.notices.index', compact('notices'));
    }

    public function create(): View
    {
        $notice = new Notice();

        return view('admin.notices.create', compact('notice'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'date'        => ['required', 'string', 'max:255'],
            'heading'     => ['required', 'string', 'max:255'],
            'copy'        => ['required', 'string'],
            'tag'         => ['nullable', 'string', 'max:100'],
            'ordinamento' => ['integer', 'min:0'],
        ]);

        Notice::create($data);

        return redirect()->route('admin.notices.index')
            ->with('success', 'Avviso creato con successo.');
    }

    public function edit(Notice $notice): View
    {
        return view('admin.notices.edit', compact('notice'));
    }

    public function update(Request $request, Notice $notice): RedirectResponse
    {
        $data = $request->validate([
            'date'        => ['required', 'string', 'max:255'],
            'heading'     => ['required', 'string', 'max:255'],
            'copy'        => ['required', 'string'],
            'tag'         => ['nullable', 'string', 'max:100'],
            'ordinamento' => ['integer', 'min:0'],
        ]);

        $notice->update($data);

        return redirect()->route('admin.notices.index')
            ->with('success', 'Avviso aggiornato.');
    }

    public function destroy(Notice $notice): RedirectResponse
    {
        $notice->delete();

        return redirect()->route('admin.notices.index')
            ->with('success', 'Avviso eliminato.');
    }
}
