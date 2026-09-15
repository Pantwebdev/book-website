<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\slider;
use App\Models\Category;
use App\Models\Sizecolor;
use App\Models\CartItem;
use App\Models\Subcategory;
use App\Models\ChildSubcategory;
use App\Models\Product;
use App\Models\Link;
use App\Models\DiscountCode;
use App\Models\CheckAvailability;
use App\Models\Wishlist;
use App\Models\Review;
use App\Models\Tax;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MasterOrder;
use App\Models\MasterOrderItem;
use App\Models\Blog;
use App\Models\Setting;
use App\Models\SimplePage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Seo;

class HomeController extends Controller
{
    public function index()
    {
        $navcategories = Category::with(['subcategories.childSubcategories'])->where('status', 1)->get();
        $linkss = Link::where('status', 1)->get();
        $Homeslider = slider::where('type', 1)->where('status', 1)->get();
        $categories = Subcategory::with('category')->where('show_collaj', 1)->where('status', 1)->take(5)->get();
        $ShopbyCollection = ChildSubcategory::where('show_collection', 1)->where('status', 1)->take(4)->get();
        $newarrivals = Product::where('new_arrivals', 1)->where('status', 1)->with(['reviews'])->get()
        ->map(function ($product) {
            $reviews = $product->reviews;
            $totalReviews = $reviews->count();
            $avgRating = $totalReviews > 0 ? $reviews->avg('rating') : 0;
            $product->total_reviews = $totalReviews;
            $product->avg_rating = $avgRating;
            return $product;
        });
        $product_on_sale = Product::where('product_on_sale', 1)->where('status', 1)->with(['reviews'])->paginate(10)
        ->through(function ($product) {
            $reviews = $product->reviews;
            $totalReviews = $reviews->count();
            $avgRating = $totalReviews > 0 ? $reviews->avg('rating') : 0;
            $product->total_reviews = $totalReviews;
            $product->avg_rating = $avgRating;
            return $product;
        });
        $ExamCorner = Product::where('exam_corner', 1)->where('status', 1)->with(['reviews'])->get()
        ->map(function ($product) {
            $reviews = $product->reviews;
            $totalReviews = $reviews->count();
            $avgRating = $totalReviews > 0 ? $reviews->avg('rating') : 0;
            $product->total_reviews = $totalReviews;
            $product->avg_rating = $avgRating;
            return $product;
        });
        $FeaturedBook = Product::where('featured_book', 1)->where('status', 1)->with(['reviews'])->get()
        ->map(function ($product) {
            $reviews = $product->reviews;
            $totalReviews = $reviews->count();
            $avgRating = $totalReviews > 0 ? $reviews->avg('rating') : 0;
            $product->total_reviews = $totalReviews;
            $product->avg_rating = $avgRating;
            return $product;
        });
        $shopbytrend = slider::where('type', 2)->where('status', 1)->get();
        $explorecategories = Subcategory::where('show_explore', 1)->where('status', 1)->take(4)->get();
        $explorecategories = Subcategory::with('category')->whereHas('category')->where('show_explore', 1)->where('status', 1)->take(4)->get();
        $colors = Sizecolor::where('type', 1)->where('status', 1)->get();
        $sizes = Sizecolor::where('type', 2)->where('status', 1)->get();
        $banner1 = Setting::where('type', 1)->first();
        $banner2 = Setting::where('type', 2)->first();
        $banner1_link = null;
        if ($banner1 && !empty($banner1->url)) {
            $category = Category::find($banner1->url);
            if ($category) {
                $banner1_link = route('category.products', $category->slug);
            }
        }
        $banner2_link = null;
        if ($banner2 && !empty($banner2->url)) {
            $subcategory = Subcategory::find($banner2->url);
            if ($subcategory) {

                $parentCategory = $subcategory->category;
                if ($parentCategory) {
                    $banner2_link = route('subcategory.products', [
                        'category' => $parentCategory->slug,
                        'subcategory' => $subcategory->slug,
                    ]);
                }
            }
        }
        $meta = Seo::where('meta_slug', '/')->first();

        $metatitle = null;
        $canonicalurl = null;
        $metakeyword = null;
        $metadescription = null;

        if ($meta) {
            $metatitle = $meta->meta_title;
            $canonicalurl = $meta->canonical_url;
            $metakeyword = $meta->meta_keyword;
            $metadescription = $meta->meta_description;
        }
        $BlogData = Blog::where('status', 1)->orderBy('created_at', 'desc')->take(4)->get();

        return view('index', compact(
            'Homeslider',
            'categories',
            'navcategories',
            'newarrivals',
            'product_on_sale',
            'ExamCorner',
            'FeaturedBook',
            'shopbytrend',
            'ShopbyCollection',
            'explorecategories',
            'linkss',
            'colors',
            'sizes',
            'banner1',
            'banner2',
            'banner1_link',
            'banner2_link',
            'metatitle',
            'meta',
            'canonicalurl',
            'metakeyword',
            'metadescription',
            'BlogData',
        ));
    }


    public function categoryProducts(Request $request, $category)
    {
        return $this->handleInitialPageLoad($request, $category);
    }

    public function subCategoryProducts(Request $request, $category, $subcategory)
    {
        return $this->handleInitialPageLoad($request, $category, $subcategory);
    }

    public function childCategoryProducts(Request $request, $category, $subcategory, $child)
    {
        return $this->handleInitialPageLoad($request, $category, $subcategory, $child);
    }


    private function handleInitialPageLoad(Request $request, $category, $subcategory = null, $child = null)
    {
        try {

            $categoryData = Category::where('slug', $category)->firstOrFail();
            $subcatData = $subcategory ? Subcategory::where('slug', $subcategory)->firstOrFail() : null;
            $childData = $child ? ChildSubcategory::where('slug', $child)->firstOrFail() : null;
            $linkss = Link::where('status', 1)->get();
            $banner = Setting::where('type', 3)->first();
            $query = Product::query();
            if ($childData) {
                $query->where('child_sub_category_id', $childData->id);
            } elseif ($subcatData) {
                $query->where('sub_category_id', $subcatData->id);
            } else {
                $query->where('category_id', $categoryData->id);
            }
            $query->orderBy('created_at', 'desc');
            $totalCount = $query->count();
            $perPage = $request->get('per_page', 12);
            if ($perPage === 'all') {
                $perPage = $totalCount;
            }
            $products = $query->with(['reviews'])->paginate($perPage);
            $products->transform(function ($product) {
                $reviews = $product->reviews;
                $totalReviews = $reviews->count();
                $avgRating = $totalReviews > 0 ? $reviews->avg('rating') : 0;
                $product->total_reviews = $totalReviews;
                $product->avg_rating = $avgRating;
                return $product;
            });
            $navcategories = Category::with(['subcategories.childSubcategories'])->where('status', 1)->get();
            $colors = Sizecolor::where('type', 1)->where('status', 1)->get();
            $sizes = Sizecolor::where('type', 2)->where('status', 1)->get();
            return view('product', compact(
                'products',
                'navcategories',
                'categoryData',
                'subcatData',
                'childData',
                'colors',
                'sizes',
                'linkss',
                'banner',
            ));

        } catch (\Exception $e) {
            \Log::error('Product listing error: ' . $e->getMessage());
            abort(404);
        }
    }

    public function filterProducts(Request $request)
    {
        try {
            $query = Product::query();


            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }
            if ($request->filled('sub_category_id')) {
                $query->where('sub_category_id', $request->sub_category_id);
            }
            if ($request->filled('child_sub_category_id')) {
                $query->where('child_sub_category_id', $request->child_sub_category_id);
            }
            $this->applyFilters($query, $request);
            $totalCount = $query->count();
            $perPage = $request->get('per_page', 10);
            if ($perPage === 'all') {
                $perPage = $totalCount;
            }
            $products = $query->with(['reviews'])->paginate($perPage);
            $products->transform(function ($product) {
                $reviews = $product->reviews;
                $totalReviews = $reviews->count();
                $avgRating = $totalReviews > 0 ? $reviews->avg('rating') : 0;
                $product->total_reviews = $totalReviews;
                $product->avg_rating = $avgRating;
                return $product;
            });

            $html = view('partials.product_list', compact('products'))->render();
            return response()->json([
                'status' => 'success',
                'html' => $html,
                'total' => $products->total(),
                'showing' => $products->count(),
                'current_page' => $products->currentPage(),
                'per_page' => $perPage,
                'last_page' => $products->lastPage(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Filter products error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'html' => '<p>Error loading products. Please try again.</p>',
            ], 500);
        }
    }
    private function applyFilters($query, Request $request)
    {
        // Category Filter
        if ($request->filled('categories')) {
            $categories = $request->categories;

            if (!is_array($categories)) {
                $categories = [$categories];
            }

            $categories = array_filter($categories);

            if (!empty($categories)) {
                $query->whereIn('category_id', $categories);
            }
        }

        // Price Filter
        if ($request->filled('max_price')) {
            $maxPrice = $request->max_price;
            if ($maxPrice != 10000) {
                $query->where('display_price', '<=', $maxPrice);
            }
        }

        switch ($request->sort) {
            case 'lowToHigh':
                $query->orderBy('display_price', 'asc');
                break;
            case 'highToLow':
                $query->orderBy('display_price', 'desc');
                break;
            case 'nameAsc':
                $query->orderBy('name', 'asc');
                break;
            case 'nameDesc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query;
    }
    // private function applyFilters($query, Request $request)
    // {

    //     if ($request->filled('max_price')) {
    //         $maxPrice = $request->max_price;
    //         if ($maxPrice != 10000) {
    //             $query->where('display_price', '<=', $maxPrice);
    //         }
    //     }
    //     if ($request->filled('colors')) {
    //         $colors = $request->colors;

    //         if (!is_array($colors)) {
    //             $colors = [$colors];
    //         }
    //         $colors = array_filter($colors);

    //         if (!empty($colors)) {
    //             $query->where(function ($q) use ($colors) {
    //                 foreach ($colors as $colorId) {
    //                     $q->orWhereJsonContains('colors', (int)$colorId);
    //                 }
    //             });
    //         }
    //     }
    //     if ($request->filled('sizes')) {
    //         $sizes = $request->sizes;

    //         if (!is_array($sizes)) {
    //             $sizes = [$sizes];
    //         }

    //         $sizes = array_filter($sizes);

    //         if (!empty($sizes)) {
    //             $query->where(function ($q) use ($sizes) {
    //                 foreach ($sizes as $sizeId) {
    //                     $q->orWhereJsonContains('sizes', (int)$sizeId);
    //                 }
    //             });
    //         }
    //     }
    //     switch ($request->sort) {
    //         case 'lowToHigh':
    //             $query->orderBy('display_price', 'asc');
    //             break;
    //         case 'highToLow':
    //             $query->orderBy('display_price', 'desc');
    //             break;
    //         case 'nameAsc':
    //             $query->orderBy('name', 'asc');
    //             break;
    //         case 'nameDesc':
    //             $query->orderBy('name', 'desc');
    //             break;
    //         default:
    //             $query->orderBy('created_at', 'desc');
    //             break;
    //     }

    //     return $query;
    // }


    public function addToCart(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|integer|exists:products,id',
                'qty'        => 'required|integer|min:1',
                'color'      => 'nullable|string',
                'size'       => 'nullable|string',
            ]);

            $product = Product::findOrFail($request->product_id);
            // --- Stock validation start ---
            $requestedQty = $request->qty;
            if (Auth::guard('customer')->check()) {
                $userId = Auth::guard('customer')->id();

                $guestToken = null;
            } else {
                $userId = null;
                $guestToken = $this->getGuestToken();
            }

            $existingCartItem = CartItem::when($userId, function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->when($guestToken, function ($query) use ($guestToken) {
                return $query->where('guest_token', $guestToken);
            })
            ->where('product_id', $product->id)->where('color', $request->color)->where('size', $request->size)->where('status', 'active') ->first();

            $currentQtyInCart = $existingCartItem ? $existingCartItem->qty : 0;
            $totalRequestedQty = $currentQtyInCart + $requestedQty;

            if ($totalRequestedQty > $product->stock) {
                $message = 'Only ' . $product->stock . ' item(s) available in stock.';
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                    ], 400);
                }
                return redirect()->back()->with('error', $message);
            }
            // --- Stock validation end ---
            if ($existingCartItem) {

                $existingCartItem->update([
                    'qty' => $existingCartItem->qty + $request->qty,
                    'name' => $product->name,
                    'sku' => $product->sku,
                ]);
            } else {

                CartItem::create([
                    'user_id' => $userId,
                    'guest_token' => $guestToken,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'qty' => $request->qty,
                    'price' => $product->display_price ?? $product->mrp_price,
                    'mrp_price' => $product->mrp_price,
                    'discount' => $product->discount ?? 0,
                    'color' => $request->color,
                    'size' => $request->size,
                    'image' => $request->image ?? $product->image,
                    'status' => 'active',
                ]);
            }


            if (Auth::guard('customer')->check()) {
                $cartCount = CartItem::where('user_id', Auth::guard('customer')->id())
                    ->where('status', 'active')
                    ->sum('qty');
            } else {
                $guestToken = Cookie::get('guest_token');
                $cartCount = $guestToken ? CartItem::where('guest_token', $guestToken)
                    ->where('status', 'active')
                    ->sum('qty') : 0;
            }


            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $product->name . ' added to cart successfully!',
                    'cart_count' => $cartCount,
                ]);
            }

            return redirect()->route('cart.view')->with('success', $product->name . ' added to cart successfully!');

        } catch (\Exception $e) {
            Log::error('Add to cart error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error adding product to cart. Please try again.',
                ], 500);
            }

            return redirect()->back()->with('error', 'Error adding product to cart.');
        }
    }


    private function getGuestToken()
    {
        $guestToken = Cookie::get('guest_token');
        if (!$guestToken) {
            $guestToken = Str::random(32);
            Cookie::queue('guest_token', $guestToken, 60 * 24 * 365 * 10);
        }
        return $guestToken;
    }


    public function updateCart(Request $request, $id)
    {
        Log::info('Update cart request:', ['id' => $id, 'data' => $request->all()]);

        try {
            $request->validate([
                'qty' => 'required|integer|min:1|max:100',
            ]);


            if (Auth::guard('customer')->check()) {
                $cartItem = CartItem::where('id', $id)
                ->where('user_id', Auth::guard('customer')->id())
                    ->where('status', 'active')
                    ->first();
            } else {
                $guestToken = Cookie::get('guest_token');
                $cartItem = $guestToken ? CartItem::where('id', $id)
                    ->where('guest_token', $guestToken)
                    ->where('status', 'active')
                    ->first() : null;
            }

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found or no longer exists',
                ], 404);
            }

            // --- Stock validation start ---
            $product = Product::find($cartItem->product_id);
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found.',
                ], 404);
            }

            if ($request->qty > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only ' . $product->stock . ' item(s) available in stock.',
                ], 400);
            }
            // --- Stock validation end ---


            $cartItem->update(['qty' => $request->qty]);


            if (Auth::guard('customer')->check()) {
                $cartItems = CartItem::where('user_id', Auth::guard('customer')->id())
                    ->where('status', 'active')
                    ->get();
                $cartCount = CartItem::where('user_id', Auth::guard('customer')->id())
                    ->where('status', 'active')
                    ->sum('qty');
            } else {
                $guestToken = Cookie::get('guest_token');
                $cartItems = $guestToken ? CartItem::where('guest_token', $guestToken)
                    ->where('status', 'active')
                    ->get() : collect();
                $cartCount = $guestToken ? CartItem::where('guest_token', $guestToken)
                    ->where('status', 'active')
                    ->sum('qty') : 0;
            }


            // $gstTax = Tax::getByType(Tax::TYPE_GST);
            // $otherTax = Tax::getByType(Tax::TYPE_OTHER);


            $subtotal = 0;
            $totalMRP = 0;
            $itemCount = 0;

            foreach ($cartItems as $item) {
                $itemSubtotal = $item->price * $item->qty;
                $subtotal += $itemSubtotal;
                $totalMRP += $item->mrp_price * $item->qty;
                $itemCount += $item->qty;
            }

            $totalDiscount = $totalMRP - $subtotal;


            $couponDiscount = session('applied_coupon.discount', 0);
            $subtotalAfterCoupon = max(0, $subtotal - $couponDiscount);


            // $gstTaxAmount = 0;
            // $otherTaxAmount = 0;

            // if ($gstTax) {
            //     $gstTaxAmount = ($subtotalAfterCoupon * $gstTax->tax) / 100;
            // }
            // if ($otherTax) {
            //     $otherTaxAmount = ($subtotalAfterCoupon * $otherTax->tax) / 100;
            // }
            $totalShipping = 0;
            foreach ($cartItems as $item) {
                $prod = $item->product ?? Product::find($item->product_id);
                if ($prod && $prod->shipping_type == 1) {
                    $totalShipping += ($prod->shipping_charge ?? 0) * $item->qty;
                }
            }
            //$totalTaxAmount = $gstTaxAmount + $otherTaxAmount;
            $finalGrandTotal = $subtotalAfterCoupon + $totalShipping;

            Log::info('Cart update successful', [
                'item_id' => $id,
                'new_qty' => $request->qty,
                'grand_total' => $finalGrandTotal,
                'cart_count' => $cartCount,
            ]);

            return response()->json([
                'success' => true,
                'cart_count' => $cartCount,
                'item_total' => $cartItem->price * $request->qty,
                'item_mrp' => $cartItem->mrp_price * $request->qty,
                'subtotal' => $subtotal,
                'total_mrp' => $totalMRP,
                'total_discount' => $totalDiscount,
                'item_count' => $itemCount,
                'shipping_charge'      => $totalShipping,
                'grand_total_with_tax' => $finalGrandTotal,
                'coupon_discount' => $couponDiscount,

                'message' => 'Quantity updated successfully',
            ]);

        } catch (\Exception $e) {
            Log::error('Cart update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unable to update cart. Please try again.',
            ], 500);
        }
    }

    public function cartView()
    {
        $navcategories = Category::with(['subcategories.childSubcategories'])
            ->where('status', 1)
            ->get();
        $linkss = Link::where('status', 1)->get();

        if (Auth::guard('customer')->check()) {
            $cartItems = CartItem::with('product')
                ->where('user_id', Auth::guard('customer')->id())
                ->where('status', 'active')
                ->get();
        } else {
            $guestToken = Cookie::get('guest_token');
            $cartItems = $guestToken ? CartItem::with('product')
                ->where('guest_token', $guestToken)
                ->where('status', 'active')
                ->get() : collect();
        }

        $totalMRP = 0;
        $finalTotal = 0;
        $itemCount = 0;

        foreach ($cartItems as $item) {
            $totalMRP += $item->mrp_price * $item->qty;
            $finalTotal += $item->price * $item->qty;
            $itemCount += $item->qty;
        }

        $totalDiscountAmount = max(0, $totalMRP - $finalTotal);

        $couponDiscount = session('applied_coupon.discount', 0);
        $finalTotalAfterCoupon = max(0, $finalTotal - $couponDiscount);

        $totalShipping = 0;
        foreach ($cartItems as $item) {
            $prod = $item->product;
            if ($prod && $prod->shipping_type == 1) {
                $totalShipping += ($prod->shipping_charge ?? 0) * $item->qty;
            }
        }

        $grandTotal = $finalTotalAfterCoupon + $totalShipping;

        return view('addtocart', compact(
            'navcategories',
            'cartItems',
            'linkss',
            'totalMRP',
            'finalTotal',
            'itemCount',
            'totalDiscountAmount',
            'totalShipping',
            'couponDiscount',
            'grandTotal',
        ));
    }
    //     public function cartView()
    //     {

    //         $navcategories = Category::with(['subcategories.childSubcategories'])
    //             ->where('status', 1)
    //             ->get();
    //         $linkss = Link::where('status', 1)->get();


    //         if (Auth::guard('customer')->check()) {
    //         $cartItems = CartItem::with('product')
    //             ->where('user_id', Auth::guard('customer')->id())
    //             ->where('status', 'active')
    //             ->get();
    //         } else {
    //             $guestToken = Cookie::get('guest_token');
    //             $cartItems = $guestToken ? CartItem::with('product')
    //                 ->where('guest_token', $guestToken)
    //                 ->where('status', 'active')
    //                 ->get() : collect();
    //         }


    //         // $gstTax = Tax::getByType(Tax::TYPE_GST);
    //         // $otherTax = Tax::getByType(Tax::TYPE_OTHER);


    //         $totalMRP = 0;
    //         $finalTotal = 0;
    //         $itemCount = 0;
    //         $totalTaxAmount = 0;

    //         foreach($cartItems as $item) {
    //             $totalMRP += $item->mrp_price * $item->qty;
    //             $finalTotal += $item->price * $item->qty;
    //             $itemCount += $item->qty;


    //             // $itemSubtotal = $item->price * $item->qty;
    //             // $totalTaxAmount += $item->calculateTaxAmount($itemSubtotal);
    //         }

    //         $totalDiscountAmount = $totalMRP - $finalTotal;
    //         if ($totalDiscountAmount < 0) {
    //             $totalDiscountAmount = 0;
    //         }


    //         $couponDiscount = session('applied_coupon.discount', 0);
    //         $finalTotalAfterCoupon = max(0, $finalTotal - $couponDiscount);


    //         // $taxableAmount = $finalTotalAfterCoupon;
    //         // $totalTaxAmount = 0;
    //         $totalShipping = 0;
    // foreach ($cartItems as $item) {
    //     $product = $item->product;
    //     if ($product && $product->shipping_type == 1) {
    //         $totalShipping += ($product->shipping_charge ?? 0) * $item->qty;
    //     }
    // }
    //         // if ($gstTax) {
    //         //     $totalTaxAmount += ($taxableAmount * $gstTax->tax) / 100;
    //         // }
    //         // if ($otherTax) {
    //         //     $totalTaxAmount += ($taxableAmount * $otherTax->tax) / 100;
    //         // }

    //         //$grandTotal = $finalTotalAfterCoupon + $totalTaxAmount;
    // $grandTotal = $finalTotalAfterCoupon + $totalShipping;

    //         return view('addtocart', compact(
    //             'navcategories',
    //             'cartItems',
    //             'linkss',
    //             'totalMRP',
    //             'finalTotal',
    //             'itemCount',
    //             'totalDiscountAmount',
    //             'totalShipping',


    //             'couponDiscount',
    //             'grandTotal'
    //         ));
    //     }

    public function removeFromCart($id)
    {
        try {
            if (Auth::guard('customer')->check()) {
                $cartItem = CartItem::where('id', $id)
                    ->where('user_id', Auth::guard('customer')->id())
                    ->first();
            } else {
                $guestToken = Cookie::get('guest_token');
                $cartItem = CartItem::where('id', $id)
                    ->where('guest_token', $guestToken)
                    ->first();
            }

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found in cart.',
                ], 404);
            }


            $cartItem->delete();


            if (Auth::guard('customer')->check()) {
                $cartCount = CartItem::where('user_id', Auth::guard('customer')->id())->sum('qty');
            } else {
                $guestToken = Cookie::get('guest_token');
                $cartCount = $guestToken
                    ? CartItem::where('guest_token', $guestToken)->sum('qty')
                    : 0;
            }

            return response()->json([
                'success' => true,
                'message' => 'Product deleted from cart successfully.',
                'cart_count' => $cartCount,
            ]);

        } catch (\Exception $e) {
            \Log::error('Remove cart error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error deleting product from cart.',
            ], 500);
        }
    }


    public function applyCoupon(Request $request)
    {
        $code = strtoupper(trim($request->code));

        $discount = DiscountCode::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$discount) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code!',
            ]);
        }

        if (!$discount->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon code is expired or inactive!',
            ]);
        }


        if (Auth::guard('customer')->check()) {
            $cartItems = CartItem::where('user_id', Auth::guard('customer')->id())
                ->where('status', 'active')
                ->get();
        } else {
            $guestToken = Cookie::get('guest_token');
            $cartItems = $guestToken ? CartItem::where('guest_token', $guestToken)
                ->where('status', 'active')
                ->get() : collect();
        }

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->price * $item->qty;
        }


        $discountedTotal = $discount->applyDiscount($subtotal);
        $couponDiscountAmount = $subtotal - $discountedTotal;


        // $gstTax = Tax::getByType(Tax::TYPE_GST);
        // $otherTax = Tax::getByType(Tax::TYPE_OTHER);


        $taxableAmount = $discountedTotal;
        // $gstTaxAmount = 0;
        // $otherTaxAmount = 0;

        // if ($gstTax) {
        //     $gstTaxAmount = ($taxableAmount * $gstTax->tax) / 100;
        // }
        // if ($otherTax) {
        //     $otherTaxAmount = ($taxableAmount * $otherTax->tax) / 100;
        // }
        $totalShipping = 0;
        foreach ($cartItems as $item) {
            $prod = $item->product ?? Product::find($item->product_id);
            if ($prod && $prod->shipping_type == 1) {
                $totalShipping += ($prod->shipping_charge ?? 0) * $item->qty;
            }
        }
        // $totalTaxAmount = $gstTaxAmount + $otherTaxAmount;
        $finalTotalWithTax = $discountedTotal + $totalShipping;


        session(['applied_coupon' => [
            'code' => $code,
            'discount' => $couponDiscountAmount,
            'discounted_total' => $discountedTotal,
        ]]);

        $discount->incrementUsage();

        return response()->json([
            'success' => true,
            'message' => "Coupon applied successfully!",
            'coupon_discount' => round($couponDiscountAmount, 2),
            'subtotal' => round($subtotal, 2),
            'discounted_subtotal' => round($discountedTotal, 2),
            'shipping_charge'      => round($totalShipping, 2),
            'final_total_with_tax' => round($finalTotalWithTax, 2),
        ]);
    }


    public function removeCoupon(Request $request)
    {
        session()->forget('applied_coupon');
        return response()->json(['success' => true]);
    }

    public function getCartCount()
    {
        if (Auth::guard('customer')->check()) {
            $cartCount = CartItem::where('user_id', Auth::guard('customer')->id())
                ->where('status', 'active')
                ->sum('qty');
        } else {
            $guestToken = Cookie::get('guest_token');
            $cartCount = $guestToken ? CartItem::where('guest_token', $guestToken)
                ->where('status', 'active')
                ->sum('qty') : 0;
        }

        return response()->json([
            'success' => true,
            'cart_count' => $cartCount,
        ]);
    }


    public function searchProduct(Request $request)
    {
        $query = $request->get('query');

        $products = \App\Models\Product::where('name', 'LIKE', "%{$query}%")
            ->select('id', 'name', 'slug', 'image')
            ->take(10)
            ->get();

        return response()->json($products);
    }

    public function productDetails($slug)
    {

        $product = Product::where('slug', $slug)->firstOrFail();
        $linkss = Link::where('status', 1)->get();
        $recentViews = json_decode(Cookie::get('recent_views', '[]'), true);
        if (!in_array($product->id, $recentViews)) {
            array_unshift($recentViews, $product->id);
        }
        $recentViews = array_slice($recentViews, 0, 12);
        Cookie::queue('recent_views', json_encode($recentViews), 60 * 24 * 30);
        $recentProducts = Product::whereIn('id', $recentViews)
         ->where('id', '!=', $product->id)
         ->orderByRaw('FIELD(id, ' . implode(',', $recentViews) . ')')
         ->with(['reviews'])
         ->limit(10)
         ->get()
         ->map(function ($product) {
             $reviews = $product->reviews;
             $product->total_reviews = $reviews->count();
             $product->avg_rating = $reviews->count() > 0 ? $reviews->avg('rating') : 0;
             return $product;
         });


        $relatedProducts = Product::where('category_id', $product->category_id)->where('id', '!=', $product->id)
           ->with(['reviews'])->limit(4)->get()
           ->map(function ($product) {
               $reviews = $product->reviews;
               $totalReviews = $reviews->count();
               $avgRating = $totalReviews > 0 ? $reviews->avg('rating') : 0;

               $product->total_reviews = $totalReviews;
               $product->avg_rating = $avgRating;

               return $product;
           });
        $relatedreview = Review::where('product_id', $product->id)->where('rating', '>=', 4)->latest()->take(8)->get();
        $reviews = Review::where('product_id', $product->id)->get();

        $totalReviews = $reviews->count();
        $avgRating = $reviews->avg('rating');


        $ratingCount = [
            5 => Review::where('product_id', $product->id)->where('rating', 5)->count(),
            4 => Review::where('product_id', $product->id)->where('rating', 4)->count(),
            3 => Review::where('product_id', $product->id)->where('rating', 3)->count(),
            2 => Review::where('product_id', $product->id)->where('rating', 2)->count(),
            1 => Review::where('product_id', $product->id)->where('rating', 1)->count(),
        ];


        $navcategories = Category::with(['subcategories.childSubcategories'])
            ->where('status', 1)
            ->get();


        $colors = Sizecolor::where('type', 1)->where('status', 1)->get();
        $sizes = Sizecolor::where('type', 2)->where('status', 1)->get();
        $productUrl = route('product.details', $product->slug);
        $cartCount = CartItem::count();

        $productSizes = [];

        if (!empty($product->sizes)) {
            $productSizes = is_array($product->sizes)
                ? $product->sizes
                : json_decode($product->sizes, true);
            $sizes = Sizecolor::whereIn('id', $productSizes)->pluck('name')->toArray();
        }

        $colorNames = [];

        if (!empty($product->colors)) {
            $colorIds = is_array($product->colors)
                ? $product->colors
                : json_decode($product->colors, true);

            $colorNames = Sizecolor::whereIn('id', $colorIds)->pluck('name')->toArray();
        }
        $meta = Seo::where('meta_slug', $slug)->first();
        $metatitle = $meta?->meta_title;
        $metadescription = $meta?->meta_description;
        $canonicalurl = $meta?->canonical_url;
        $metakeyword = $meta?->meta_keyword;
        return view('productdetails', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'navcategories' => $navcategories,
            'colors' => $colors,
            'sizes' => $sizes,
            'linkss' => $linkss,
            'productUrl' => $productUrl,
            'recentProducts' => $recentProducts,
            'cartCount' => $cartCount ,
            'relatedreview' => $relatedreview,
            'totalReviews' => $totalReviews,
            'avgRating' => $avgRating,
            'ratingCount' => $ratingCount,
            'sizes' => $sizes,
            'colorNames' => $colorNames,
            'metatitle' => $metatitle,
            'meta' => $meta,
            'canonicalurl' => $canonicalurl,
            'metakeyword' => $metakeyword,
            'metadescription' => $metadescription,

        ]);
    }



    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'Phone'   => 'required|string|max:20',
            'message' => 'nullable|string|max:1000',
        ]);

        Contact::create([
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'phone'   => $validated['Phone'],
            'message' => $validated['message'],
        ]);

        return response()->json(['success' => true, 'message' => 'Message saved successfully!']);
    }



    public function searchProducts(Request $request)
    {
        $query = $request->get('query');

        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->where('status', 1)
            ->limit(10)
            ->get(['name', 'slug']);

        $html = '';

        if ($products->count() > 0) {
            foreach ($products as $product) {
                $html .= '<a href="' . url('/product/' . $product->slug) . '">' . e($product->name) . '</a>';
            }
        } else {
            $html .= '<p style="padding:8px;">No products found.</p>';
        }

        return response()->json(['html' => $html]);
    }

    public function toggle(Request $request)
    {
        try {
            $guest_token = $this->getGuestToken();
            $productId = $request->product_id;

            $exists = Wishlist::where('guest_token', $guest_token)
                              ->where('product_id', $productId)
                              ->first();

            if ($exists) {
                $exists->delete();
                $count = Wishlist::where('guest_token', $guest_token)->count();
                return response()->json([
                    'status' => 'removed',
                    'count' => $count,
                    'message' => 'Product removed from wishlist',
                ]);
            } else {
                Wishlist::create([
                    'guest_token' => $guest_token,
                    'product_id' => $productId,
                ]);
                $count = Wishlist::where('guest_token', $guest_token)->count();
                return response()->json([
                    'status' => 'added',
                    'count' => $count,
                    'message' => 'Product added to wishlist',
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Wishlist toggle error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating wishlist',
            ], 500);
        }
    }

    public function count()
    {
        $guest_token = $this->getGuestToken();
        $count = Wishlist::where('guest_token', $guest_token)->count();
        return response()->json(['count' => $count]);
    }


    public function allReviews($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $reviews = Review::where('product_id', $product->id)->latest()->paginate(10);
        $navcategories = Category::with(['subcategories.childSubcategories'])->where('status', 1)->get();
        $linkss = Link::where('status', 1)->get();
        return view('showreview', compact('product', 'reviews', 'linkss', 'navcategories'));
    }
    public function submitReview(Request $request)
    {
        if (!auth()->check() || auth()->user()->type != 2) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to submit a review.',
                'requires_login' => true, // Add this flag
            ], 401);
        }
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'name'       => 'required|string|max:255',
            'rating'     => 'required|integer|between:1,5',
            'review'     => 'required|string|max:2000',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $review = new Review();
            $review->product_id = $request->product_id;
            $review->name       = $request->name;
            $review->user_id    = auth()->id();
            $review->rating     = $request->rating;
            $review->review     = $request->review;
            $review->status     = 'pending';

            if ($request->hasFile('image')) {
                $imageName = time() . '_' . $request->image->getClientOriginalName();
                $request->image->move(public_path('userassets/image/reviews'), $imageName);
                $review->image = $imageName;
            }

            $review->save();

            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully! It will be visible after approval.',
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }



}
