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
use App\Models\Newsletter;

class SimpleFileController extends Controller
{
    public function contactus()
    {
        $navcategories = Category::with(['subcategories.childSubcategories'])
               ->where('status', 1)
               ->get();
        $linkss = Link::where('status', 1)->get();
        return view('simplefile.contactus', compact('navcategories', 'linkss'));
    }
    public function aboutus()
    {
        $navcategories = Category::with(['subcategories.childSubcategories'])->where('status', 1)->get();
        $linkss = Link::where('status', 1)->get();
        $aboutus = SimplePage::where('id', 1)->where('status', 1)->first();
        $banner = Setting::where('type', 3)->first();
        return view('simplefile.aboutus', compact('navcategories', 'linkss', 'aboutus', 'banner'));
    }
    public function helpsupport()
    {
        $navcategories = Category::with(['subcategories.childSubcategories'])->where('status', 1)->get();
        $linkss = Link::where('status', 1)->get();
        $helpsupport = SimplePage::where('id', 2)->where('status', 1)->first();
        $banner = Setting::where('type', 3)->first();
        return view('simplefile.helpsupport', compact('navcategories', 'linkss', 'helpsupport', 'banner'));
    }
    public function returnpolicy()
    {
        $navcategories = Category::with(['subcategories.childSubcategories'])->where('status', 1)->get();
        $linkss = Link::where('status', 1)->get();
        $returnpolicy = SimplePage::where('id', 3)->where('status', 1)->first();
        $banner = Setting::where('type', 3)->first();
        return view('simplefile.returnpolicy', compact('navcategories', 'linkss', 'returnpolicy', 'banner'));
    }
    public function shippingreturns()
    {
        $navcategories = Category::with(['subcategories.childSubcategories'])->where('status', 1)->get();
        $linkss = Link::where('status', 1)->get();
        $shippingreturns = SimplePage::where('id', 4)->where('status', 1)->first();
        $banner = Setting::where('type', 3)->first();
        return view('simplefile.shippingreturns', compact('navcategories', 'linkss', 'shippingreturns', 'banner'));
    }
    public function privacypolicy()
    {
        $navcategories = Category::with(['subcategories.childSubcategories'])->where('status', 1)->get();
        $linkss = Link::where('status', 1)->get();
        $privacypolicy = SimplePage::where('id', 5)->where('status', 1)->first();
        $banner = Setting::where('type', 3)->first();
        return view('simplefile.privacypolicy', compact('navcategories', 'linkss', 'privacypolicy', 'banner'));
    }


    public function blog(Request $request)
    {
        $navcategories = Category::with(['subcategories.childSubcategories'])->where('status', 1)->get();
        $linkss = Link::where('status', 1)->get();
        $banner = Setting::where('type', 3)->first();
        $blogdata = Blog::paginate(12);

        if ($request->ajax()) {
            return view('partials.blog-list', compact('blogdata'))->render();
        }

        return view('simplefile.blogpage', compact('navcategories', 'linkss', 'blogdata', 'banner'));
    }

    public function blogDetails($slug)
    {
        $blog = Blog::where('slug', $slug)->where('status', 1)->firstOrFail();
        $navcategories = Category::with(['subcategories.childSubcategories'])
            ->where('status', 1)
            ->get();
        $linkss = Link::where('status', 1)->get();

        return view('simplefile.blogdetails', compact('blog', 'navcategories', 'linkss'));
    }

    public function arrivals(Request $request)
    {
        $navcategories = Category::with(['subcategories.childSubcategories'])->where('status', 1)->get();
        $linkss = Link::where('status', 1)->get();

        $newarrivals = Product::where('new_arrivals', 1)->where('status', 1)->paginate(15);

        if ($request->ajax()) {


            return view(
                'partials.newarrivals-list',
                compact('newarrivals'),
            )->render();
        }

        return view('simplefile.newarrivals', compact('navcategories', 'linkss', 'newarrivals'));
    }
    public function seller(Request $request)
    {
        $navcategories = Category::with(['subcategories.childSubcategories'])->where('status', 1)->get();
        $linkss = Link::where('status', 1)->get();

        $bestseller = Product::where('product_on_sale', 1)->where('status', 1)->paginate(15);

        if ($request->ajax()) {

            return view(
                'partials.seller-list',
                compact('bestseller'),
            )->render();
        }
        return view('simplefile.seller', compact('navcategories', 'linkss', 'bestseller'));
    }
    public function examcorner(Request $request)
    {
        $navcategories = Category::with(['subcategories.childSubcategories'])->where('status', 1)->get();
        $linkss = Link::where('status', 1)->get();

        $examcorner = Product::where('exam_corner', 1)->where('status', 1)->paginate(15);

        if ($request->ajax()) {
            return view(
                'partials.examcorner-list',
                compact('examcorner'),
            )->render();
        }

        return view('simplefile.examcorner', compact('navcategories', 'linkss', 'examcorner'));
    }
    public function featuredbooks(Request $request)
    {
        $navcategories = Category::with(['subcategories.childSubcategories'])->where('status', 1)->get();
        $linkss = Link::where('status', 1)->get();
        $featuredbooks = Product::where('featured_book', 1)->where('status', 1)->paginate(15);
        if ($request->ajax()) {
            return view(
                'partials.featuredbooks-list',
                compact('featuredbooks'),
            )->render();
        }

        return view('simplefile.featuredbooks', compact('navcategories', 'linkss', 'featuredbooks'));
    }
    public function indexWishlist()
    {
        $navcategories = Category::with(['subcategories.childSubcategories'])->where('status', 1)->get();
        $linkss = Link::where('status', 1)->get();
        $guestToken = Cookie::get('guest_token');

        $wishlists = Wishlist::with('product')
            ->where('guest_token', $guestToken)
            ->latest()
            ->get();

        return view('simplefile.test', compact('navcategories', 'linkss', 'wishlists'));
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletters,email',
        ]);

        Newsletter::create([
            'email' => $request->email,
        ]);

        return response()->json([
            'message' => 'Subscribed successfully!',
        ]);
    }
}
