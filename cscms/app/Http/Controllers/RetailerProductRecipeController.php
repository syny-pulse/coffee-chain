<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductRecipe;

class RetailerProductRecipeController extends Controller
{
    public function index()
    {
        // Get all product recipes
        $recipes = ProductRecipe::orderBy('created_at', 'desc')->get();
        
        // Get all retailer products with their compositions
        $products = DB::table('retailer_products')->get();

        return view('retailers.product_recipes.index', compact('recipes', 'products'));
    }

    public function create()
    {
        return view('retailers.product_recipes.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|in:' . implode(',', array_keys(ProductRecipe::PRODUCT_NAMES)),
            'recipe_name' => 'required|string|max:100',
            'coffee_variety' => 'required|in:' . implode(',', array_keys(ProductRecipe::COFFEE_VARIETIES)),
            'processing_method' => 'required|in:' . implode(',', array_keys(ProductRecipe::PROCESSING_METHODS)),
            'required_grade' => 'required|in:' . implode(',', array_keys(ProductRecipe::REQUIRED_GRADES)),
            'percentage_composition' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Create the product recipe
            $recipe = ProductRecipe::create($request->all());

            DB::commit();

            return redirect()->route('retailer.product_recipes.index')
                ->with('success', 'Product recipe created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create product recipe: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function show($id)
    {
        $recipe = ProductRecipe::findOrFail($id);
        return view('retailers.product_recipes.show', compact('recipe'));
    }

    public function edit($id)
    {
        $recipe = ProductRecipe::findOrFail($id);
        return view('retailers.product_recipes.edit', compact('recipe'));
    }

    public function update(Request $request, $id)
    {
        $recipe = ProductRecipe::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'product_name' => 'required|in:' . implode(',', array_keys(ProductRecipe::PRODUCT_NAMES)),
            'recipe_name' => 'required|string|max:100',
            'coffee_variety' => 'required|in:' . implode(',', array_keys(ProductRecipe::COFFEE_VARIETIES)),
            'processing_method' => 'required|in:' . implode(',', array_keys(ProductRecipe::PROCESSING_METHODS)),
            'required_grade' => 'required|in:' . implode(',', array_keys(ProductRecipe::REQUIRED_GRADES)),
            'percentage_composition' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $recipe->update($request->all());

            DB::commit();

            return redirect()->route('retailer.product_recipes.index')
                ->with('success', 'Product recipe updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update product recipe: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $recipe = ProductRecipe::findOrFail($id);
            $recipe->delete();

            return redirect()->route('retailer.product_recipes.index')
                ->with('success', 'Product recipe deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete product recipe: ' . $e->getMessage()]);
        }
    }

    // Method to create retailer product with composition (legacy method)
    public function createRetailerProduct(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'product_name' => 'required|string|max:255',
            'price_per_kg' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'components' => 'required|array|min:1',
            'components.*.coffee_breed' => 'required|in:arabica,robusta',
            'components.*.roast_grade' => 'required|in:Grade 1,Grade 2,Grade 3,Grade 4,Grade 5',
            'components.*.percentage' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check total percentage
        $totalPercentage = collect($data['components'])->sum('percentage');
        if ($totalPercentage != 100) {
            return redirect()->back()->withErrors(['components' => 'Total percentage of components must be 100%.'])->withInput();
        }

        try {
            DB::beginTransaction();

            // Insert product
            $productId = DB::table('retailer_products')->insertGetId([
                'name' => $data['product_name'],
                'price_per_kg' => $data['price_per_kg'],
                'description' => $data['description'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert components
            foreach ($data['components'] as $component) {
                DB::table('product_composition')->insert([
                    'product_id' => $productId,
                    'coffee_breed' => $component['coffee_breed'],
                    'roast_grade' => $component['roast_grade'],
                    'percentage' => $component['percentage'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('retailer.product_recipes.index')->with('success', 'Product recipe created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create product: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
