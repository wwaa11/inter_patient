<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceTypeRequest;
use App\Http\Requests\UpdateServiceTypeRequest;
use App\Models\ServiceType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceTypeController extends Controller
{
    public function index(): View
    {
        $serviceTypes = ServiceType::query()->latest()->get();

        return view('service-types.index', compact('serviceTypes'));
    }

    public function create(): View
    {
        return view('service-types.create');
    }

    public function store(StoreServiceTypeRequest $request): RedirectResponse
    {
        ServiceType::create($request->validated());

        return redirect()->route('service-types.index')
            ->with('success', 'Service type created successfully.');
    }

    public function edit(ServiceType $serviceType): View
    {
        return view('service-types.edit', compact('serviceType'));
    }

    public function update(UpdateServiceTypeRequest $request, ServiceType $serviceType): RedirectResponse
    {
        $serviceType->update($request->validated());

        return redirect()->route('service-types.index')
            ->with('success', 'Service type updated successfully.');
    }

    public function destroy(ServiceType $serviceType): RedirectResponse
    {
        $serviceType->delete();

        return redirect()->route('service-types.index')
            ->with('success', 'Service type deleted successfully.');
    }
}
