<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RetailerProductRecipeController extends Controller
{
    public function index()
    {
        // Get all products with their compositions
        $products = DB::table('retailer_products')->get();

        return view('retailers.product_recipes.index', compact('products'));
    }

    public function create()
    {
        return view('retailers.product_recipes.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
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

        // Insert product
        $productId = DB::table('retailer_products')->insertGetId([
            'product_name' => $data['product_name'],
            'price' => $data['price'],
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

        return redirect()->route('retailer.product_recipes.index')->with('success', 'Product recipe created successfully.');
    }
}
