<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProviderRequest;
use App\Http\Requests\UpdateProviderRequest;
use App\Models\Provider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $providers = Provider::query()->latest()->get();

        return view('providers.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('providers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProviderRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time().'_'.$file->getClientOriginalName();
            $directory = public_path('provider-logos');

            if (! File::exists($directory)) {
                File::makeDirectory($directory, 0777, true);
            }

            $file->move($directory, $filename);
            $validated['logo'] = $filename;
        }

        Provider::create($validated);

        return redirect()->route('providers.index')
            ->with('success', 'Provider created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Provider $provider): View
    {
        return view('providers.show', compact('provider'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Provider $provider): View
    {
        return view('providers.edit', compact('provider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProviderRequest $request, Provider $provider): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($provider->logo) {
                $oldLogoPath = public_path('provider-logos/'.$provider->logo);
                if (File::exists($oldLogoPath)) {
                    File::delete($oldLogoPath);
                }
            }

            $file = $request->file('logo');
            $filename = time().'_'.$file->getClientOriginalName();
            $directory = public_path('provider-logos');

            if (! File::exists($directory)) {
                File::makeDirectory($directory, 0777, true);
            }

            $file->move($directory, $filename);
            $validated['logo'] = $filename;
        }

        $provider->update($validated);

        return redirect()->route('providers.index')
            ->with('success', 'Provider updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Provider $provider): RedirectResponse
    {
        if ($provider->logo) {
            $logoPath = public_path('provider-logos/'.$provider->logo);
            if (File::exists($logoPath)) {
                File::delete($logoPath);
            }
        }

        $provider->delete();

        return redirect()->route('providers.index')
            ->with('success', 'Provider deleted successfully.');
    }
}
