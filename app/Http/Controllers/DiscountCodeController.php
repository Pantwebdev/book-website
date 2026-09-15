<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use App\Models\Product;
use App\Models\Tax;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    public function index()
    {
        $discountCodes = DiscountCode::latest()->get();
        $products = Product::all();
        return view('admin.discount-codes.index', compact('discountCodes', 'products'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:discount_codes,code',
            'type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'usage_limit' => 'nullable|integer|min:1',
        ]);

        DiscountCode::create($request->only([
            'code', 'type', 'discount_value', 'valid_from', 'valid_until', 'usage_limit',
        ]));

        return redirect()->route('discount-codes.index')->with('success', 'Discount code created successfully!');
    }


    public function edit($id)
    {
        $discountCode = DiscountCode::findOrFail($id);
        $products = Product::all();

        return view('admin.discount-codes.edit', compact('discountCode', 'products'));
    }

    public function update(Request $request, DiscountCode $discountCode)
    {
        $request->validate([
            'code' => 'required|string|unique:discount_codes,code,' . $discountCode->id,
            'type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'usage_limit' => 'nullable|integer|min:1',
        ]);

        $discountCode->update($request->only([
            'code',
            'type',
            'discount_value',
            'valid_from',
            'valid_until',
            'usage_limit',
            'is_active',
        ]));

        // ❌ Removed products()->sync() since discount applies to all products

        return redirect()->route('discount-codes.index')
            ->with('success', 'Discount code updated successfully!');
    }



    public function destroy($id)
    {
        $discountCode = DiscountCode::findOrFail($id);

        $discountCode->delete();

        return redirect()->route('discount-codes.index')->with('success', 'Discount code deleted successfully!');
    }
    public function applyDiscount(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_code' => 'required|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $discountCode = DiscountCode::where('code', $request->discount_code)->first();

        if (!$discountCode) {
            return response()->json(['success' => false, 'message' => 'Invalid discount code']);
        }

        if (!$discountCode->isValid()) {
            return response()->json(['success' => false, 'message' => 'Discount code is not valid or expired']);
        }

        $originalPrice = $product->display_price ?? $product->mrp_price;
        $discountedPrice = $discountCode->applyDiscount($originalPrice);

        return response()->json([
            'success' => true,
            'original_price' => $originalPrice,
            'discounted_price' => $discountedPrice,
            'discount_amount' => $originalPrice - $discountedPrice,
            'discount_type' => $discountCode->type,
            'discount_value' => $discountCode->discount_value,
        ]);
    }

    public function tax()
    {
        $taxCodes = Tax::latest()->get();
        $products = Product::all();
        return view('admin.discount-codes.taxindex', compact('taxCodes', 'products'));
    }


    public function storetax(Request $request)
    {
        $request->validate([

            'type' => 'required|in:1,2',
            'tax' => 'required|numeric|min:0',

        ]);

        Tax::create($request->only([
            'type', 'tax',
        ]));

        return redirect()->route('tax')->with('success', 'Tax created successfully!');
    }
    public function getTax($id)
    {
        $tax = Tax::findOrFail($id);
        return response()->json($tax);
    }

    public function updatetax(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:1,2',
            'tax' => 'required|numeric|min:0',
        ]);

        $tax = Tax::findOrFail($id);
        $tax->update([
            'type' => $request->type,
            'tax' => $request->tax,
        ]);

        return redirect()->route('tax')->with('success', 'Tax updated successfully!');
    }


    public function destroytax($id)
    {
        $TaxCode = Tax::findOrFail($id);

        $TaxCode->delete();

        return redirect()->route('tax')->with('success', 'Tax deleted successfully!');
    }
}
