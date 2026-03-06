<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNotifierRequest;
use App\Http\Requests\UpdateNotifierRequest;
use App\Models\Notifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotifierController extends Controller
{
    public function index(): View
    {
        $notifiers = Notifier::query()->latest()->get();

        return view('notifiers.index', compact('notifiers'));
    }

    public function create(): View
    {
        return view('notifiers.create');
    }

    public function store(StoreNotifierRequest $request): RedirectResponse
    {
        Notifier::create($request->validated());

        return redirect()->route('notifiers.index')
            ->with('success', 'Notifier created successfully.');
    }

    public function edit(Notifier $notifier): View
    {
        return view('notifiers.edit', compact('notifier'));
    }

    public function update(UpdateNotifierRequest $request, Notifier $notifier): RedirectResponse
    {
        $notifier->update($request->validated());

        return redirect()->route('notifiers.index')
            ->with('success', 'Notifier updated successfully.');
    }

    public function destroy(Notifier $notifier): RedirectResponse
    {
        $notifier->delete();

        return redirect()->route('notifiers.index')
            ->with('success', 'Notifier deleted successfully.');
    }
}
