<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use App\Models\ProductRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to view inventory.');
        }

        $raw_materials = DB::table('processor_raw_material_inventory')
            ->where('processor_company_id', $user->company->company_id)
            ->get();

        $finished_goods = DB::table('processor_finished_goods_inventory as pfi')
            ->join('product_recipes as pr', 'pfi.recipe_id', '=', 'pr.recipe_id')
            ->where('processor_company_id', $user->company->company_id)
            ->select(
                'pfi.*',
                'pr.recipe_name',
                'pr.coffee_variety',
                'pr.processing_method'
            )
            ->get();

        $metrics = [
            'total_processing_capacity' => 1000,
            'ready_for_sale_count' => $finished_goods->where('available_stock_units', '>', 0)->count(),
            'total_inventory_value' => $finished_goods->sum(function($item) {
                return $item->available_stock_units * $item->selling_price_per_unit;
            })
        ];

        return view('processor.inventory.index', compact(
            'raw_materials',
            'finished_goods',
            'metrics'
        ));
    }

    public function create()
    {
        return view('processor.inventory.create');
    }

    public function fetchRecipes(Request $request)
{
    \Log::info('Fetch recipes request:', $request->all());
    
    try {
        $validated = $request->validate([
            'product_name' => 'required|in:drinking_coffee,roasted_coffee,coffee_scents,coffee_soap'
        ]);

        $recipes = DB::table('product_recipes')
            ->where('product_name', $validated['product_name'])
            ->select(
                'recipe_id',
                'recipe_name',
                'coffee_variety',
                'processing_method',
                'required_grade',
                'percentage_composition'
            )
            ->get();

        \Log::info('Recipes found:', ['count' => $recipes->count()]);

        return response()->json([
            'success' => true,
            'recipes' => $recipes
        ]);

    } catch (\Exception $e) {
        \Log::error('Recipe fetch error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error loading recipes. Please try again.'
        ], 500);
    }
}

    public function store(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'product_name' => 'required|in:drinking_coffee,roasted_coffee,coffee_scents,coffee_soap',
            'recipe_id' => 'required|exists:product_recipes,recipe_id',
            'product_variant' => 'required|string|max:100',
            'current_stock_units' => 'required|numeric|min:0',
            'production_cost_per_unit' => 'required|numeric|min:0',
            'selling_price_per_unit' => 'required|numeric|min:0',
        ]);
        
        DB::table('processor_finished_goods_inventory')->insert([
            'processor_company_id' => $user->company->company_id,
            'recipe_id' => $validated['recipe_id'],
            'product_name' => $validated['product_name'],
            'product_variant' => $validated['product_variant'],
            'current_stock_units' => $validated['current_stock_units'],
            'reserved_stock_units' => 0,
            'available_stock_units' => $validated['current_stock_units'],
            'production_cost_per_unit' => $validated['production_cost_per_unit'],
            'selling_price_per_unit' => $validated['selling_price_per_unit'],
            'last_updated' => now(),
        ]);
        
        // Deduct raw materials based on recipe
        $recipe = \App\Models\ProductRecipe::find($validated['recipe_id']);
        if ($recipe) {
            // Calculate required raw material in kg
            $required_kg = $validated['current_stock_units'] * ($recipe->percentage_composition / 100);

            $rawMaterial = \App\Models\ProcessorRawMaterialInventory::where([
                'processor_company_id' => $user->company->company_id,
                'coffee_variety' => $recipe->coffee_variety,
                'processing_method' => $recipe->processing_method,
                'grade' => $recipe->required_grade,
            ])->first();

            if ($rawMaterial) {
                $rawMaterial->current_stock_kg -= $required_kg;
                $rawMaterial->available_stock_kg -= $required_kg;
                $rawMaterial->last_updated = now();
                $rawMaterial->save();

                // Low stock warning threshold
                $lowStockThreshold = 50;
                if ($rawMaterial->current_stock_kg < $lowStockThreshold) {
                    // Log a warning (replace with notification logic as needed)
                    \Log::warning('Low stock for raw material:', [
                        'processor_company_id' => $rawMaterial->processor_company_id,
                        'coffee_variety' => $rawMaterial->coffee_variety,
                        'processing_method' => $rawMaterial->processing_method,
                        'grade' => $rawMaterial->grade,
                        'current_stock_kg' => $rawMaterial->current_stock_kg,
                    ]);
                }
            }
        }
        
        return redirect()->route('processor.inventory.index')->with('success', 'Finished good added to inventory successfully.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $item = DB::table('processor_finished_goods_inventory')
            ->where('inventory_id', $id)
            ->where('processor_company_id', $user->company->company_id)
            ->first();

        if (!$item) {
            return redirect()->route('processor.inventory.index')->with('error', 'Inventory item not found or you do not have permission to edit it.');
        }
        
        $product_types = ['drinking_coffee', 'roasted_coffee', 'coffee_scents', 'coffee_soap'];

        return view('processor.inventory.edit', compact('item', 'product_types'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        $item = DB::table('processor_finished_goods_inventory')
            ->where('inventory_id', $id)
            ->where('processor_company_id', $user->company->company_id)
            ->first();

        if (!$item) {
            return redirect()->route('processor.inventory.index')->with('error', 'Inventory item not found or you do not have permission to update it.');
        }

        $validated = $request->validate([
            'product_name' => 'required|in:drinking_coffee,roasted_coffee,coffee_scents,coffee_soap',
            'recipe_id' => 'required|exists:product_recipes,recipe_id',
            'product_variant' => 'required|string|max:100',
            'current_stock_units' => 'required|numeric|min:0',
            'production_cost_per_unit' => 'required|numeric|min:0',
            'selling_price_per_unit' => 'required|numeric|min:0',
        ]);

        DB::table('processor_finished_goods_inventory')
            ->where('inventory_id', $id)
            ->update([
                'recipe_id' => $validated['recipe_id'],
                'product_name' => $validated['product_name'],
                'product_variant' => $validated['product_variant'],
                'current_stock_units' => $validated['current_stock_units'],
                'available_stock_units' => $validated['current_stock_units'], 
                'production_cost_per_unit' => $validated['production_cost_per_unit'],
                'selling_price_per_unit' => $validated['selling_price_per_unit'],
                'last_updated' => now(),
            ]);

        return redirect()->route('processor.inventory.index')->with('success', 'Inventory item updated successfully.');
    }

    public function destroy($id)
    {
        $user = Auth::user();

        // Try to find it in finished goods first
        $item = DB::table('processor_finished_goods_inventory')
            ->where('inventory_id', $id)
            ->where('processor_company_id', $user->company->company_id)
            ->first();

        if ($item) {
            DB::table('processor_finished_goods_inventory')->where('inventory_id', $id)->delete();
            return redirect()->route('processor.inventory.index')->with('success', 'Finished good inventory item deleted successfully.');
        }

        // If not found, try to find it in raw materials
        $raw_material = DB::table('processor_raw_material_inventory')
            ->where('inventory_id', $id)
            ->where('processor_company_id', $user->company->company_id)
            ->first();

        if ($raw_material) {
            DB::table('processor_raw_material_inventory')->where('inventory_id', $id)->delete();
            return redirect()->route('processor.inventory.index')->with('success', 'Raw material inventory item deleted successfully.');
        }

        return redirect()->route('processor.inventory.index')->with('error', 'Inventory item not found or you do not have permission to delete it.');
    }
}