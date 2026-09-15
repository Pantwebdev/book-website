<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DiscountCodeController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PrintUploadController;
use App\Http\Controllers\SimpleFileController;
use App\Models\Product;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\ChildSubcategory;

//Route::get('/', function () {
//   return view('welcome');
//});





Route::get('/hash', function () {
    return bcrypt('12345678');
});


// 👇👇👇👇👇👇  ye product index karna ke liye funtion hai ye comment rahega delete nhi karna hai is function ko jab jarur hogi tab uncomment karna hai 👇👇👇👇👇👇 
// Route::get('/generate-sitemap', function () {

//     $sitemap = Sitemap::create();

//     // Static pages
//     $sitemap->add(Url::create('/')->setPriority(1.0));
//     $sitemap->add(Url::create('/shop')->setPriority(0.9));

//     // Categories
//     Category::chunk(100, function ($categories) use ($sitemap) {
//         foreach ($categories as $category) {
//             if ($category->slug) {
//                 $sitemap->add(
//                     Url::create("/{$category->slug}")
//                         ->setPriority(0.8)
//                         ->setLastModificationDate($category->updated_at),
//                 );
//             }
//         }
//     });

//     // Subcategories
//   Subcategory::with('category')->chunk(100, function ($subcategories) use ($sitemap) {
//         foreach ($subcategories as $subcategory) {
//             if ($subcategory->slug && $subcategory->category) {
//                 $sitemap->add(
//                     Url::create("/{$subcategory->category->slug}/{$subcategory->slug}")
//                         ->setPriority(0.7)
//                         ->setLastModificationDate($subcategory->updated_at),
//                 );
//             }
//         }
//     });

//     // Child Categories
//   ChildSubcategory::with(['category', 'subcategory'])->chunk(100, function ($childcategories) use ($sitemap) {
//         foreach ($childcategories as $child) {
//             if ($child->slug && $child->category && $child->subcategory) {
//                 $sitemap->add(
//                     Url::create("/{$child->category->slug}/{$child->subcategory->slug}/{$child->slug}")
//                         ->setPriority(0.6)
//                         ->setLastModificationDate($child->updated_at),
//                 );
//             }
//         }
//     });

//     // Products
//     Product::chunk(100, function ($products) use ($sitemap) {
//         foreach ($products as $product) {
//             if ($product->slug) {
//                 $sitemap->add(
//                     Url::create("/product/{$product->slug}")
//                         ->setPriority(0.7)
//                         ->setLastModificationDate($product->updated_at),
//                 );
//             }
//         }
//     });

//     $sitemap->writeToFile(public_path('sitemap.xml'));

//     return "Sitemap Generated Successfully";
// });

Route::prefix('admin')->group(function () {

    Route::get('login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::middleware(['auth:web', 'admin'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('password/update', [AuthController::class, 'updateForm'])->name('password.update.form');
        Route::post('password/update', [AuthController::class, 'update'])->name('password.update');
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('form', [AdminController::class, 'form'])->name('form');


        Route::get('/category/data', [AdminController::class, 'categoryData'])->name('category.data');
        Route::get('/category', [AdminController::class, 'category'])->name('category');
        Route::get('/category/create', [AdminController::class, 'categoryCreate'])->name('category.create');
        Route::post('/category/store', [AdminController::class, 'categoryStore'])->name('category.store');
        Route::get('/category/edit/{id}', [AdminController::class, 'categoryEdit'])->name('category.edit');
        Route::post('/category/update/{id}', [AdminController::class, 'categoryUpdate'])->name('category.update');
        Route::delete('/category/delete/{id}', [AdminController::class, 'categoryDelete'])->name('category.delete');
        Route::get('/category/image-remove/{id}', [AdminController::class, 'removeCategoryImage'])->name('category.image.remove');
        Route::get('/category/remove-image/{id}/{type?}', [AdminController::class, 'removeCategoryImage'])->name('category.image.remove');

        Route::get('/category/status-toggle/{id}', [AdminController::class, 'togglecategoryStatus'])->name('category.status.toggle');
        Route::get('/category/colaj-toggle/{id}', [AdminController::class, 'togglecolajStatus'])->name('colaj.status.toggle');
        Route::post('/category/collaj-toggle', [AdminController::class, 'togglecolajStatus'])->name('colaj.status.toggle');
        Route::post('/category/explore-toggle', [AdminController::class, 'toggleexploreStatus'])->name('explore.status.toggle');
        Route::post('/category/collection-toggle', [AdminController::class, 'togglecollectionStatus'])->name('collection.status.toggle');

        Route::get('/subcategory/data', [AdminController::class, 'subcategoryData'])->name('subcategory.data');
        Route::get('/subcategory', [AdminController::class, 'subcategory'])->name('subcategory');
        Route::get('/subcategory/create', [AdminController::class, 'subcategoryCreate'])->name('subcategory.create');
        Route::post('/subcategory/store', [AdminController::class, 'subcategoryStore'])->name('subcategory.store');
        Route::get('/subcategory/edit/{id}', [AdminController::class, 'subcategoryEdit'])->name('subcategory.edit');
        Route::post('/subcategory/update/{id}', [AdminController::class, 'subcategoryUpdate'])->name('subcategory.update');
        Route::delete('/subcategory/delete/{id}', [AdminController::class, 'subcategoryDelete'])->name('subcategory.delete');
        Route::get('/subcategory/status-toggle/{id}', [AdminController::class, 'toggleSubcategoryStatus'])->name('subcategory.status.toggle');
        Route::post('/subcategory/collaj-toggle', [AdminController::class, 'togglesubcolajStatus'])->name('subcolaj.status.toggle');
        Route::post('/subcategory/explore-toggle', [AdminController::class, 'togglesubexploreStatus'])->name('subexplore.status.toggle');

        Route::get('/subcategory/image-remove/{id}', [AdminController::class, 'removeSubcategoryImage'])->name('subcategory.image.remove');
        Route::get('/subcategory/explore-image/remove/{id}', [AdminController::class, 'removeExploreImage'])->name('subcategory.explore.image.remove');


        Route::get('/childsubcategory', [AdminController::class, 'childsubcategory'])->name('childsubcategory');
        // Fetch subcategories for a selected category
        Route::get('/get-subcategories/{category_id}', [AdminController::class, 'getSubcategoriesByCategory']);
        Route::get('/get-childsubcategories/{subcategory_id}', [AdminController::class, 'getChildSubcategoriesBySubcategory']);



        Route::get('/childsubcategory/create', [AdminController::class, 'childsubcategoryCreate'])->name('childsubcategory.create');
        Route::post('/childsubcategory/store', [AdminController::class, 'childsubcategoryStore'])->name('childsubcategory.store');
        Route::get('/childsubcategory', [AdminController::class, 'childsubcategory'])->name('childsubcategory');
        Route::get('/childsubcategory/data', [AdminController::class, 'childsubcategoryData'])->name('childsubcategory.data');
        Route::get('/childsubcategory/edit/{id}', [AdminController::class, 'childsubcategoryEdit'])->name('childsubcategory.edit');
        Route::post('/childsubcategory/update/{id}', [AdminController::class, 'childsubcategoryUpdate'])->name('childsubcategory.update');
        Route::delete('/childsubcategory/delete/{id}', [AdminController::class, 'childsubcategoryDelete'])->name('childsubcategory.delete');
        Route::get('/childsubcategory/status-toggle/{id}', [AdminController::class, 'togglechildsubcategoryStatus'])->name('childsubcategory.status.toggle');
        Route::post('/childsubcategory/collection-toggle', [AdminController::class, 'togglechildsubcollectionStatus'])->name('childsubcollection.status.toggle');

        Route::get('/childsubcategory/image-remove/{id}', [AdminController::class, 'removechildsubcategoryImage'])->name('childsubcategory.image.remove');
        Route::get('/subcategory/collection-image/remove/{id}', [AdminController::class, 'removecollectionImage'])->name('childsubcategory.collection.image.remove');

        Route::get('/author/create', [AdminController::class, 'sizecolor'])->name('sizecolor.create');
        Route::post('/author/store', [AdminController::class, 'sizecolorStore'])->name('sizecolor.store');
        Route::get('/author/edit/{id}', [AdminController::class, 'sizecolorEdit'])->name('sizecolor.edit');
        Route::post('/author/update/{id}', [AdminController::class, 'sizecolorUpdate'])->name('sizecolor.update');
        Route::get('/author/delete/{id}', [AdminController::class, 'sizecolorDelete'])->name('sizecolor.delete');
        Route::get('/author/image-remove/{id}', [AdminController::class, 'removesizecolorImage'])->name('sizecolor.image.remove');

        Route::get('/slider/data', [AdminController::class, 'sliderData'])->name('slider.data');
        Route::get('/slider', [AdminController::class, 'slider'])->name('slider');
        Route::get('/slider/create', [AdminController::class, 'sliderCreate'])->name('slider.create');
        Route::post('/slider/store', [AdminController::class, 'sliderStore'])->name('slider.store');
        Route::get('/slider/edit/{id}', [AdminController::class, 'sliderEdit'])->name('slider.edit');
        Route::post('/slider/update/{id}', [AdminController::class, 'sliderUpdate'])->name('slider.update');
        Route::delete('/slider/delete/{id}', [AdminController::class, 'sliderDelete'])->name('slider.delete');
        Route::get('/slider/status-toggle/{id}', [AdminController::class, 'toggleSliderStatus'])->name('slider.status.toggle');

        Route::get('/slider/image-remove/{id}', [AdminController::class, 'removeSliderImage'])->name('slider.image.remove');


        Route::get('/product/data', [AdminController::class, 'productData'])->name('product.data');
        Route::get('/showproduct', [AdminController::class, 'showproduct'])->name('showproduct');
        Route::get('/createproduct', [AdminController::class, 'product'])->name('product');
        Route::post('/product/store', [AdminController::class, 'storeProduct'])->name('product.store');
        Route::get('/product/edit/{id}', [AdminController::class, 'productEdit'])->name('product.edit');
        Route::post('/product/update/{id}', [AdminController::class, 'productUpdate'])->name('product.update');
        Route::delete('/product/delete/{id}', [AdminController::class, 'productDelete'])->name('product.delete');
        //Route::get('admin/product/image-remove/{id}', [AdminController::class, 'removeproductImage'])->name('product.image.remove');
        Route::post('/product/remove-image', [AdminController::class, 'removeProductImage'])->name('product.remove.image');
        Route::get('/product/remove-image', [AdminController::class, 'removeProductImage'])->name('product.image.remove');
        // Color image removal route
        Route::post('/product/remove-color-image', [AdminController::class, 'removeColorImage'])->name('product.remove.color.image');


        Route::post('/page/store', [AdminController::class, 'pageStore'])->name('page.store');
        Route::get('/page/edit/{id}', [AdminController::class, 'pageEdit'])->name('page.edit');
        Route::post('/page/update/{id}', [AdminController::class, 'pageUpdate'])->name('page.update');
        Route::get('/page/delete/{id}', [AdminController::class, 'pageDelete'])->name('page.delete');
        Route::get('/page/image-remove/{id}', [AdminController::class, 'removepageImage'])->name('page.image.remove');


        Route::get('/link', [AdminController::class, 'link'])->name('link');
        Route::get('/link/create', [AdminController::class, 'linkCreate'])->name('link.create');
        Route::post('/link/store', [AdminController::class, 'linkStore'])->name('link.store');
        Route::get('/link/edit/{id}', [AdminController::class, 'linkEdit'])->name('link.edit');
        Route::post('/link/update/{id}', [AdminController::class, 'linkUpdate'])->name('link.update');
        Route::delete('/link/delete/{id}', [AdminController::class, 'linkDelete'])->name('link.delete');
        Route::get('/link/status-toggle/{id}', [AdminController::class, 'togglelinkStatus'])->name('link.status.toggle');
        Route::get('/link/image-remove/{id}', [AdminController::class, 'removelinkImage'])->name('link.image.remove');


        Route::get('/discount-codes', [DiscountCodeController::class, 'index'])->name('discount-codes.index');
        Route::post('/discount-codes', [DiscountCodeController::class, 'store'])->name('discount-codes.store');
        Route::get('/discount-codes/{id}/edit', [DiscountCodeController::class, 'edit'])->name('discount-codes.edit');
        Route::put('/discount-codes/{discountCode}', [DiscountCodeController::class, 'update'])->name('discount-codes.update');
        Route::delete('/discount-codes/{discountCode}', [DiscountCodeController::class, 'destroy'])->name('discount-codes.destroy');
        Route::post('/apply-discount', [DiscountCodeController::class, 'applyDiscount'])->name('apply-discount');


        Route::get('/tax', [DiscountCodeController::class, 'tax'])->name('tax');
        Route::post('/tax', [DiscountCodeController::class, 'storetax'])->name('tax.store');
        Route::put('/tax/{id}', [DiscountCodeController::class, 'updatetax'])->name('tax.update');

        Route::delete('/tax/{discountCode}', [DiscountCodeController::class, 'destroytax'])->name('tax.destroy');
        //Route::post('admin/apply-discount', [DiscountCodeController::class, 'applyDiscount'])->name('apply-discount');

        Route::get('/page/data', [AdminController::class, 'pageData'])->name('page.data');
        Route::get('/page', [AdminController::class, 'page'])->name('page');
        Route::post('/page/store', [AdminController::class, 'pageStore'])->name('page.store');
        Route::get('/page/edit/{id}', [AdminController::class, 'pageEdit'])->name('page.edit');
        Route::post('/page/update/{id}', [AdminController::class, 'pageUpdate'])->name('page.update');
        Route::get('/page/delete/{id}', [AdminController::class, 'pageDelete'])->name('page.delete');
        Route::get('/page/status-toggle/{id}', [AdminController::class, 'togglepageStatus'])->name('page.status.toggle');
        Route::get('/page/image-remove/{id}', [AdminController::class, 'removepageImage'])->name('page.image.remove');


        Route::get('/blog/data', [AdminController::class, 'blogData'])->name('blog.data');
        Route::get('/blog', [AdminController::class, 'blog'])->name('admin.blog');
        Route::post('/blog/store', [AdminController::class, 'blogStore'])->name('blog.store');
        Route::get('/blog/edit/{id}', [AdminController::class, 'blogEdit'])->name('blog.edit');
        Route::post('/blog/update/{id}', [AdminController::class, 'blogUpdate'])->name('blog.update');
        Route::delete('/blog/delete/{id}', [AdminController::class, 'blogDelete'])->name('blog.delete');
        Route::get('/blog/status-toggle/{id}', [AdminController::class, 'toggleblogStatus'])->name('blog.status.toggle');
        Route::get('/blog/image-remove/{id}', [AdminController::class, 'removeblogImage'])->name('blog.image.remove');

        Route::get('/setting/edit/1', [AdminController::class, 'settingEdit'])->name('setting.edit');
        Route::post('/setting/update/1', [AdminController::class, 'settingUpdate'])->name('setting.update');
        Route::get('/setting/remove-image/{id}', [AdminController::class, 'removeSettingImage'])->name('setting.image.remove');

        Route::get('/showhomepagebanner', [AdminController::class, 'showhomepagebanner'])->name('showhomepagebanner');
        Route::get('/homepagebanner/edit/{id}', [AdminController::class, 'homepagebannerEdit'])->name('homepagebanner.edit');
        Route::put('/homepagebanner/update/{id}', [AdminController::class, 'homepagebannerUpdate'])->name('homepagebanner.update');
        Route::get('/homepagebanner/{id}/remove-image', [AdminController::class, 'removehomepagebannerImage'])->name('homepagebanner.remove-image');

        // Remove Banner Image
        Route::get('/homepagebanner/remove-image/{id}', [AdminController::class, 'removehomepagebannerImage'])->name('homepagebanner.image.remove');
        Route::get('/checkavailability/list', [AdminController::class, 'showCheckAvailability'])->name('checkavailability.list');

        Route::post('/checkavailability/import', [AdminController::class, 'importCheckAvailability'])->name('checkavailability.import');
        Route::get('/checkavailability/status-toggle/{id}', [AdminController::class, 'togglecheckavailabilityStatus'])->name('checkavailability.status.toggle');
        Route::delete('/checkavailability/{id}', [AdminController::class, 'checkavailabilityDelete'])->name('checkavailability.destroy');
        Route::get('/checkavailability/sample', [AdminController::class, 'downloadSample'])
          ->name('checkavailability.sample');
        Route::get('/checkavailability/data', [AdminController::class, 'checkAvailabilityData'])
        ->name('checkavailability.data');
        Route::get('/contact/data', [AdminController::class, 'contactData'])->name('contact.data');
        Route::get('/contactshow', [AdminController::class, 'contactshow'])->name('contactshow');
        Route::get('/customer/data', [AdminController::class, 'customerData'])->name('customer.data');
        Route::get('/customer', [AdminController::class, 'customershow']);

        Route::get('/printing-document/data', [AdminController::class, 'printingData'])->name('printing.data');
        Route::get('/printing-docunment', [AdminController::class, 'printingdocunmentshow']);
        Route::post('/payment-mode/{id}', [AdminController::class, 'handlePaymentMode'])
            ->name('admin.payment.mode');

        Route::get('/seo/data', [AdminController::class, 'seoData'])->name('seo.data');
        Route::get('/showseo', [AdminController::class, 'showseo'])->name('showseo');
        Route::get('/createseo', [AdminController::class, 'seo'])->name('seo');
        Route::post('/seo/store', [AdminController::class, 'storeseo'])->name('seo.store');
        Route::get('/seo/edit/{id}', [AdminController::class, 'seoEdit'])->name('seo.edit');
        Route::post('/seo/update/{id}', [AdminController::class, 'seoUpdate'])->name('seo.update');
        Route::delete('/seo/delete/{id}', [AdminController::class, 'seoDelete'])->name('seo.delete');


        Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');

        Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
        Route::put('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.update-status');
        Route::put('/orders/{id}/update-payment-status', [AdminOrderController::class, 'updatePaymentStatus'])->name('admin.orders.update-payment-status');
        Route::get('/orders/{id}/invoice', [AdminOrderController::class, 'invoice'])->name('admin.orders.invoice');
        Route::delete('/orders/{id}', [AdminOrderController::class, 'destroy'])->name('admin.orders.destroy');
        Route::post('/orders/export', [AdminOrderController::class, 'export'])->name('admin.orders.export');

    });
});
// Route::get('/test-email-simple', function() {
//     try {
//         \Illuminate\Support\Facades\Mail::raw('Test email body', function($message) {
//             $message->to('info@ajhuie.com')
//                     ->subject('Test Email')
//                     ->from('info@ajhuie.com', 'Laravel Test');
//         });

//         return 'Email sent successfully!';
//     } catch (\Exception $e) {
//         return 'Error: ' . $e->getMessage();
//     }
// });


// customer route
// customer route
Route::prefix('customer')->group(function () {

    Route::get('login', function () {
        return redirect()->route('cart.view')->with('open_login_modal', true);
    })->name('customer.login');

    // POST route same रहेगा
    Route::post('login', [CustomerAuthController::class, 'login']);

    Route::get('register', [CustomerAuthController::class, 'registerForm'])->name('customer.register');
    Route::post('register', [CustomerAuthController::class, 'register']);
    Route::post('send-signup-otp', [CustomerAuthController::class, 'sendSignupOtp'])->name('customer.send.signup.otp');
    Route::post('verify-signup-otp', [CustomerAuthController::class, 'verifySignupOtp'])->name('customer.verify.signup.otp');
    Route::post('logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');
    Route::get('forgot-password', [CustomerAuthController::class, 'showForgotPasswordForm'])->name('customer.password.request');
    Route::post('forgot-password', [CustomerAuthController::class, 'sendResetLink'])->name('customer.password.email');
    Route::get('verify-otp', [CustomerAuthController::class, 'showOtpForm'])->name('customer.password.otp');
    Route::post('verify-otp', [CustomerAuthController::class, 'verifyOtp'])->name('customer.password.verify');
    Route::post('resend-otp', [CustomerAuthController::class, 'resendOtp'])->name('customer.password.resend');
    Route::get('reset-password/{token}', [CustomerAuthController::class, 'showResetPasswordForm'])->name('customer.password.reset.form');
    Route::post('reset-password', [CustomerAuthController::class, 'resetPassword'])->name('customer.password.update');

    Route::post('/cart/delete/{id}', [OrderController::class, 'deleteCartItem'])->name('cart.delete');

    Route::middleware(['auth:customer', 'customer'])->group(function () {

        Route::get('dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
        Route::get('orders', [CustomerController::class, 'orders'])->name('customer.orders');
        Route::get('order/{order_number}', [CustomerController::class, 'orderDetails'])->name('customer.order.details');
        Route::post('profile/update', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');
        Route::post('/track-order', [CustomerController::class, 'trackOrdercu'])->name('customer.order.track.submit');
        Route::get('/order-track/{order_number}', [CustomerController::class, 'orderTrackView'])->name('customer.order.track.view');
        Route::get('cartitem', [CustomerController::class, 'cartitem'])->name('customer.cartitem');





        Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
        Route::post('/fetch-city-state', [OrderController::class, 'fetchCityState'])->name('fetch.city.state');

        Route::post('/check-pincode', [OrderController::class, 'checkPincode'])->name('check.pincode');
        // Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
        Route::post('/place-order', [OrderController::class, 'placeOrder'])->name('order.place');

        Route::get('/order-confirmation/{order_number}', [OrderController::class, 'orderConfirmation'])->name('order.confirmation');

        Route::get('/order/{order}/invoice/download', [OrderController::class, 'downloadInvoice'])->name('order.invoice.download');
        Route::post('/order/track', [OrderController::class, 'trackByOrderNumber'])
            ->name('order.track.submit');

        Route::get('/order/track/{order_number}', [OrderController::class, 'trackOrder'])->name('order.track');
        Route::controller(PaymentController::class)->group(function () {
            Route::get('/payment/{order_number}', function ($order_number) {
                return redirect()->route('order.confirmation', [
                    'order_number' => $order_number,

                ]);
            })->name('payment.page');
            Route::post('/create-razorpay-order', 'createRazorpayOrder')->name('create.razorpay.order');

            Route::match(['get', 'post'], '/payment/success/{order_number}', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
            Route::get('/payment/failure/{order_number}', 'paymentFailure')->name('payment.failure');
            Route::get('/payment/cancel/{order_number}', 'paymentCancel')->name('payment.cancel');
            Route::get('/test-razorpay', 'testRazorpay');
        });
        Route::post('/submit-review', [HomeController::class, 'submitReview'])->name('submit.review');




    });
});
// Print upload direct payment
Route::get('/file-upload', [PrintUploadController::class,'index'])->name('print.upload');
Route::post('/file-upload-store', [PrintUploadController::class,'store'])->name('print.upload.store');

Route::post('/print/initiate-payment', [PrintUploadController::class, 'initiatePayment'])->name('print.initiate.payment');
Route::match(['get', 'post'], '/print/payment/success/{order_id}', [PrintUploadController::class, 'paymentSuccess'])->name('print.payment.success');
Route::get('/print/payment/cancel/{order_id}', [PrintUploadController::class, 'paymentCancel'])->name('print.payment.cancel');

Route::get('/print/order/confirmation/{order_number}', [PrintUploadController::class, 'confirmation'])
    ->name('print.order.confirmation');

//Route::post('/remaining-payment-success/{order_id}', [PrintUploadController::class, 'remainingPaymentSuccess']);
Route::get('/pay-remaining/{order_number}', [PrintUploadController::class, 'payRemaining'])
    ->name('pay.remaining');
Route::get('/create-remaining-order/{order_number}', [PrintUploadController::class, 'createRemainingOrder']);
Route::post('/remaining-payment-success/{order_number}', [PrintUploadController::class, 'remainingPaymentSuccess'])
    ->name('remaining.payment.success');

Route::get('/track-order', function () {
    return view('track-order');
});

Route::post('/track-order', [PrintUploadController::class, 'trackOrder'])
    ->name('track.order');

// Route::get('/file-upload', function(){
//     return view('upload');
// })->middleware('auth:customer');
Route::get('/', [HomeController::class, 'index'])->name('index');
//product
Route::get('/product', [HomeController::class, 'product'])->name('product');
Route::get('/search-product', [HomeController::class, 'searchProduct'])->name('product.search');
Route::get('/product-details', [HomeController::class, 'product'])->name('product');
Route::get('/product/{slug}', [HomeController::class, 'productDetails'])->name('product.details');
//Pincode
//Route::post('/check-pincode', [HomeController::class, 'checkPincode'])->name('check.pincode');
Route::get('/proceed-to-checkout', [HomeController::class, 'proceedToCheckout'])->name('proceed.checkout');
Route::post('/wishlist/toggle', [HomeController::class, 'toggle'])->name('wishlist.toggle');
Route::get('/wishlist/count', [HomeController::class, 'count'])->name('wishlist.count');
Route::post('/apply-coupon', [HomeController::class, 'applyCoupon'])->name('apply.coupon');
Route::post('/remove-coupon', [HomeController::class, 'removeCoupon'])->name('remove.coupon');
//Review
Route::get('/product/{slug}/reviews', [HomeController::class, 'allReviews'])->name('product.reviews');

//Cart
Route::post('addtocart', [HomeController::class, 'addtocart'])->name('cart.add');
Route::get('cart', [HomeController::class, 'cartView'])->name('cart.view');
Route::patch('cart/update/{id}', [HomeController::class, 'updateCart'])->name('cart.update');
Route::delete('cart/remove/{itemId}', [HomeController::class, 'removeFromCart'])->name('cart.remove');
Route::get('cart/count', [HomeController::class, 'getCartCount'])->name('cart.count');



Route::get('/contact-us', [SimpleFileController::class, 'contactus'])->name('contactus');
Route::post('/contact-submit', [HomeController::class, 'contactSubmit'])->name('contact.submit');



Route::get('/about-us', [SimpleFileController::class, 'aboutus'])->name('aboutus');
Route::get('/help-support', [SimpleFileController::class, 'helpsupport'])->name('helpsupport');
Route::get('/return-policy', [SimpleFileController::class, 'returnpolicy'])->name('returnpolicy');
Route::get('/shipping-returns', [SimpleFileController::class, 'shippingreturns'])->name('shippingreturns');
Route::get('/privacy-policy', [SimpleFileController::class, 'privacypolicy'])->name('privacypolicy');
Route::get('/new-arrivals', [SimpleFileController::class, 'arrivals'])->name('arrivals');
Route::get('/seller', [SimpleFileController::class, 'seller'])->name('seller');
Route::get('/exam-corner', [SimpleFileController::class, 'examcorner'])->name('examcorner');
Route::get('/featured', [SimpleFileController::class, 'featuredbooks'])->name('featuredbooks');
Route::post('/newsletter/subscribe', [SimpleFileController::class, 'subscribe'])->name('newsletter.subscribe');

Route::get('/my-wishlist', [SimpleFileController::class, 'indexWishlist'])
    ->name('wishlist.index');
Route::get('/blog', [SimpleFileController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [SimpleFileController::class, 'blogDetails'])->name('blog.details');

// Add these POST routes for AJAX filtering
// GET routes for initial page loads
Route::get('/{category}', [HomeController::class, 'categoryProducts'])->name('category.products');
Route::get('/{category}/{subcategory}', [HomeController::class, 'subCategoryProducts'])->name('subcategory.products');
Route::get('/{category}/{subcategory}/{child}', [HomeController::class, 'childCategoryProducts'])->name('childcategory.products');
Route::post('/filter-products', [HomeController::class, 'filterProducts'])->name('filter.products');
