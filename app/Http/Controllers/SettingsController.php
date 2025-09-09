<?php
namespace App\Http\Controllers;

use App\Models\Embassy;
use App\Models\GuaranteeCase;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private function randomHexColour()
    {
        return '#' . substr(str_shuffle('0123456789abcdef'), 0, 6);
    }

    public function index()
    {
        $embassies      = Embassy::all();
        $guaranteeCases = GuaranteeCase::all();

        return view('settings.index', compact('embassies', 'guaranteeCases'));
    }

    // Embassy CRUD methods
    public function storeEmbassy(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Embassy::create([
            'name'   => $request->name,
            'colour' => $this->randomHexColour(),
        ]);

        return redirect()->route('settings.index')->with('success', 'Embassy added successfully!');
    }

    public function updateEmbassy(Request $request, $id)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'colour' => 'nullable|string|max:255',
        ]);

        $embassy = Embassy::findOrFail($id);
        $embassy->update([
            'name'   => $request->name,
            'colour' => $request->colour,
        ]);

        return redirect()->route('settings.index')->with('success', 'Embassy updated successfully!');
    }

    public function destroyEmbassy($id)
    {
        $embassy = Embassy::findOrFail($id);
        $embassy->delete();

        return redirect()->route('settings.index')->with('success', 'Embassy deleted successfully!');
    }

    // Guarantee Case CRUD methods
    public function storeGuaranteeCase(Request $request)
    {
        $request->validate([
            'case'       => 'required|string|max:255',
            'definition' => 'nullable|string|max:255',
        ]);

        GuaranteeCase::create([
            'name'       => $request->case,
            'definition' => $request->definition,
            'colour'     => $this->randomHexColour(),
        ]);

        return redirect()->route('settings.index')->with('success', 'Guarantee case added successfully!');
    }

    public function updateGuaranteeCase(Request $request, $id)
    {
        $request->validate([
            'case'       => 'required|string|max:255',
            'definition' => 'nullable|string|max:255',
            'colour'     => 'nullable|string|max:255',
        ]);

        $guaranteeCase = GuaranteeCase::findOrFail($id);
        $guaranteeCase->update([
            'name'       => $request->case,
            'definition' => $request->definition,
            'colour'     => $request->colour,
        ]);

        return redirect()->route('settings.index')->with('success', 'Guarantee case updated successfully!');
    }

    public function destroyGuaranteeCase($id)
    {
        $guaranteeCase = GuaranteeCase::findOrFail($id);
        $guaranteeCase->delete();

        return redirect()->route('settings.index')->with('success', 'Guarantee case deleted successfully!');
    }
}
