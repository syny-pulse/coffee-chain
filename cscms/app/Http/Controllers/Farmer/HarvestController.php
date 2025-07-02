<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Farmer\FarmerHarvest;

class HarvestController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;
        $harvests = FarmerHarvest::where('company_id', $company->company_id)->orderByDesc('harvest_date')->get();
        return view('farmers.harvests.index', compact('harvests'));
    }

    public function create()
    {
        return view('farmers.harvests.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;
        $request->validate([
            'coffee_variety' => 'required|string',
            'processing_method' => 'required|string',
            'grade' => 'required|string',
            'quantity_kg' => 'required|numeric|min:0',
            'available_quantity_kg' => 'required|numeric|min:0',
            'harvest_date' => 'required|date',
            'quality_notes' => 'nullable|string',
        ]);
        if ($request->available_quantity_kg > $request->quantity_kg) {
            return back()->withErrors(['available_quantity_kg' => 'Available quantity must be less than or equal to total quantity.'])->withInput();
        }
        $status = ($request->available_quantity_kg == 0) ? 'unavailable' : 'available';
        FarmerHarvest::create([
            'company_id' => $company->company_id,
            'coffee_variety' => $request->coffee_variety,
            'processing_method' => $request->processing_method,
            'grade' => $request->grade,
            'quantity_kg' => $request->quantity_kg,
            'available_quantity_kg' => $request->available_quantity_kg,
            'harvest_date' => $request->harvest_date,
            'quality_notes' => $request->quality_notes,
            'availability_status' => $status,
        ]);
        return redirect()->route('farmers.harvests.index')->with('success', 'Harvest recorded successfully.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $company = $user->company;
        $harvest = FarmerHarvest::where('company_id', $company->company_id)->findOrFail($id);
        return view('farmers.harvests.edit', compact('harvest'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $company = $user->company;
        $request->validate([
            'coffee_variety' => 'required|string',
            'processing_method' => 'required|string',
            'grade' => 'required|string',
            'quantity_kg' => 'required|numeric|min:0',
            'available_quantity_kg' => 'required|numeric|min:0',
            'harvest_date' => 'required|date',
            'quality_notes' => 'nullable|string',
        ]);
        if ($request->available_quantity_kg > $request->quantity_kg) {
            return back()->withErrors(['available_quantity_kg' => 'Available quantity must be less than or equal to total quantity.'])->withInput();
        }
        $harvest = FarmerHarvest::where('company_id', $company->company_id)->findOrFail($id);
        $status = ($request->available_quantity_kg == 0) ? 'unavailable' : 'available';
        $harvest->update([
            'coffee_variety' => $request->coffee_variety,
            'processing_method' => $request->processing_method,
            'grade' => $request->grade,
            'quantity_kg' => $request->quantity_kg,
            'available_quantity_kg' => $request->available_quantity_kg,
            'harvest_date' => $request->harvest_date,
            'quality_notes' => $request->quality_notes,
            'availability_status' => $status,
        ]);
        return redirect()->route('farmers.harvests.index')->with('success', 'Harvest updated successfully.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $company = $user->company;
        $harvest = FarmerHarvest::where('company_id', $company->company_id)->findOrFail($id);
        $harvest->delete();
        return redirect()->route('farmers.harvests.index')->with('success', 'Harvest deleted successfully.');
    }

    public function show($id)
    {
        $user = Auth::user();
        $company = $user->company;
        $harvest = FarmerHarvest::where('company_id', $company->company_id)->findOrFail($id);
        return view('farmers.harvests.show', compact('harvest'));
    }
}