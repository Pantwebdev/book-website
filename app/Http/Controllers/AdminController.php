<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Sizecolor;
use App\Models\ChildSubcategory;
use App\Models\Product;
use App\Models\slider;
use App\Models\Contact;
use App\Models\Link;
use App\Models\Setting;
use App\Models\CheckAvailability;
use App\Models\SimplePage;
use App\Models\Blog;
use App\Models\Seo;
use App\Models\User;
use App\Models\MasterOrder;
use App\Models\MasterOrderItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Imports\CheckAvailabilityImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\PrintOrder;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendRemainingPaymentJob;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalOrders   = MasterOrder::count();
        $totalCustomer = User::where('type', 2)->count();
        $todayOrders   = MasterOrder::whereDate('created_at', today())->count();
        $totalRevenue  = MasterOrder::sum('grand_total');
        $lowStockProducts = Product::where('stock', '<=', 5)->get();
        return view('admin.admindashboard', compact(
            'totalOrders',
            'todayOrders',
            'totalRevenue',
            'totalCustomer',
            'lowStockProducts',
        ));
    }
    public function form()
    {
        return view('admin.form');
    }

    // private function updateSitemap()
    // {
    //     try {
    //         $sitemap = \Spatie\Sitemap\Sitemap::create();

    //         $sitemap->add(\Spatie\Sitemap\Tags\Url::create('/')->setPriority(1.0));
    //         $sitemap->add(\Spatie\Sitemap\Tags\Url::create('/shop')->setPriority(0.9));

    //         \App\Models\Category::chunk(100, function ($categories) use ($sitemap) {
    //             foreach ($categories as $category) {
    //                 if ($category->slug) {
    //                     $sitemap->add(
    //                         \Spatie\Sitemap\Tags\Url::create("/{$category->slug}")
    //                             ->setPriority(0.8)
    //                             ->setLastModificationDate($category->updated_at),
    //                     );
    //                 }
    //             }
    //         });

    //         \App\Models\Subcategory::with('category')->chunk(100, function ($subcategories) use ($sitemap) {
    //             foreach ($subcategories as $subcategory) {
    //                 if ($subcategory->slug && $subcategory->category) {
    //                     $sitemap->add(
    //                         \Spatie\Sitemap\Tags\Url::create("/{$subcategory->category->slug}/{$subcategory->slug}")
    //                             ->setPriority(0.7)
    //                             ->setLastModificationDate($subcategory->updated_at),
    //                     );
    //                 }
    //             }
    //         });

    //         \App\Models\ChildSubCategory::with(['category', 'subcategory'])->chunk(100, function ($children) use ($sitemap) {
    //             foreach ($children as $child) {
    //                 if ($child->slug && $child->category && $child->subcategory) {
    //                     $sitemap->add(
    //                         \Spatie\Sitemap\Tags\Url::create("/{$child->category->slug}/{$child->subcategory->slug}/{$child->slug}")
    //                             ->setPriority(0.6)
    //                             ->setLastModificationDate($child->updated_at),
    //                     );
    //                 }
    //             }
    //         });

    //         \App\Models\Product::chunk(100, function ($products) use ($sitemap) {
    //             foreach ($products as $product) {
    //                 if ($product->slug) {
    //                     $sitemap->add(
    //                         \Spatie\Sitemap\Tags\Url::create("/product/{$product->slug}")
    //                             ->setPriority(0.7)
    //                             ->setLastModificationDate($product->updated_at),
    //                     );
    //                 }
    //             }
    //         });

    //         $sitemap->writeToFile(public_path('sitemap.xml'));

    //     } catch (\Exception $e) {
    //         \Log::error('Sitemap update failed: ' . $e->getMessage());
    //     }
    // }
    public function categoryData(Request $request)
    {
        $query = Category::select(['id', 'title', 'status']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                $badge = $row->status ? 'badge-success' : 'badge-danger';
                $label = $row->status ? 'Active' : 'Inactive';
                return '<a href="' . route('category.status.toggle', $row->id) . '" class="badge ' . $badge . '">' . $label . '</a>';
            })
            ->addColumn('action', function ($row) {
                return '
                <div class="form-button-action">
                    <a href="' . route('category.edit', $row->id) . '" class="btn btn-link btn-primary btn-lg" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form action="' . route('category.delete', $row->id) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button class="btn btn-link btn-danger" type="submit" onclick="return confirm(\'Are you sure to delete?\')" title="Delete">
                            <i class="fa fa-times"></i>
                        </button>
                    </form>
                </div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function category()
    {
        return view('admin.category');  // compact hataao
    }

    public function categoryCreate()
    {
        $categories = Category::latest()->get();
        return view('admin.category', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        $categoryCount = Category::count();

        // Restrict to max 8 categories
        if ($categoryCount >= 8) {
            return redirect()->back()->with('error', 'You can only create a maximum of 8 categories.');
        }
        $request->validate([
            'title' => 'required|string|max:255|unique:categories,title',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'alt_tag' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $category = new Category();
        $category->title = $request->title;
        $category->slug = Str::slug($request->title);
        $category->alt_tag = $request->alt_tag;
        $category->meta_description = $request->meta_description;
        $category->status = $request->status;

        $category->show_collaj = $request->show_collaj ?? 0;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->hashName();
            $image->move(public_path('userassets/image/menu'), $imageName);
            $category->image = $imageName;
        }


        $category->save();

        return redirect()->route('category')->with('success', 'Category created successfully');
    }

    public function categoryEdit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.editcategory', compact('category'));
    }
    public function categoryUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'alt_tag' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $category = Category::findOrFail($id);
        $category->title = $request->title;
        $category->slug = Str::slug($request->title);
        $category->alt_tag = $request->alt_tag;
        $category->meta_description = $request->meta_description;
        $category->status = $request->status ?? 1;

        if ($request->hasFile('image')) {
            if ($category->image && File::exists(public_path('userassets/image/menu/' . $category->image))) {
                File::delete(public_path('userassets/image/menu/' . $category->image));
            }
            $image = $request->file('image');
            $filename = $image->hashName();
            $request->image->move(public_path('userassets/image/menu'), $filename);
            $category->image = $filename;
        }




        $category->save();

        return redirect()->route('category')->with('success', 'Category updated successfully');
    }

    public function categoryDelete($id)
    {
        $category = Category::findOrFail($id);
        $subCategoryIds = Subcategory::where('category_id', $id)->pluck('id');
        if ($subCategoryIds->count() > 0) {
            $childCount = ChildSubcategory::whereIn('sub_category_id', $subCategoryIds)->count();
            if ($childCount > 0) {
                return back()->with(
                    'error',
                    '❌ Please delete child subcategories under this category first.',
                );
            }
            return back()->with(
                'error',
                '❌ Please delete subcategories under this category first.',
            );
        }
        if ($category->image && file_exists(public_path('userassets/image/menu/' . $category->image))) {
            unlink(public_path('userassets/image/menu/' . $category->image));
        }
        $category->delete();
        return redirect()->route('category')->with('success', '✅ Category deleted successfully.');
    }



    public function removeCategoryImage($id, $type = null)
    {
        $category = Category::findOrFail($id);
        $path = 'userassets/image/menu/';

        if ($type === 'collaj' && $category->collaj_image) {
            File::delete(public_path($path . $category->collaj_image));
            $category->collaj_image = null;
        } elseif ($type === 'explore' && $category->explore_image) {
            File::delete(public_path($path . $category->explore_image));
            $category->explore_image = null;
        } elseif ($type === 'collection' && $category->collection_image) {
            File::delete(public_path($path . $category->collection_image));
            $category->collection_image = null;
        } else {
            File::delete(public_path($path . $category->image));
            $category->image = null;
        }

        $category->save();
        return back()->with('success', ucfirst($type ?? 'Main') . ' image removed successfully');
    }


    public function togglecategoryStatus($id)
    {
        $category = Category::findOrFail($id);
        $category->status = $category->status ? 0 : 1; // Toggle the status (1 → 0, 0 → 1)
        $category->save();

        return redirect()->back()->with('success', 'category status updated successfully.');
    }
    public function togglecolajStatus(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->show_collaj = $request->collaj;  // ✅ Store toggle value in show_collaj
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Collaj status updated successfully',
            'show_collaj' => $category->show_collaj,
        ]);
    }
    public function toggleexploreStatus(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->show_explore = $request->explore;  // ✅ Store toggle value in show_collaj
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'show_explore status updated successfully',
            'show_explore' => $category->show_explore,
        ]);
    }
    public function togglecollectionStatus(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->show_collection = $request->collection;  // ✅ Store toggle value in show_collaj
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'show_collection status updated successfully',
            'show_collection' => $category->show_collection,
        ]);
    }



    public function subcategoryData(Request $request)
    {
        $query = Subcategory::with('parentCategory')->select(['id', 'category_id', 'title', 'show_collaj', 'status']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('category', function ($row) {
                return $row->parentCategory->title ?? 'N/A';
            })
            ->addColumn('collaj', function ($row) {
                return '<input type="checkbox" class="collaj-toggle" data-id="' . $row->id . '" ' . ($row->show_collaj == 1 ? 'checked' : '') . '>';
            })
            ->addColumn('status', function ($row) {
                $badge = $row->status ? 'badge-success' : 'badge-danger';
                $label = $row->status ? 'Active' : 'Inactive';
                return '<a href="' . route('subcategory.status.toggle', $row->id) . '" class="badge ' . $badge . '">' . $label . '</a>';
            })
            ->addColumn('action', function ($row) {
                return '
                <div class="form-button-action">
                    <a href="' . route('subcategory.edit', $row->id) . '" class="btn btn-link btn-primary btn-lg" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form action="' . route('subcategory.delete', $row->id) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button class="btn btn-link btn-danger" type="submit" onclick="return confirm(\'Are you sure to delete?\')" title="Delete">
                            <i class="fa fa-times"></i>
                        </button>
                    </form>
                </div>';
            })
            ->rawColumns(['collaj', 'status', 'action'])
            ->make(true);
    }
    public function subcategory()
    {
        $categories = Category::get();
        return view('admin.subcategory', compact('categories')); // subcategories hataao
    }

    public function subcategoryStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:subcategories,title',
            'category_id' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            'description' => 'nullable|string',
        ]);

        $subcategory = new Subcategory(); // Replace with your actual model
        $subcategory->title = $request->title;
        $subcategory->slug = Str::slug($request->title); // ✅ Generate slug
        $subcategory->category_id = $request->category_id;

        $subcategory->description = $request->description;
        $subcategory->status = $request->status;

        // ✅ Handle Image Upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $hashedName = hash_file('sha256', $image->getRealPath()) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('userassets/image/menu');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $hashedName);
            $subcategory->image = $hashedName;
        }

        $subcategory->save();

        return redirect()->route('subcategory')->with('success', 'Subcategory created successfully!');
    }
    public function subcategoryEdit($id)
    {
        $categories = Category::all();
        $subcategory = Subcategory::find($id);

        return view('admin.editsubcategory', compact('categories', 'subcategory'));
    }
    public function subcategoryUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'description' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (str_word_count($value) > 15) {
                        $fail('Description must not exceed 15 words.');
                    }
                },
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'explore_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $subcategory = Subcategory::findOrFail($id);
        $subcategory->title = $request->title;
        $subcategory->slug = $request->slug ?? \Str::slug($request->title);
        $subcategory->category_id = $request->category_id;

        $subcategory->description = $request->description;

        if ($request->hasFile('image')) {
            if ($subcategory->image && file_exists(public_path('userassets/image/menu/' . $subcategory->image))) {
                unlink(public_path('userassets/image/menu/' . $subcategory->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('userassets/image/menu'), $imageName);
            $subcategory->image = $imageName;
        }
        if ($subcategory->show_explore == 1 && $request->hasFile('explore_image')) {
            if ($subcategory->explore_image && file_exists(public_path('userassets/image/menu/' . $subcategory->explore_image))) {
                unlink(public_path('userassets/image/menu/' . $subcategory->explore_image));
            }

            $explore = $request->file('explore_image');
            $exploreName = time() . '_explore_' . uniqid() . '.' . $explore->getClientOriginalExtension();
            $explore->move(public_path('userassets/image/menu'), $exploreName);
            $subcategory->explore_image = $exploreName;
        }
        $subcategory->save();

        return redirect()->route('subcategory')->with('success', 'Subcategory updated successfully.');
    }
    public function subcategoryDelete($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $productCount = Product::where('sub_category_id', $id)->count();
        if ($productCount > 0) {
            return back()->with('error', '❌ Please delete products under this subcategory first.');
        }
        $childCount = ChildSubcategory::where('sub_category_id', $id)->count();
        if ($childCount > 0) {
            return back()->with('error', '❌ Please delete child subcategories first.');
        }
        if ($subcategory->image && file_exists(public_path('userassets/image/menu/' . $subcategory->image))) {
            unlink(public_path('userassets/image/menu/' . $subcategory->image));
        }

        $subcategory->delete();

        return redirect()->route('subcategory')->with('success', '✅ Subcategory deleted successfully.');
    }

    public function removeSubcategoryImage($id)
    {
        $subcategory = Subcategory::findOrFail($id);

        if ($subcategory->image && file_exists(public_path('userassets/image/menu/' . $subcategory->image))) {
            unlink(public_path('userassets/image/menu/' . $subcategory->image));
            $subcategory->image = null;
            $subcategory->save();
        }

        return redirect()->back()->with('success', 'Image removed successfully.');
    }
    public function removeExploreImage($id)
    {
        $subcategory = Subcategory::findOrFail($id);

        if ($subcategory->explore_image && file_exists(public_path('userassets/image/menu/' . $subcategory->explore_image))) {
            unlink(public_path('userassets/image/menu/' . $subcategory->explore_image));
            $subcategory->explore_image = null;
            $subcategory->save();
        }

        return back()->with('success', 'Explore image removed successfully.');
    }

    public function toggleSubcategoryStatus($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $subcategory->status = $subcategory->status ? 0 : 1; // Toggle the status (1 → 0, 0 → 1)
        $subcategory->save();

        return redirect()->back()->with('success', 'Subcategory status updated successfully.');
    }
    public function togglesubcolajStatus(Request $request)
    {
        $Subcategory = Subcategory::findOrFail($request->id);
        $Subcategory->show_collaj = $request->collaj;  // ✅ Store toggle value in show_collaj
        $Subcategory->save();

        return response()->json([
            'success' => true,
            'message' => 'Collaj status updated successfully',
            'show_collaj' => $Subcategory->show_collaj,
        ]);
    }
    public function togglesubexploreStatus(Request $request)
    {
        $category = Subcategory::findOrFail($request->id);
        $category->show_explore = $request->explore;  // ✅ Store toggle value in show_collaj
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'show_explore status updated successfully',
            'show_explore' => $category->show_explore,
        ]);
    }

    public function getSubcategoriesByCategory($category_id)
    {
        $subcategories = Subcategory::where('category_id', $category_id)
                                    ->where('status', 1)
                                    ->get();

        // Return JSON response
        return response()->json($subcategories);
    }

    public function getChildSubcategoriesBySubcategory($subcategory_id)
    {
        $childSubcategories = ChildSubcategory::where('sub_category_id', $subcategory_id)
                                            ->where('status', 1)
                                            ->get();

        return response()->json($childSubcategories);
    }




    public function childsubcategoryData(Request $request)
    {
        $query = ChildSubcategory::with(['category', 'subcategory'])
                    ->select(['id', 'category_id', 'sub_category_id', 'title', 'status']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('category', function ($row) {
                return $row->category ? $row->category->title : '-';
            })
            ->addColumn('subcategory', function ($row) {
                return $row->subcategory ? $row->subcategory->title : '-';
            })
            ->addColumn('status', function ($row) {
                $badge = $row->status ? 'badge-success' : 'badge-danger';
                $label = $row->status ? 'Active' : 'Inactive';
                return '<a href="' . route('childsubcategory.status.toggle', $row->id) . '" class="badge ' . $badge . '">' . $label . '</a>';
            })
            ->addColumn('action', function ($row) {
                return '
                <div class="form-button-action">
                    <a href="' . route('childsubcategory.edit', $row->id) . '" class="btn btn-link btn-primary btn-lg" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form action="' . route('childsubcategory.delete', $row->id) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button class="btn btn-link btn-danger" type="submit" onclick="return confirm(\'Are you sure to delete?\')" title="Delete">
                            <i class="fa fa-times"></i>
                        </button>
                    </form>
                </div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
    public function childsubcategory()
    {
        $categories = Category::where('status', 1)->get();
        return view('admin.childsubcategory', compact('categories')); // childsubcategories hataao
    }



    public function childsubcategoryStore(Request $request)
    {
        $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('child_subcategories', 'title'), // ✅ Prevent duplicate title
            ],
            'category_id' => 'required|string|max:255',
            'sub_category_id' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
        ], [
            'title.unique' => 'This child subcategory name already exists. Please choose a different name.',
            'title.required' => 'Please enter a child subcategory title.',
        ]);

        $childsubcategory = new ChildSubcategory(); // Replace with your actual model
        $childsubcategory->title = $request->title;
        $childsubcategory->slug = Str::slug($request->title); // ✅ Generate slug
        $childsubcategory->category_id = $request->category_id;

        $childsubcategory->sub_category_id = $request->sub_category_id;
        $childsubcategory->description = $request->description;
        $childsubcategory->status = $request->status;

        // ✅ Handle Image Upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $hashedName = hash_file('sha256', $image->getRealPath()) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('userassets/image/menu');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $hashedName);
            $childsubcategory->image = $hashedName;
        }

        $childsubcategory->save();

        return redirect()->route('childsubcategory')->with('success', 'Subcategory created successfully!');
    }
    public function childsubcategoryEdit($id)
    {
        $categories = Category::where('status', 1)->get();
        $subcategories = Subcategory::where('status', 1)->get();
        $childsubcategory = ChildSubcategory::findOrFail($id);

        return view('admin.editchildsubcategory', compact('categories', 'subcategories', 'childsubcategory'));
    }
    public function childsubcategoryUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:subcategories,id', // ✅ Fixed here
            'description' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (str_word_count($value) > 15) {
                        $fail('Description must not exceed 15 words.');
                    }
                },
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'collection_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // optional fix for naming
        ]);

        $childsubcategory = ChildSubcategory::findOrFail($id);
        $childsubcategory->title = $request->title;
        $childsubcategory->slug = $request->slug ?? \Str::slug($request->title);
        $childsubcategory->category_id = $request->category_id;
        $childsubcategory->sub_category_id = $request->sub_category_id;
        $childsubcategory->description = $request->description;

        // ✅ Normal Image Upload
        if ($request->hasFile('image')) {
            if ($childsubcategory->image && file_exists(public_path('userassets/image/menu/' . $childsubcategory->image))) {
                unlink(public_path('userassets/image/menu/' . $childsubcategory->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('userassets/image/menu'), $imageName);
            $childsubcategory->image = $imageName;
        }

        // ✅ Shop By Collection (only if show_collection = 1)
        if ($childsubcategory->show_collection == 1 && $request->hasFile('collection_image')) {
            if ($childsubcategory->collection_image && file_exists(public_path('userassets/image/menu/' . $childsubcategory->collection_image))) {
                unlink(public_path('userassets/image/menu/' . $childsubcategory->collection_image));
            }

            $collection = $request->file('collection_image');
            $collectionName = time() . '_collection_' . uniqid() . '.' . $collection->getClientOriginalExtension();
            $collection->move(public_path('userassets/image/menu'), $collectionName);
            $childsubcategory->collection_image = $collectionName;
        }

        $childsubcategory->save();

        return redirect()->route('childsubcategory')->with('success', 'Child Subcategory updated successfully.');
    }

    public function togglechildsubcollectionStatus(Request $request)
    {
        $childsubcategory = ChildSubcategory::findOrFail($request->id);
        $childsubcategory->show_collection = $request->collection;  // ✅ Store toggle value in show_collaj
        $childsubcategory->save();

        return response()->json([
            'success' => true,
            'message' => 'show_collection status updated successfully',
            'show_collection' => $childsubcategory->show_collection,
        ]);
    }
    public function childsubcategoryDelete($id)
    {
        $childsubcategory = ChildSubcategory::findOrFail($id);
        $productCount = Product::where('child_sub_category_id', $id)->count();
        if ($productCount > 0) {
            return back()->with('error', '❌ Please delete products under this child subcategory first.');
        }
        if ($childsubcategory->image && file_exists(public_path('userassets/image/menu/' . $childsubcategory->image))) {
            unlink(public_path('userassets/image/menu/' . $childsubcategory->image));
        }

        $childsubcategory->delete();

        return redirect()->route('childsubcategory')->with('success', '✅ Child Subcategory deleted successfully.');
    }

    public function removechildsubcategoryImage($id)
    {
        $childsubcategory = ChildSubcategory::findOrFail($id);

        if ($childsubcategory->image && file_exists(public_path('userassets/image/menu/' . $childsubcategory->image))) {
            unlink(public_path('userassets/image/menu/' . $childsubcategory->image));
            $childsubcategory->image = null;
            $childsubcategory->save();
        }

        return redirect()->back()->with('success', 'Image removed successfully.');
    }
    public function removecollectionImage($id)
    {
        $childsubcategory = ChildSubcategory::findOrFail($id);

        if ($childsubcategory->collection_image && file_exists(public_path('userassets/image/menu/' . $childsubcategory->collection_image))) {
            unlink(public_path('userassets/image/menu/' . $childsubcategory->collection_image));
            $childsubcategory->collection_image = null;
            $childsubcategory->save();
        }

        return back()->with('success', 'Explore image removed successfully.');
    }
    public function toggleChildSubcategoryStatus($id)
    {
        $childsubcategory = ChildSubcategory::findOrFail($id);

        // Toggle status
        $childsubcategory->status = $childsubcategory->status ? 0 : 1;
        $childsubcategory->save();

        return redirect()->back()->with('success', 'Subcategory status updated successfully.');
    }

    public function productData(Request $request)
    {
        $query = Product::with(['category', 'subcategory', 'childsubcategory'])
                        ->select(['id', 'category_id', 'sub_category_id', 'child_sub_category_id', 'sku', 'name', 'stock', 'image']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('category', function ($row) {
                $cat = $row->category?->title ?? '-';
                if ($row->subcategory) {
                    $cat .= ' -> ' . $row->subcategory->title;
                }
                if ($row->childsubcategory) {
                    $cat .= ' -> ' . $row->childsubcategory->title;
                }
                return $cat;
            })
            ->addColumn('stock', function ($row) {
                $badge = $row->stock <= 5 ? '<span class="badge bg-danger ms-2">Low Stock!</span>' : '';
                return $row->stock . ' ' . $badge;
            })
            ->addColumn('image', function ($row) {
                if ($row->image) {
                    return '<img src="' . asset('userassets/image/product/' . $row->image) . '" width="60" height="60" alt="image">';
                }
                return '<span class="text-muted">No Image</span>';
            })
            ->addColumn('action', function ($row) {
                return '
                <div class="form-button-action">
                    <a href="' . route('product.edit', $row->id) . '" class="btn btn-link btn-primary btn-lg" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form action="' . route('product.delete', $row->id) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button class="btn btn-link btn-danger" type="submit" onclick="return confirm(\'Are you sure to delete?\')" title="Delete">
                            <i class="fa fa-times"></i>
                        </button>
                    </form>
                </div>';
            })
            ->rawColumns(['category', 'stock', 'image', 'action'])
            ->make(true);
    }
    public function showproduct()
    {
        $subcategories = Subcategory::with('parentCategory')->latest()->get();
        $categories = Category::get();
        $childsubcategories = ChildSubCategory::get();
        $colors = Sizecolor::where('type', 1)->get(); // Type 1 = Color
        $sizes  = Sizecolor::where('type', 2)->get();
        $Product  = Product::get();
        return view('admin.showproduct', compact('categories', 'subcategories', 'childsubcategories', 'colors', 'sizes', 'Product'));
    }
    public function product()
    {
        $subcategories = Subcategory::with('parentCategory')->latest()->get();
        $categories = Category::get();
        $childsubcategories = ChildSubCategory::get();
        $colors = Sizecolor::where('type', 1)->get(); // Type 1 = Color
        $sizes  = Sizecolor::where('type', 2)->get();
        return view('admin.product', compact('categories', 'subcategories', 'childsubcategories', 'colors', 'sizes'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'sku' => 'required|string|min:6|regex:/^\S*$/|unique:products,sku',
            'name' => 'required|string|max:255',
            'stock' => 'required|integer',
            'mrp_price' => 'required|integer|min:1',
            'discount' => 'nullable|integer|min:0|max:100',
            'display_price' => 'nullable|integer|min:1',
            'description' => 'required|string',
            'author' => 'required|string',
            'publisher' => 'required|string',
            'edition' => 'required|string',
            'published_date' => 'required|string',
            'language' => 'required|string',
            'product_pdf' => 'nullable|file|mimes:pdf|max:10240',


            'image' => 'required|image|mimes:jpg,jpeg,png|max:4096',
            'image2' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'image3' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'image4' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'image5' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',

            'multipleimage.*' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ], [
            'sku.regex' => 'SKU should not contain spaces.',
        ]);

        // main images
        $imagePath = null;
        $imagePaths = [
            'image2' => null,
            'image3' => null,
            'image4' => null,
            'image5' => null,
        ];

        // upload main image
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadImage($request->file('image'));
        }

        // upload image2 to image5
        foreach ($imagePaths as $key => $value) {
            if ($request->hasFile($key)) {
                $imagePaths[$key] = $this->uploadImage($request->file($key));
            }
        }

        // multiple images
        $multipleImages = [];
        if ($request->hasFile('multipleimage')) {

            foreach ($request->file('multipleimage') as $file) {
                $multipleImages[] = $this->uploadImage($file);
            }
        }


        $pdfPath = null;
        if ($request->hasFile('product_pdf')) {
            $file = $request->file('product_pdf');
            $pdfName = md5(time() . rand()) . '.pdf';
            $file->move(public_path('userassets/image/product/pdf'), $pdfName);
            $pdfPath = $pdfName;
        }
        // price calculation
        $mrp = (int) $request->mrp_price;
        $discount = (int) ($request->discount ?? 0);
        $displayPrice = $request->display_price;

        if ($discount > 0 && empty($displayPrice)) {
            $displayPrice = $mrp - ($mrp * $discount / 100);
        }

        $displayPrice = $displayPrice !== '' ? $displayPrice : null;

        // slug
        $slug = $request->meta_slug
            ? Str::slug($request->meta_slug)
            : Str::slug($request->name);

        $category = Category::find($request->category_id);

        // SEO fields
        $metaTitle = $request->meta_title
            ?: ($category->title . ' ' . $request->name . ' ' . $request->sku);

        $canonicalUrl = $request->canonical_url
            ?: url('/product/' . $slug);

        $metaDescription = $request->meta_description
            ?: Str::limit(strip_tags($request->description), 160);

        $seoImageUrl = $imagePath
            ? url('userassets/image/product/' . $imagePath)
            : null;

        // create product
        $product = Product::create([
            'category_id' => $request->category_id,
            'sub_category_id' => $request->filled('sub_category_id') ? $request->sub_category_id : null,
            'child_sub_category_id' => $request->filled('child_sub_category_id') ? $request->child_sub_category_id : null,

            'sku' => preg_replace('/\s+/', '', $request->sku),
            'name' => $request->name,
            'slug' => $slug,

            'language' => $request->language,
            'edition' => $request->edition,
            'published_date' => $request->published_date,

            'display_price' => $displayPrice,
            'mrp_price' => $mrp,
            'discount' => $discount,

            'stock' => $request->stock,

            'author' => $request->author,
            'publisher' => $request->publisher,

            'product_on_sale' => $request->product_on_sale ? 1 : 0,
            'new_arrivals' => $request->new_arrivals ? 1 : 0,
            'bulk_products' => $request->bulk_products ? 1 : 0,
            'exam_corner' => $request->exam_corner ? 1 : 0,
            'featured_book' => $request->featured_book ? 1 : 0,

            'description' => $request->description,
            'shipping_type' => $request->shipping_type,
            'shipping_charge' => $request->shipping_charge,

            'image' => $imagePath,
            'image2' => $imagePaths['image2'],
            'image3' => $imagePaths['image3'],
            'image4' => $imagePaths['image4'],
            'image5' => $imagePaths['image5'],

            'multipleimage' => json_encode($multipleImages),

            'altimage' => $request->altimage,
            'altimage2' => $request->altimage2,
            'altimage3' => $request->altimage3,
            'altimage4' => $request->altimage4,
            'altimage5' => $request->altimage5,
            'product_pdf' => $pdfPath,
            'cod_available' => $request->cod_available ? 1 : 0,
        ]);

        // create SEO
        Seo::create([
            'meta_slug' => $slug,
            'meta_title' => $metaTitle,
            'canonical_url' => $canonicalUrl,
            'image_url' => $seoImageUrl,
            'meta_keyword' => $request->meta_keyword,
            'meta_description' => $metaDescription,
        ]);
        // $this->updateSitemap();
        return redirect()->route('showproduct')
            ->with('success', '✅ Product created successfully!');
    }
    private function uploadImage($file)
    {
        $name = md5(time() . rand()) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('userassets/image/product'), $name);
        return $name;
    }

    public function productEdit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::get();
        $subcategories = Subcategory::where('category_id', $product->category_id)->where('status', 1)->get();
        $childsubcategories = ChildSubCategory::where('sub_category_id', $product->sub_category_id)->where('status', 1)->get();

        return view('admin.editproduct', compact(
            'product',
            'categories',
            'subcategories',
            'childsubcategories',
        ));
    }

    public function productUpdate(Request $request, $id)
    {
        try {
            \Log::info('Starting product update for ID: ' . $id);

            $product = Product::findOrFail($id);
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'sku' => 'required|regex:/^\S*$/|unique:products,sku,' . $product->id,
                'name' => 'required|string|max:255',
                'mrp_price' => 'required|numeric|min:0',
                'display_price' => 'nullable|numeric',
                'discount' => 'nullable|numeric|min:0|max:100',
                'stock' => 'required|integer',
                'description' => 'nullable|string',
                'product_pdf' => 'nullable|file|mimes:pdf|max:10240',

                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
                'image2' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
                'image3' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
                'image4' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
                'image5' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
                'multipleimage.*' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
                'color_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            ]);

            \Log::info('Validation passed');

            $imagePath = $product->image;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = md5(time() . rand()) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('userassets/image/product'), $imageName);
                $imagePath = $imageName;
            }

            $imagePaths = [
                'image2' => $product->image2,
                'image3' => $product->image3,
                'image4' => $product->image4,
                'image5' => $product->image5,
            ];

            $images = ['image2', 'image3', 'image4', 'image5'];
            foreach ($images as $imgField) {
                if ($request->hasFile($imgField)) {
                    $file = $request->file($imgField);
                    $name = md5(time() . rand()) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('userassets/image/product'), $name);
                    $imagePaths[$imgField] = $name;
                }
            }

            $existingImages = $product->multipleimage
            ? (is_array($product->multipleimage) ? $product->multipleimage : json_decode($product->multipleimage, true) ?? [])
            : [];

            if ($request->hasFile('multipleimage')) {
                foreach ($request->file('multipleimage') as $multiImg) {
                    $mName = md5(time() . rand()) . '.' . $multiImg->getClientOriginalExtension();
                    $multiImg->move(public_path('userassets/image/product'), $mName);
                    $existingImages[] = $mName;
                }
            }

            $discount = null;
            $displayPrice = null;

            if ($request->has('apply_discount') && $request->apply_discount == 1 && $request->filled('discount')) {
                $discount = $request->discount;
                $displayPrice = $request->mrp_price - ($request->mrp_price * $discount / 100);
            } else {
                $displayPrice = $request->mrp_price;
                $discount = null;
            }

            // PDF upload
            $pdfPath = $product->product_pdf;
            if ($request->hasFile('product_pdf')) {
                // delete old pdf if exists
                if ($product->product_pdf && file_exists(public_path('userassets/image/product/pdf/' . $product->product_pdf))) {
                    unlink(public_path('userassets/image/product/pdf/' . $product->product_pdf));
                }
                $pdfFile = $request->file('product_pdf');
                $pdfName = md5(time() . rand()) . '.pdf';
                $pdfFile->move(public_path('userassets/image/product/pdf'), $pdfName);
                $pdfPath = $pdfName;
            }
            // Shipping Logic
            $shippingType = $request->shipping_type; // 0 or 1

            $shippingCharge = $shippingType == 1
    ? $request->shipping_charge
    : 0;
            $updateData = [
                'category_id' => $request->category_id,
                'sub_category_id' => $request->filled('sub_category_id') ? $request->sub_category_id : null,
                'child_sub_category_id' => $request->filled('child_sub_category_id') ? $request->child_sub_category_id : null,
                'sku' => preg_replace('/\s+/', '', $request->sku),
                'name' => $request->name,
                'slug' => \Illuminate\Support\Str::slug($request->name),
                'language' => $request->language,
                'edition' => $request->edition,
                'published_date' => $request->published_date,
                'mrp_price' => $request->mrp_price,
                'display_price' => $displayPrice,
                'discount' => $discount,
                'stock' => $request->stock,
                'author' => $request->author,
                'publisher' => $request->publisher,
                'product_on_sale' => $request->product_on_sale ? 1 : 0,
                'new_arrivals' => $request->new_arrivals ? 1 : 0,
                'exam_corner' => $request->exam_corner ? 1 : 0,
                'featured_book' => $request->featured_book ? 1 : 0,
                'description' => $request->description,
                'image' => $imagePath,
                'image2' => $imagePaths['image2'],
                'image3' => $imagePaths['image3'],
                'image4' => $imagePaths['image4'],
                'image5' => $imagePaths['image5'],
                'multipleimage' => json_encode($existingImages),
                'meta_slug' => $request->meta_slug,
                'meta_title' => $request->meta_title,
                'canonical_url' => $request->canonical_url,
                'meta_keyword' => $request->meta_keyword,
                'meta_description' => $request->meta_description,
                'product_pdf' => $pdfPath,
                'shipping_type' => $shippingType,
                'shipping_charge' => $shippingCharge,
                'cod_available' => $request->cod_available ? 1 : 0,


            ];

            \Log::info('Update Data:', $updateData);

            $product->update($updateData);

            \Log::info('Product updated successfully');
            // $this->updateSitemap();
            return redirect()->route('showproduct')->with('success', '✅ Product updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Product update error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return back()->withInput()->with('error', 'Error updating product: ' . $e->getMessage());
        }
    }
    public function productDelete($id)
    {
        $product = Product::findOrFail($id);
        $imageFields = ['image', 'image2', 'image3', 'image4', 'image5'];
        foreach ($imageFields as $field) {
            if ($product->$field && file_exists(public_path('userassets/image/product/' . $product->$field))) {
                unlink(public_path('userassets/image/product/' . $product->$field));
            }
        }

        if (!empty($product->multipleimage)) {
            $images = is_array($product->multipleimage)
            ? $product->multipleimage
            : json_decode($product->multipleimage, true);

            if (is_array($images)) {
                foreach ($images as $img) {
                    if (file_exists(public_path('userassets/image/product/' . $img))) {
                        unlink(public_path('userassets/image/product/' . $img));
                    }
                }
            }
        }
        // delete PDF
        if ($product->product_pdf && file_exists(public_path('userassets/image/product/pdf/' . $product->product_pdf))) {
            unlink(public_path('userassets/image/product/pdf/' . $product->product_pdf));
        }
        $product->delete();
        // $this->updateSitemap();
        return redirect()->back()->with('success', '🗑️ Product deleted successfully!');
    }


    public function removeProductImage(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $field = $request->field;


        if (in_array($field, ['image','image2','image3','image4','image5'])) {
            if ($product->$field && file_exists(public_path('userassets/image/product/' . $product->$field))) {
                unlink(public_path('userassets/image/product/' . $product->$field));
            }
            $product->$field = null;
            $product->save();
        }


        if ($field == 'multipleimage') {
            $imgName = $request->image_name;
            $images = $product->multipleimage ?? [];
            $images = array_filter($images, fn($img) => $img !== $imgName);

            if (file_exists(public_path('userassets/image/product/' . $imgName))) {
                unlink(public_path('userassets/image/product/' . $imgName));
            }

            $product->multipleimage = array_values($images);
            $product->save();
        }
        // PDF remove
        if ($field == 'product_pdf') {
            if ($product->product_pdf && file_exists(public_path('userassets/image/product/pdf/' . $product->product_pdf))) {
                unlink(public_path('userassets/image/product/pdf/' . $product->product_pdf));
            }
            $product->product_pdf = null;
            $product->save();
        }

        return redirect()->back()->with('success', '✅ Image removed successfully!');
    }

    public function removeColorImage(Request $request)
    {
        try {
            \Log::info('Remove color image request:', $request->all());

            $request->validate([
                'product_id' => 'required|exists:products,id',
                'color_id' => 'required|exists:sizecolors,id',
            ]);

            $product = Product::findOrFail($request->product_id);
            $colorImages = $product->color_images ?? [];

            \Log::info('Current color images:', $colorImages);

            if (isset($colorImages[$request->color_id])) {
                $imageName = null;

                \Log::info('Image data found:', ['color_id' => $request->color_id, 'data' => $colorImages[$request->color_id]]);

                // Check if it's an array (old format) or string (new format)
                if (is_array($colorImages[$request->color_id]) && isset($colorImages[$request->color_id]['image'])) {
                    $imageName = $colorImages[$request->color_id]['image'];
                } else {
                    $imageName = $colorImages[$request->color_id];
                }

                \Log::info('Image name to delete:', ['imageName' => $imageName]);

                if ($imageName) {
                    // Delete the image file
                    $imagePath = public_path('userassets/image/product/colors/' . $imageName);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                        \Log::info('Image file deleted:', ['path' => $imagePath]);
                    }
                }

                // Remove from the array
                unset($colorImages[$request->color_id]);

                // Also remove from colors array
                $colors = $product->colors ?? [];
                $colors = array_filter($colors, function ($colorId) use ($request) {
                    return $colorId != $request->color_id;
                });
                $colors = array_values($colors); // Reindex array

                // Update the product
                $product->update([
                    'color_images' => $colorImages,
                    'colors' => $colors,
                ]);

                \Log::info('Color image and color removed successfully');

                return response()->json([
                    'success' => true,
                    'message' => 'Color image removed successfully! The color has also been unchecked.',
                ]);
            }

            \Log::warning('Image not found for color_id:', ['color_id' => $request->color_id]);

            return response()->json([
                'success' => false,
                'message' => 'Image not found!',
            ], 404);

        } catch (\Exception $e) {
            \Log::error('Error removing color image: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error removing image: ' . $e->getMessage(),
            ], 500);
        }
    }
    // public function removeColorImage(Request $request)
    // {
    //     try {
    //         \Log::info('Remove color image request:', $request->all());

    //         $request->validate([
    //             'product_id' => 'required|exists:products,id',
    //             'color_id' => 'required|exists:sizecolors,id',
    //         ]);

    //         $product = Product::findOrFail($request->product_id);
    //         $colorImages = $product->color_images ?? [];

    //         \Log::info('Current color images:', $colorImages);

    //         if (isset($colorImages[$request->color_id])) {
    //             $imageName = null;

    //             \Log::info('Image data found:', ['color_id' => $request->color_id, 'data' => $colorImages[$request->color_id]]);

    //             // Check if it's an array (old format) or string (new format)
    //             if (is_array($colorImages[$request->color_id]) && isset($colorImages[$request->color_id]['image'])) {
    //                 $imageName = $colorImages[$request->color_id]['image'];
    //             } else {
    //                 $imageName = $colorImages[$request->color_id];
    //             }

    //             \Log::info('Image name to delete:', ['imageName' => $imageName]);

    //             if ($imageName) {
    //                 // Delete the image file
    //                 $imagePath = public_path('userassets/image/product/colors/' . $imageName);
    //                 if (file_exists($imagePath)) {
    //                     unlink($imagePath);
    //                     \Log::info('Image file deleted:', ['path' => $imagePath]);
    //                 }
    //             }

    //             // Remove from the array
    //             unset($colorImages[$request->color_id]);

    //             // Update the product
    //             $product->update(['color_images' => $colorImages]);

    //             \Log::info('Color image removed successfully');

    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Color image removed successfully!'
    //             ]);
    //         }

    //         \Log::warning('Image not found for color_id:', ['color_id' => $request->color_id]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Image not found!'
    //         ], 404);

    //     } catch (\Exception $e) {
    //         \Log::error('Error removing color image: ' . $e->getMessage());
    //         \Log::error($e->getTraceAsString());

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error removing image: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }
    //new code end


    public function sizecolor()
    {
        $color = Sizecolor::latest()->get();
        return view('admin.sizecolor', compact('color'));
    }

    public function sizecolorStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'type' => 'nullable|string',
        ]);

        $color = new Sizecolor();
        $color->name = $request->name;
        $color->address = $request->address;
        $color->type = $request->type;
        $color->website = $request->website;
        $color->bio = $request->bio;
        $color->status = $request->status ?? 1;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('userassets/image/color'), $imageName);
            $color->image = $imageName;
        }

        $color->save();

        return redirect()->route('sizecolor.create')->with('success', 'authors /publishers created successfully');
    }

    public function sizecolorEdit($id)
    {
        $color = Sizecolor::findOrFail($id);
        return view('admin.editsizecolor', compact('color'));
    }
    public function sizecolorUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'type' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:255',
        ], [
            'image.max' => 'The image is too large. Only images less than 2MB are allowed.', // custom message
            'image.mimes' => 'Only JPG, JPEG, PNG, or SVG images are allowed.', // optional
        ]);
        $color = Sizecolor::findOrFail($id);
        $color->name = $request->name;

        $color->type = $request->type;
        $color->address = $request->address;
        $color->website = $request->website;
        $color->bio = $request->bio;
        $color->status = $request->status ?? 1;

        if ($request->hasFile('image')) {
            if ($color->image && File::exists(public_path('userassets/image/color/' . $color->image))) {
                File::delete(public_path('userassets/image/color/' . $color->image));
            }
            $filename = md5(time() . rand()) . '.' . $request->image->extension();
            $request->image->move(public_path('userassets/image/color'), $filename);
            $color->image = $filename;
        }

        $color->save();

        return redirect()->route('sizecolor.create')->with('success', 'Category updated successfully');
    }

    public function sizecolorDelete($id)
    {
        $color = Sizecolor::findOrFail($id);
        $color->delete();

        return redirect()->route('category')->with('success', 'Category deleted.');
    }

    public function removesizecolorImage($id)
    {
        $color = Sizecolor::findOrFail($id);

        if ($color->image && File::exists(public_path('userassets/image/color/' . $color->image))) {
            File::delete(public_path('userassets/image/color/' . $color->image));
        }

        $color->image = null;
        $color->save();

        return redirect()->back()->with('success', 'Image removed successfully.');
    }






    public function sliderData(Request $request)
    {
        $query = Slider::select(['id', 'type', 'title', 'image', 'status']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('category', function ($row) {
                return $row->type == 1 ? 'Home Page Slider' : ($row->type == 2 ? 'Shop By Trend' : '-');
            })
            ->addColumn('image', function ($row) {
                if ($row->image) {
                    return '<img src="' . url('userassets/image/slider/' . $row->image) . '" width="60" height="60" alt="image">';
                }
                return '<span class="text-muted">No Image</span>';
            })
            ->addColumn('status', function ($row) {
                $badge = $row->status ? 'badge-success' : 'badge-danger';
                $label = $row->status ? 'Active' : 'Inactive';
                return '<a href="' . route('slider.status.toggle', $row->id) . '" class="badge ' . $badge . '">' . $label . '</a>';
            })
            ->addColumn('action', function ($row) {
                return '
                <div class="form-button-action">
                    <a href="' . route('slider.edit', $row->id) . '" class="btn btn-link btn-primary btn-lg" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form action="' . route('slider.delete', $row->id) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button class="btn btn-link btn-danger" type="submit" onclick="return confirm(\'Are you sure to delete?\')" title="Delete">
                            <i class="fa fa-times"></i>
                        </button>
                    </form>
                </div>';
            })
            ->rawColumns(['image', 'status', 'action'])
            ->make(true);
    }

    public function sliderCreate()
    {
        return view('admin.slider'); // subcategories compact hataao
    }


    public function sliderStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'url' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $slider = new slider(); // Replace with your actual model
        $slider->title = $request->title;

        $slider->type = $request->type;
        $slider->url = $request->url;
        $slider->description = $request->description;
        $slider->status = $request->status;

        // ✅ Handle Image Upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $hashedName = $image->hashName();
            $image->move(public_path('userassets/image/slider'), $hashedName);
            $slider->image = $hashedName;
        }
        $slider->save();

        return redirect()->route('slider.create')->with('success', 'slider created successfully!');
    }



    public function sliderEdit($id)
    {

        $slider = slider::find($id);

        return view('admin.editslider', compact('slider'));
    }
    public function sliderUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'nullable|string',
            'url' => 'nullable|string',

            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $slider = slider::findOrFail($id);
        $slider->title = $request->title;
        $slider->url = $request->url;

        $slider->type = $request->type;
        $slider->description = $request->description;

        if ($request->hasFile('image')) {
            if ($slider->image && file_exists(public_path('userassets/image/slider/' . $slider->image))) {
                unlink(public_path('userassets/image/slider/' . $slider->image));
            }

            $image = $request->file('image');
            $imageName = $image->hashName();
            $image->move(public_path('userassets/image/slider'), $imageName);
            $slider->image = $imageName;
        }


        $slider->save();

        return redirect()->route('slider.create')->with('success', 'Slider updated successfully.');
    }
    public function sliderDelete($id)
    {
        $slider = slider::findOrFail($id);

        if ($slider->image && file_exists(public_path('userassets/image/slider/' . $slider->image))) {
            unlink(public_path('userassets/image/slider/' . $slider->image));
        }

        $slider->delete();

        return redirect()->route('slider.create')->with('success', 'Slider deleted successfully.');
    }
    public function removeSliderImage($id)
    {
        $slider = slider::findOrFail($id);

        if ($slider->image && file_exists(public_path('userassets/image/slider/' . $slider->image))) {
            unlink(public_path('userassets/image/slider/' . $slider->image));
            $slider->image = null;
            $slider->save();
        }

        return redirect()->back()->with('success', 'Image removed successfully.');
    }
    public function toggleSliderStatus($id)
    {
        $slider = slider::findOrFail($id);
        $slider->status = $slider->status ? 0 : 1; // Toggle the status (1 → 0, 0 → 1)
        $slider->save();

        return redirect()->back()->with('success', 'Slider status updated successfully.');
    }



    //link controller code start
    public function linkCreate()
    {
        $links = Link::latest()->get();
        return view('admin.link', compact('links'));
    }

    public function linkStore(Request $request)
    {
        $request->validate([

            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            'link' => 'required|string',
        ]);

        $link = new Link();
        $link->link = $request->link;

        $link->status = $request->status;



        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->hashName();
            $image->move(public_path('userassets/image/link'), $imageName);
            $link->image = $imageName;
        }


        $link->save();

        return redirect()->route('link.create')->with('success', 'Link created successfully');

    }

    public function linkEdit($id)
    {
        $link = Link::findOrFail($id);
        return view('admin.editlink', compact('link'));
    }
    public function linkUpdate(Request $request, $id)
    {
        $request->validate([

            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            'link' => 'nullable|string',
        ]);

        $link = Link::findOrFail($id);

        $link->link = $request->link;


        if ($request->hasFile('image')) {
            if ($link->image && File::exists(public_path('userassets/image/link/' . $link->image))) {
                File::delete(public_path('userassets/image/link/' . $link->image));
            }
            $image = $request->file('image');
            $filename = $image->hashName();
            $request->image->move(public_path('userassets/image/link'), $filename);
            $link->image = $filename;
        }


        $link->save();

        return redirect()->route('link.create')->with('success', 'Link updated successfully');
    }

    public function linkDelete($id)
    {
        $link = Link::findOrFail($id);
        $link->delete();

        return redirect()->route('link.create')->with('success', 'Category deleted.');
    }

    public function togglelinkStatus($id)
    {
        $link = Link::findOrFail($id);
        $link->status = $link->status ? 0 : 1; // Toggle the status (1 → 0, 0 → 1)
        $link->save();

        return redirect()->back()->with('success', 'link status updated successfully.');
    }

    public function removelinkImage($id)
    {
        $link = Link::findOrFail($id);

        if ($link->image && file_exists(public_path('userassets/image/link/' . $link->image))) {
            unlink(public_path('userassets/image/link/' . $link->image));
            $link->image = null;
            $link->save();
        }

        return redirect()->back()->with('success', 'Image removed successfully.');
    }
    public function settingEdit()
    {
        $setting = Setting::findOrFail(1);
        return view('admin.editsetting', compact('setting'));
    }

    public function settingUpdate(Request $request)
    {
        $setting = Setting::findOrFail(1);

        // ✅ Validation
        $request->validate([
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // ✅ Update basic fields
        $setting->email = $request->email;
        $setting->phone = $request->phone;
        $setting->address = $request->address;
        $setting->facebook = $request->facebook;
        $setting->instagram = $request->instagram;
        $setting->linkedin = $request->linkedin;
        $setting->youtube = $request->youtube;

        // ✅ Handle Image Upload
        if ($request->hasFile('image')) {
            // old image delete
            if ($setting->image && file_exists(public_path('userassets/image/' . $setting->image))) {
                unlink(public_path('userassets/image/' . $setting->image));
            }


            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('userassets/image'), $filename);

            $setting->image = $filename;
        }


        if ($setting->save()) {
            return redirect()->back()->with('success', '✅ Setting updated successfully!');
        } else {
            return redirect()->back()->with('error', '❌ Failed to update settings. Please try again.');
        }
    }
    public function removeSettingImage($id)
    {
        $setting = Setting::findOrFail($id);

        if ($setting->image && file_exists(public_path('userassets/image/' . $setting->image))) {
            unlink(public_path('userassets/image/' . $setting->image));
        }

        $setting->image = null;
        $setting->save();

        return redirect()->back()->with('success', '🗑️ Image removed successfully!');
    }

    // public function showhomepagebanner(){
    //     $banner = Setting::where('id', '!=', 1)->get();

    //     return view('admin.showhomepagebanner', compact('banner'));
    // }
    // // Show Edit Form
    // public function homepagebannerEdit($id)
    // {
    //     $setting = Setting::findOrFail($id);

    //     $categories = Category::select('id', 'title')->get();
    //     $subcategories = Subcategory::select('id', 'title')->get();
    //     $childSubcategories = ChildSubcategory::select('id', 'title')->get();

    //     return view('admin.homepagebannerEdit', compact(
    //         'setting',
    //         'categories',
    //         'subcategories',
    //         'childSubcategories'
    //     ));
    // }




    public function showhomepagebanner()
    {
        $banner = Setting::where('id', '!=', 1)->get();

        return view('admin.showhomepagebanner', compact('banner'));
    }
    // Show edit form
    public function homepagebannerEdit($id)
    {
        $banner = Setting::findOrFail($id);
        $categories = Category::all();
        $subcategories = Subcategory::all();
        $childSubcategories = ChildSubcategory::all();

        return view('admin.homepagebannerEdit', compact('banner', 'categories', 'subcategories', 'childSubcategories'));
    }

    // Update banner
    public function homepagebannerUpdate(Request $request, $id)
    {
        $banner = Setting::findOrFail($id);

        $request->validate([
            'type' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $banner->type = $request->type;
        $banner->url = $request->url;
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($banner->image && file_exists(public_path('userassets/image/' . $banner->image))) {
                unlink(public_path('userassets/image/' . $banner->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('userassets/image'), $filename);
            $banner->image = $filename;
        }

        $banner->save();

        return redirect()->route('showhomepagebanner')->with('success', 'Banner updated successfully.');
    }

    // Remove Banner Image
    public function removehomepagebannerImage($id)
    {
        $setting = Setting::findOrFail($id);

        if ($setting->image && file_exists(public_path('userassets/image/' . $setting->image))) {
            unlink(public_path('userassets/image/' . $setting->image));
        }

        $setting->image = null;
        $setting->save();

        return redirect()->back()->with('success', '🗑️ Image removed successfully!');
    }



    public function showCheckAvailability()
    {
        return view('admin.import_checkavailability');
    }

    public function checkAvailabilityData(Request $request)
    {
        $query = CheckAvailability::select(['id', 'pincode', 'status']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                $badge = $row->status ? 'badge-success' : 'badge-danger';
                $label = $row->status ? 'Active' : 'Inactive';
                return '<a href="' . route('checkavailability.status.toggle', $row->id) . '" class="badge ' . $badge . '">' . $label . '</a>';
            })
            ->addColumn('action', function ($row) {
                return '<form action="' . route('checkavailability.destroy', $row->id) . '" method="POST" style="display:inline;">
                ' . csrf_field() . '
                ' . method_field('DELETE') . '
                <button class="btn btn-link btn-danger" type="submit" onclick="return confirm(\'Delete?\')" title="Delete">
                    <i class="fa fa-times"></i>
                </button>
            </form>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }



    public function importCheckAvailability(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new CheckAvailabilityImport(), $request->file('file'));
            return back()->with('success', 'Excel data imported successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
    public function togglecheckavailabilityStatus($id)
    {
        $link = CheckAvailability::findOrFail($id);
        $link->status = $link->status ? 0 : 1;
        $link->save();

        return redirect()->back()->with('success', 'aviabilty status updated successfully.');
    }
    public function checkavailabilityDelete($id)
    {
        $l = CheckAvailability::findOrFail($id);
        $l->delete();

        return redirect()->route('checkavailability.list')->with('success', 'Check Availability deleted.');
    }
    public function downloadSample()
    {
        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=sample_check_availability.csv',
        ];

        $columns = ['pincode', 'status'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            // sample data
            fputcsv($file, ['110001', '1']);
            fputcsv($file, ['110002', '1']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    //link controller code end

    //page start

    public function pageData(Request $request)
    {
        $query = SimplePage::select(['id', 'title', 'image', 'status']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                if ($row->image) {
                    return '<img src="' . url('userassets/image/simple/' . $row->image) . '" width="60" height="60" alt="image">';
                }
                return '<span class="text-muted">No Image</span>';
            })
            ->addColumn('status', function ($row) {
                $badge = $row->status ? 'badge-success' : 'badge-danger';
                $label = $row->status ? 'Active' : 'Inactive';
                return '<a href="' . route('page.status.toggle', $row->id) . '" class="badge ' . $badge . '">' . $label . '</a>';
            })
            ->addColumn('action', function ($row) {
                return '
                <div class="form-button-action">
                    <a href="' . route('page.edit', $row->id) . '" class="btn btn-link btn-primary btn-lg" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="' . route('page.delete', $row->id) . '" class="btn btn-link btn-danger" title="Delete"
                        onclick="return confirm(\'Are you sure to delete?\')">
                        <i class="fa fa-times"></i>
                    </a>
                </div>';
            })
            ->rawColumns(['image', 'status', 'action'])
            ->make(true);
    }
    public function page()
    {
        $subpage = SimplePage::latest()->get();

        return view('admin.simple.page');
    }

    public function pageStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'short_content' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'alt_tag' => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ]);

        $page = new SimplePage();
        $page->title = $request->title;
        $page->slug = Str::slug($request->title);
        $page->short_content = $request->short_content;
        $page->alt_tag = $request->alt_tag;
        $page->content = $request->content;
        $page->status = $request->status;

        // ✅ Handle Image Upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $hashedName = $image->hashName();
            $image->move(public_path('userassets/image/simple'), $hashedName);
            $page->image = $hashedName;
        }
        $page->save();

        return redirect()->route('page')->with('success', 'Page created successfully!');
    }



    public function pageEdit($id)
    {

        $page = SimplePage::find($id);

        return view('admin.simple.editpage', compact('page'));
    }
    public function pageUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'short_content' => 'nullable|string',
            'content' => 'nullable|string',
            'alt_tag' => 'nullable|string|max:255',

            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $page = SimplePage::findOrFail($id);
        $page->title = $request->title;
        $page->slug = $request->slug;
        $page->short_content = $request->short_content;
        $page->alt_tag = $request->alt_tag;
        $page->content = $request->content;

        if ($request->hasFile('image')) {
            if ($page->image && file_exists(public_path('userassets/image/simple/' . $page->image))) {
                unlink(public_path('userassets/image/slider/' . $page->image));
            }

            $image = $request->file('image');
            $imageName = $image->hashName();
            $image->move(public_path('userassets/image/simple'), $imageName);
            $page->image = $imageName;
        }
        $page->save();

        return redirect()->route('page')->with('success', 'Page updated successfully.');
    }
    public function pageDelete($id)
    {
        $page = SimplePage::findOrFail($id);

        if ($page->image && file_exists(public_path('userassets/image/simple/' . $page->image))) {
            unlink(public_path('userassets/image/simple/' . $page->image));
        }

        $page->delete();

        return redirect()->route('page')->with('success', 'Page deleted successfully.');
    }
    public function removepageImage($id)
    {
        $page = SimplePage::findOrFail($id);

        if ($page->image && file_exists(public_path('userassets/image/simple/' . $page->image))) {
            unlink(public_path('userassets/image/simple/' . $page->image));
            $page->image = null;
            $page->save();
        }

        return redirect()->back()->with('success', 'Image removed successfully.');
    }
    public function togglepageStatus($id)
    {
        $page = SimplePage::findOrFail($id);
        $page->status = $page->status ? 0 : 1; // Toggle the status (1 → 0, 0 → 1)
        $page->save();

        return redirect()->back()->with('success', 'Page status updated successfully.');
    }



    //page End

    //Blog Start
    public function blogData(Request $request)
    {
        $query = Blog::select(['id', 'title', 'image', 'status']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                if ($row->image) {
                    return '<img src="' . url('userassets/image/blog/' . $row->image) . '" width="60" height="60" alt="image">';
                }
                return '<span class="text-muted">No Image</span>';
            })
            ->addColumn('status', function ($row) {
                $badge = $row->status ? 'badge-success' : 'badge-danger';
                $label = $row->status ? 'Active' : 'Inactive';
                return '<a href="' . route('blog.status.toggle', $row->id) . '" class="badge ' . $badge . '">' . $label . '</a>';
            })
            ->addColumn('action', function ($row) {
                return '
                <div class="form-button-action">
                    <a href="' . route('blog.edit', $row->id) . '" class="btn btn-link btn-primary btn-lg" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form action="' . route('blog.delete', $row->id) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button class="btn btn-link btn-danger" type="submit" onclick="return confirm(\'Are you sure to delete?\')" title="Delete">
                            <i class="fa fa-times"></i>
                        </button>
                    </form>
                </div>';
            })
            ->rawColumns(['image', 'status', 'action'])
            ->make(true);
    }
    public function blog()
    {
        $subblog = Blog::latest()->get();

        return view('admin.simple.blog');
    }

    public function blogStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:blogs,title',
            'short_content' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'alt_tag' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'metatitle' => 'nullable|string|max:255',
            'connitialtag' => 'nullable|string|max:255',
            'imageurl' => 'nullable|string|max:255',
            'metadescription' => 'nullable|string',
        ]);

        $blog = new Blog();
        $blog->title = $request->title;
        $blog->slug = Str::slug($request->title);
        $blog->short_content = $request->short_content;
        $blog->alt_tag = $request->alt_tag;
        $blog->content = $request->content;
        $blog->metatitle = $request->metatitle;
        $blog->metadescription = $request->metadescription;
        $blog->connitialtag = $request->connitialtag;
        $blog->imageurl = $request->imageurl;
        $blog->status = $request->status;

        // ✅ Handle Image Upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $hashedName = $image->hashName();
            $image->move(public_path('userassets/image/blog'), $hashedName);
            $blog->image = $hashedName;
        }
        $blog->save();

        return redirect()->route('admin.blog')->with('success', 'Blog created successfully!');
    }



    public function blogEdit($id)
    {

        $blog = Blog::find($id);

        return view('admin.simple.editblog', compact('blog'));
    }
    public function blogUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'short_content' => 'nullable|string',
            'content' => 'nullable|string',
            'alt_tag' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'metatitle' => 'nullable|string|max:255',
            'connitialtag' => 'nullable|string|max:255',
            'imageurl' => 'nullable|string|max:255',
            'metadescription' => 'nullable|string',
        ]);

        $blog = Blog::findOrFail($id);
        $blog->title = $request->title;
        $blog->slug = $request->slug;
        $blog->short_content = $request->short_content;
        $blog->alt_tag = $request->alt_tag;
        $blog->content = $request->content;
        $blog->metatitle = $request->metatitle;
        $blog->connitialtag = $request->connitialtag;
        $blog->imageurl = $request->imageurl;
        $blog->metadescription = $request->metadescription;

        if ($request->hasFile('image')) {
            if ($blog->image && file_exists(public_path('userassets/image/blog/' . $blog->image))) {
                unlink(public_path('userassets/image/slider/' . $blog->image));
            }

            $image = $request->file('image');
            $imageName = $image->hashName();
            $image->move(public_path('userassets/image/blog'), $imageName);
            $blog->image = $imageName;
        }
        $blog->save();

        return redirect()->route('admin.blog')->with('success', 'Blog updated successfully.');
    }
    public function blogDelete($id)
    {
        $blog = Blog::findOrFail($id);

        if ($blog->image && file_exists(public_path('userassets/image/blog/' . $blog->image))) {
            unlink(public_path('userassets/image/blog/' . $blog->image));
        }

        $blog->delete();

        return redirect()->route('admin.blog')->with('success', 'Blog deleted successfully.');
    }
    public function removeblogImage($id)
    {
        $blog = Blog::findOrFail($id);

        if ($blog->image && file_exists(public_path('userassets/image/blog/' . $blog->image))) {
            unlink(public_path('userassets/image/blog/' . $blog->image));
            $blog->image = null;
            $blog->save();
        }

        return redirect()->back()->with('success', 'Image removed successfully.');
    }
    public function toggleblogStatus($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->status = $blog->status ? 0 : 1; // Toggle the status (1 → 0, 0 → 1)
        $blog->save();

        return redirect()->back()->with('success', 'Blog status updated successfully.');
    }



    public function seoData(Request $request)
    {
        $query = Seo::select(['id', 'meta_slug', 'meta_title', 'canonical_url']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
                <div class="form-button-action">
                    <a href="' . route('seo.edit', $row->id) . '" class="btn btn-link btn-primary btn-lg" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form action="' . route('seo.delete', $row->id) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button class="btn btn-link btn-danger" type="submit" onclick="return confirm(\'Are you sure to delete?\')" title="Delete">
                            <i class="fa fa-times"></i>
                        </button>
                    </form>
                </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function showseo()
    {

        $showseo = Seo::get();

        return view('admin.showseo');
    }
    public function seo()
    {

        return view('admin.seo');
    }
    public function storeseo(Request $request)
    {
        $request->validate([
            'meta_title' => 'required|string|max:255',
            'meta_slug' => 'required|string|max:255',
            'canonical_url' => 'nullable|string|max:255',
            'image_url' => 'nullable|string|max:255',
            'meta_keyword' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $seo = new Seo(); // Replace with your actual model
        $seo->meta_title = $request->meta_title;
        $seo->meta_slug = $request->meta_slug;
        $seo->canonical_url = $request->canonical_url;
        $seo->image_url = $request->image_url;
        $seo->meta_keyword = $request->meta_keyword;
        $seo->meta_description = $request->meta_description;

        $seo->save();

        return redirect()->route('showseo')->with('success', 'Seo created successfully!');
    }
    public function seoEdit($id)
    {

        $seo = Seo::find($id);

        return view('admin.editseo', compact('seo'));
    }
    public function seoUpdate(Request $request, $id)
    {
        $request->validate([
            'meta_slug' => 'nullable|string|max:255',
            'meta_keyword' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|string|max:255',
            'image_url' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $seo = Seo::findOrFail($id);
        $seo->meta_title = $request->meta_title;
        $seo->meta_slug = $request->meta_slug;
        $seo->meta_keyword = $request->meta_keyword;
        $seo->canonical_url = $request->canonical_url;
        $seo->image_url = $request->image_url;
        $seo->meta_description = $request->meta_description;

        $seo->save();

        return redirect()->route('showseo')->with('success', 'Seo updated successfully.');
    }
    public function seoDelete($id)
    {
        $seo = Seo::findOrFail($id);



        $seo->delete();

        return redirect()->route('showseo')->with('success', 'Seo deleted successfully.');
    }
    //End Blog
    public function contactData(Request $request)
    {
        $query = Contact::select(['id', 'name', 'email', 'phone', 'message']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }
    public function contactshow()
    {
        $Contactform = Contact::orderBy('id', 'desc')->get();
        return view('admin.contactform');
    }
    public function customerData(Request $request)
    {
        $query = User::where('id', '!=', 1)->select(['id', 'name', 'email', 'phone']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }
    public function customershow()
    {
        $Contactform = User::where('id', '!=', 1)->orderBy('id', 'desc')->get();
        return view('admin.customerform');
    }

    public function printingData(Request $request)
    {
        $query = PrintOrder::select([
            'id', 'name', 'email', 'phone', 'file_path',
            'paper_size', 'print_type', 'copies', 'pages',
            'total_amount', 'paid_amount', 'remaining_amount',
            'payment_status', 'remaining_payment_mode', 'created_at',
        ]);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('file', function ($row) {
                return '<a href="' . asset('uploads/print_orders/' . $row->file_path) . '" target="_blank" class="btn btn-sm btn-info">View File</a>';
            })
            ->addColumn('total_amount', function ($row) {
                return '₹' . $row->total_amount;
            })
            ->addColumn('paid_amount', function ($row) {
                return '₹' . $row->paid_amount;
            })
            ->addColumn('remaining_amount', function ($row) {
                return '₹' . $row->remaining_amount;
            })
            ->addColumn('payment', function ($row) {
                if ($row->payment_status != 'paid') {
                    return '
                    <form action="' . route('admin.payment.mode', $row->id) . '" method="POST">
                        ' . csrf_field() . '
                        <button name="mode" value="offline" class="btn btn-success btn-sm">Offline Paid</button>
                        <button name="mode" value="online" class="btn btn-primary btn-sm">Send Link</button>
                    </form>';
                }
                return '<span class="badge bg-success">Paid (' . ucfirst($row->remaining_payment_mode) . ')</span>';
            })
            ->addColumn('date', function ($row) {
                return $row->created_at->format('d-m-Y');
            })
            ->rawColumns(['file', 'payment'])
            ->make(true);
    }
    public function printingdocunmentshow()
    {
        $Contactform = PrintOrder::orderBy('id', 'desc')->get();
        return view('admin.printingdocunmentshow', compact('Contactform'));
    }

    public function handlePaymentMode(Request $request, $id)
    {
        $order = PrintOrder::findOrFail($id);

        if ($request->mode == 'offline') {

            $order->update([
                'payment_status' => 'paid',
                'remaining_payment_mode' => 'offline',
                'paid_amount' => $order->total_amount,
                'remaining_amount' => 0,
                'remaining_paid_at' => now(),
            ]);

            return back()->with('success', 'Offline payment done');

        } elseif ($request->mode == 'online') {

            // SEND PAYMENT LINK EMAIL
            // Mail::raw("
            //     Pay Remaining Amount

            //     Order No: {$order->order_number}
            //     Remaining: ₹{$order->remaining_amount}

            //     Pay Now:
            //     " . url('/pay-remaining/'.$order->id) . "
            // ", function($message) use ($order) {
            //     $message->to($order->email)
            //             ->subject('Pay Remaining Amount');
            // });
            SendRemainingPaymentJob::dispatch($order);
            (new SendRemainingPaymentJob($order))->handle();
            return back()->with('success', 'Payment link sent');
        }
    }

}
