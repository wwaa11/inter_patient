<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmbassyRequest;
use App\Http\Requests\StoreGuaranteeCaseRequest;
use App\Http\Requests\UpdateEmbassyRequest;
use App\Http\Requests\UpdateGuaranteeCaseRequest;
use App\Models\Embassy;
use App\Models\GuaranteeCase;
use App\Traits\HasRandomColor;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    use HasRandomColor;

    /**
     * Display settings index.
     */
    public function index(): View
    {
        $embassies = Embassy::all();
        $guaranteeCases = GuaranteeCase::all();

        return view('settings.index', compact('embassies', 'guaranteeCases'));
    }

    /**
     * Store a new embassy.
     */
    public function storeEmbassy(StoreEmbassyRequest $request): RedirectResponse
    {
        Embassy::create([
            'name' => $request->name,
            'colour' => $this->randomHexColor(),
        ]);

        return redirect()->route('settings.index')->with('success', 'Embassy added successfully!');
    }

    /**
     * Update an embassy.
     */
    public function updateEmbassy(UpdateEmbassyRequest $request, int $id): RedirectResponse
    {
        $embassy = Embassy::findOrFail($id);
        $embassy->update($request->validated());

        return redirect()->route('settings.index')->with('success', 'Embassy updated successfully!');
    }

    /**
     * Delete an embassy.
     */
    public function destroyEmbassy(int $id): RedirectResponse
    {
        $embassy = Embassy::findOrFail($id);
        $embassy->delete();

        return redirect()->route('settings.index')->with('success', 'Embassy deleted successfully!');
    }

    /**
     * Store a new guarantee case.
     */
    public function storeGuaranteeCase(StoreGuaranteeCaseRequest $request): RedirectResponse
    {
        GuaranteeCase::create([
            'name' => $request->case,
            'definition' => $request->definition,
            'colour' => $this->randomHexColor(),
        ]);

        return redirect()->route('settings.index')->with('success', 'Guarantee case added successfully!');
    }

    /**
     * Update a guarantee case.
     */
    public function updateGuaranteeCase(UpdateGuaranteeCaseRequest $request, int $id): RedirectResponse
    {
        $guaranteeCase = GuaranteeCase::findOrFail($id);
        $guaranteeCase->update([
            'name' => $request->case,
            'definition' => $request->definition,
            'colour' => $request->colour,
        ]);

        return redirect()->route('settings.index')->with('success', 'Guarantee case updated successfully!');
    }

    /**
     * Delete a guarantee case.
     */
    public function destroyGuaranteeCase(int $id): RedirectResponse
    {
        $guaranteeCase = GuaranteeCase::findOrFail($id);
        $guaranteeCase->delete();

        return redirect()->route('settings.index')->with('success', 'Guarantee case deleted successfully!');
    }
}
