<!doctype html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $meta->meta_title ?? 'Ajhuie Books, Photostat & Photocopy Services' }}</title>
    <meta name="description"
        content="{{ $meta->meta_description ?? 'Ajhuie Books Shop & Photoshop offers school & college books, competitive exam books, stationery, photocopy, printing, lamination and document services at affordable prices.' }}">
    <meta name="keywords"
        content="{{ $meta->meta_keywords ?? 'Ajhuie Books Shop, book store, school books, college books, competitive exam books, stationery shop, photocopy shop, photoshop service, printing, lamination, document printing, book shop near me' }}">
    <link rel="canonical" href="{{ $meta->canonical_url ?? url()->current() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   
    
<!-- SEO ADD START -->

<meta name="robots" content="index, follow">
<meta name="author" content="Ajhuie Books Store">

<!-- Local SEO -->
<meta name="geo.region" content="IN">
<meta name="geo.placename" content="India">

<!-- Open Graph (WhatsApp / Facebook) -->
<meta property="og:title" content="Ajhuie Books Store">
<meta property="og:description" content="Buy school, college and competitive books online with printing and photocopy services.">
<meta property="og:image" content="{{ url('userassets/image/favicon_ajhuie.png') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Ajhuie Books Store">
<meta name="twitter:description" content="Online book store for students with best prices and fast delivery.">
<meta name="twitter:image" content="{{ url('userassets/image/favicon_ajhuie.png') }}">

<!-- Structured Data (Google SEO Boost) -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BookStore",
  "name": "Ajhuie Books Store",
  "url": "{{ url('/') }}",
  "logo": "{{ url('userassets/image/favicon_ajhuie.png') }}",
  "description": "Online bookstore offering school, college and competitive exam books along with photocopy and printing services.",
  "address": {
    "@type": "PostalAddress",
    "addressCountry": "IN"
  }
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Organization",
      "name": "Ajhuie Books Store",
      "url": "{{ url('/') }}",
      "logo": "{{ url('userassets/image/favicon_ajhuie.png') }}",
      "sameAs": []
    },
    {
      "@type": "WebSite",
      "name": "Ajhuie Books Store",
      "url": "{{ url('/') }}",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ url('/') }}?s={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    },
    {
      "@type": "BookStore",
      "name": "Ajhuie Books Store",
      "url": "{{ url('/') }}",
      "image": "{{ url('userassets/image/favicon_ajhuie.png') }}",
      "description": "Online bookstore offering school, college and competitive exam books along with photocopy and printing services.",
      "address": {
        "@type": "PostalAddress",
        "addressCountry": "IN"
      }
    }
  ]
}
</script>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-YYHLH287HC"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-YYHLH287HC');
</script>
 <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- SEO ADD END -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Upright:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <link rel="icon" type="image/png" href="{{ url('userassets/image/favicon_ajhuie.png') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="{{ url('userassets/bootstrap/bootstrap.min.css') }}?v={{ time() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ url('userassets/css/style.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ url('userassets/css/responsive.css') }}?v={{ time() }}">





</head>

<body class="pradeep121">
    <header class="header-main">
        <!-- Header Section -->
        <div style="background-color:#cb7000">
            <div class="topnav">


                <div class="mobilenumber">

                 <!-- LOGO -->
                    <div class="logoheader">
                        <a href="{{ url('/') }}">
                            @if(!empty($setting->image))
                            <img src="{{ url('userassets/image/' . $setting->image) }}" alt="ajhuie books store">
                            @else
                            <img src="{{ url('userassets/image/logo.png') }}" alt="ajhuie books store">
                            @endif
                        </a>
                    </div>
                    <ul>
                        <li><a href="tel:9711475393"><i class="fa-header-icon fa fa-phone"></i> 9711475393</a></li>
                    </ul>
                   
                    <!-- Upload -->
                    <div class="uplodedoc">
                        <a href="{{ route('print.upload') }}" class="position-relative btnfile">
                           <img src="{{ url('userassets/image/print.png') }}" alt="print">
                            <spam>Print<spam>
                            
                        </a>
                    </div>
                </div>
                <!-- SEARCH -->
                <div class="header-search">
                    <div class="">
                        <input type="text" name="fakeusernameremembered" style="display:none">
                        <input type="password" name="fakepasswordremembered" style="display:none">
                        <form autocomplete="off">
                            <div class="input-group">
                                <input type="search" id="searchProduct" name="search_product_field" class="form-control"
                                    placeholder="Search products..." autocomplete="off" autocorrect="off"
                                    autocapitalize="off" spellcheck="false">
                            </div>
                        </form>

                        <div id="searchResults" class="search-results "></div>

                    </div>
                </div>

                <!-- ICONS -->
                <div class="icons">

                    <!-- Wishlist -->
                    <a href="{{ route('wishlist.index') }}" style="text-decoration:none;color:inherit;">
                        <div class="icon position-relative">
                            <i class="bi bi-suit-heart" id="wishlistIcon"></i>
                            <span id="wishlistCount"
                                class="count position-absolute top-0 start-100 translate-middle badge rounded-pill "
                                style="font-size:10px;transform:translate(-50%,-50%) !important;">
                                {{ $wishcount ?? 0 }}
                            </span>
                        </div>
                    </a>

                    <!-- Cart -->
                    <a href="{{ route('cart.view') }}" style="text-decoration:none;color:inherit;">
                        <div class="icon position-relative">
                            <i class="fa-solid fa-bag-shopping" ></i>
                            <span class="count position-absolute top-0 start-100 translate-middle badge rounded-pill headerCartCount"
                                id="headerCartCount" style="font-size:10px;transform:translate(-50%,-50%) !important;">
                                {{ $cartCount ?? 0 }}
                            </span>
                        </div>
                    </a>

                    <!-- USER DROPDOWN -->
                    <!-- <div class="dropdown">

                        @if(auth('customer')->check())

                        <button class="dropbtn newonepadi" type="button">
                            <i class="fa fa-user"></i>

                            <span>{{ auth('customer')->user()->name }}</span>
                            <i class="fa fa-caret-down"></i>
                        </button>

                        <div class="dropdown-contentform">
                            <a href="{{ route('customer.dashboard') }}">Dashboard</a>

                            <a href="{{ route('customer.logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('customer.logout') }}" method="POST"
                                style="display:none;">
                                @csrf
                            </form>
                        </div>

                        @else

                        <button class="dropbtn" type="button">
                            <i class="fa fa-user"></i>
                            <i class="fa fa-caret-down"></i>
                        </button>

                        <div class="dropdown-contentform">
                            <a href="javascript:void(0)" class="open-modal" data-modal="loginModalTop">
                                Login
                            </a>

                            <a href="javascript:void(0)" class="open-modal" data-modal="signupModalTop">
                                Signup
                            </a>
                        </div>

                        @endif

                    </div> -->
  <div class="dropdown">
            @if(auth('customer')->check())
                <button class="dropbtn newonepadi" type="button">
                   <i class="fas fa-user-circle" style="font-size: 22px;"></i>
                   <!--<span> {{ auth('customer')->user()->name }}</span>-->
                    <i class="fa fa-caret-down"></i>
                </button>

                <div class="dropdown-contentform">
                     <a class="product-dotss" style="color:#d97000;text-transform: capitalize;font-weight: 600;">{{ auth('customer')->user()->name }}</a>
                    <a href="{{ route('customer.dashboard') }}">Dashboard</a>
                    <!-- <a href="{{ route('customer.orders') }}">Track Order</a> -->

                    <a href="{{ route('customer.logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                    </a>

                    <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                </div>
            @else
            <!-- GUEST OR ADMIN → Login / Signup -->
            <button class="dropbtn newonepadi" type="button">
                <i class="fa fa-user"></i>
                <i class="fa fa-caret-down"></i>
            </button>

            <div class="dropdown-contentform">
                <a href="javascript:void(0)" class="open-modal" data-modal="loginModalTop">
                    Login
                </a>
                <a href="javascript:void(0)" class="open-modal" data-modal="signupModalTop">
                    Signup
                </a>
            </div>
            @endif

        </div>
        </div>
                </div>

            </div>
        </div>
        <nav class="navbar navbar-expand-lg navbar-light ">
            <div class="container">

             

                <div class="collapse navbar-collapse" id="mainNav">

                    <!-- 🔥 MOBILE LOGIN / SIGNUP (ONLY MOBILE) -->
                   

<div class="mobile-auth d-lg-none">
    @if(auth('customer')->check())

        <p class="mobile-auth-link">
            Hi : {{ auth('customer')->user()->name }}
        </p>
<div class="mobiledetailsuser">
  <a href="{{ route('customer.dashboard') }}" class="mobile-auth-link">
            DASHBOARD
        </a>

<span class="text-white">|</span>

        <a href="{{ route('customer.logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="mobile-auth-link logout">
            LOGOUT
        </a>
</div>
      

    @else
        <a href="javascript:void(0)" class="mobile-auth-link open-modal" data-modal="loginModalTop">
            Login
        </a>
        <span style="color: white;">|</span>
        <a href="javascript:void(0)" class="mobile-auth-link open-modal" data-modal="signupModalTop">
            Signup
        </a>
    @endif
</div>


                   

                    <ul class="navbar-nav navulmain ">
                        <li class="nav-item dropdown position-static">
                            <div class="d-flex align-items-center overflow-auto" id="scrollContainer">
                                <div class="navmanul" id="scrollContent">

                                    <!-- HOME -->
                                    <a class="nav-link " data-menu="home" href="{{ url('/') }}">
                                        Home

                                        <i class="fas fa-home"></i>
                                    </a>

                                    <!-- CATEGORIES -->
                                    @foreach($navcategories as $category)
                                    <a class="nav-link mainlink" data-menu="{{ Str::slug($category->title) }}"
                                        href="{{ url('/' . $category->slug) }}">
                                        {{ $category->title }}
                                    </a>
                                    @endforeach

                                </div>
                            </div>
                        </li>
                    </ul>



                </div>


                <div class="mobile-header">
                       <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                      <div class="logoheader-mobile">
                        <a href="{{ url('/') }}">
                            @if(!empty($setting->image))
                            <img src="{{ url('userassets/image/' . $setting->image) }}"
                                alt="Ajhuie Books Shop & Photoshop">
                            @else
                            <img src="{{ url('userassets/image/logo.png') }}" alt="Ajhuie Books Shop & Photoshop">
                            @endif
                        </a>
                    </div>
                    <div class="mobileicontop">
                    
                 
                        
                        <div class="mobile-search">
                            <span id="mobileSearchIcon">
                                <i class="fas fa-search"></i>
                            </span>

                            <div id="mobileSearchBox" style="display:none;">
                                <input type="search" id="searchProductMobile" class="form-control"
                                    placeholder="Search products...">
                                <div id="searchResultsMobile" class="search-results"></div>
                            </div>
                        </div>

                        
                       

                            <a href="{{ route('wishlist.index') }}">
                                <div class="icon position-relative">
                                    <i class="bi bi-suit-heart"></i>
                                    <span id="wishlistCount"
                                        class="count position-absolute top-0 start-100 translate-middle badge rounded-pill ">
                                        {{ $wishcount ?? 0 }}
                                    </span>
                                </div>
                            </a>

                            <a href="{{ route('cart.view') }}">
                                <div class="icon position-relative">
                                    <i class="fa-solid fa-bag-shopping"></i>
                                   <span id="headerCartCount" class="headerCartCount count position-absolute top-0 start-100 translate-middle badge rounded-pill">
                                        {{ $cartCount ?? 0 }}
                                    </span>
                                </div>
                            </a>

                          

                            <div class="uplodedoc">
                                <a href="{{ route('print.upload') }}" class="position-relative btnfile">
                                <img src="{{ url('userassets/image/printer.png') }}" alt="print">
                                    <spam>Print<spam>
                                    
                                </a>
                            </div>

                      

                    </div>

                </div>




            </div>

            <!-- Mega Menu -->
            <div class="dropdown-menu nav-main-menu-dropdown " id="nav-main-menu-dropdown">
                <div class="container-fluid">

                    <div class="tab-content">

                        @foreach($navcategories as $category)
                        <div class="tab-pane fade" id="{{ Str::slug($category->title) }}">

                            <div class="menu-scroll-area">
                                <!--<div class="row">-->

                                   
                                <!--    <div class="col-lg-5 menu-left-scroll sub-tittle">-->

                                <!--        <h6 class="fw-bold mb-3 ">{{ $category->title }}</h6>-->
                                <!--        <div class="dropcontainer">-->
                                         

                                <!--            @foreach($category->subcategories as $subcat)-->
                                <!--            <a href="{{ url('/' . $category->slug . '/' . $subcat->slug) }}" class=" ">-->
                                <!--                <img src="{{ url('userassets/image/menu/' . $subcat->image) }}"-->
                                <!--                    width="40" class="me-2">-->
                                <!--                {{ $subcat->title }}-->
                                <!--            </a>-->
                                <!--            @endforeach-->
                                <!--        </div>-->
                                <!--    </div>-->
                                <!--    <div class="col-lg-1">-->
                                <!--        <div class="horizontalline"></div>-->
                                <!--    </div>-->
                                    
                                <!--    <div class="col-lg-6 menu-right-scroll  sub-tittle1">-->



                                <!--        @foreach($category->subcategories as $subcat)-->

                                       
                                <!--        <div class="dropcontainer1">-->
                                           

                                <!--            @foreach($subcat->childSubcategories as $child)-->
                                <!--            <a href="{{ url('/' . $category->slug . '/' . $subcat->slug . '/' . $child->slug) }}"-->
                                <!--                class="d-flex align-items-center mb-2">-->
                                <!--                <img src="{{ url('userassets/image/menu/' . $child->image) }}"-->
                                <!--                    width="40" class="me-2">-->
                                <!--                {{ $child->title }}-->
                                <!--            </a>-->
                                <!--            @endforeach-->
                                <!--        </div>-->
                                <!--        @endforeach-->


                                <!--    </div>-->

                                <!--</div>-->
                                
                                @php
    $hasChild = false;
    foreach($category->subcategories as $subcat){
        if($subcat->childSubcategories->count() > 0){
            $hasChild = true;
            break;
        }
    }
@endphp

<div class="row">

    <!-- LEFT -->
    <div class="{{ $hasChild ? 'col-lg-5' : 'col-lg-12' }} menu-left-scroll sub-tittle">
        <h6 class="fw-bold mb-3">{{ $category->title }}</h6>

        <div class="dropcontainer">
            @foreach($category->subcategories as $subcat)
                <a href="{{ url('/' . $category->slug . '/' . $subcat->slug) }}">
                    <img src="{{ url('userassets/image/menu/' . $subcat->image) }}" width="40" class="me-2">
                    {{ $subcat->title }}
                </a>
            @endforeach
        </div>
    </div>

    @if($hasChild)
        <!-- LINE -->
        <div class="col-lg-1">
            <div class="horizontalline"></div>
        </div>

        <!-- RIGHT -->
        <div class="col-lg-6 menu-right-scroll sub-tittle1">
            @foreach($category->subcategories as $subcat)
                @if($subcat->childSubcategories->count() > 0)
                    <div class="dropcontainer1">
                        @foreach($subcat->childSubcategories as $child)
                            <a href="{{ url('/' . $category->slug . '/' . $subcat->slug . '/' . $child->slug) }}"
                                class="d-flex align-items-center mb-2">
                                <img src="{{ url('userassets/image/menu/' . $child->image) }}" width="40" class="me-2">
                                {{ $child->title }}
                            </a>
                        @endforeach
                    </div>
                @endif
            @endforeach
        </div>
    @endif

</div>
                            </div>

                        </div>
                        @endforeach

                    </div>
                </div>
            </div>

        </nav>

    </header>

    <!-- login/signup model code start -->
    <div>
        <div id="loginModalTop" class="dropdown_modalTop">
            <div class="dropdown_modalTop-content">
                <span class="dropdown_modalTop-close">&times;</span>
                <h3>Login</h3>
                <input type="text" class="form-control mb-3" placeholder="Email or Username" id="loginUser">

                <div class="position-relative mb-3">
                    <input type="password" class="form-control pe-5" placeholder="Password" id="loginPass"
                        name="new_password_field_123" autocomplete="new-password" autocorrect="off" autocapitalize="off"
                        spellcheck="false">

                    <span class="position-absolute top-50 end-0 translate-middle-y me-3" style="cursor:pointer;"
                        id="togglePassword">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>

                <!-- FORGOT PASSWORD LINK UPDATE करें -->
                <div class="mb-3 text-center">
                    <a href="{{ route('customer.password.request') }}" onclick="closeLoginModal()" class="text-primary"
                        style="font-size: 14px; text-decoration: none;">
                        <i class="fa fa-key me-1"></i> Forgot Password?
                    </a>
                </div>

                <div id="loginMessage" class="mb-3" style="display:none;"></div>
                <button class="btn logisign-colur w-100" id="loginBtnHeader">Login</button>
            </div>
        </div>





        
        <!-- SIGNUP MODAL -->
        <div id="signupModalTop" class="dropdown_modalTop">
            <div class="dropdown_modalTop-content"> <span class="dropdown_modalTop-close">&times;</span>
                <!-- Step 1: Email and OTP -->
                <div id="signupStep1">
                    <h3>Signup - Step 1</h3>
                    <div class="mb-3"> <input type="email" class="form-control" placeholder="Email" id="signupEmail"
                            required>
                        <div id="signupEmailError" class="text-danger small mt-1" style="display: none;"> </div>
                    </div> <button class="btn logisign-colur w-100 mb-3" id="sendOtpBtn">Send OTP</button>
                    <div id="otpSection" style="display: none;">
                        <div class="mb-3"> <input type="text" class="form-control" placeholder="Enter OTP"
                                id="signupOtp" maxlength="6">
                            <div id="otpError" class="text-danger small mt-1" style="display: none;"></div>
                        </div> <button class="btn logisign-colur w-100 mb-3" id="verifyOtpBtn">Verify OTP</button>
                        <div id="otpMessage" class="small mb-3" style="display: none;"></div>
                    </div>
                </div> <!-- Step 2: Complete Registration -->
                <div id="signupStep2" style="display: none;">
                    <h3>Signup - Step 2</h3>
                    <div id="signupForm">
                        <div class="mb-3"> <input type="text" class="form-control" placeholder="Full Name"
                                id="signupName" required>
                            <div id="signupNameError" class="text-danger small mt-1" style="display: none;"> </div>
                        </div>
                        <div class="mb-3"> <input type="tel" class="form-control" placeholder="Phone Number (10 digits)"
                                id="signupPhone" inputmode="numeric" pattern="\d{10}" maxlength="10" required>
                            <div id="phoneError" class="text-danger small mt-1" style="display: none;"> </div>
                        </div>

                        
                        <div class="mb-3">
                            <div class="position-relative">
                                <input type="password" class="form-control"
                                       placeholder="Password (Min 8 chars with uppercase, lowercase, number & special)"
                                       id="signupPassword" required
                                       style="padding-right: 40px;">
                        
                                <span class="togglePasswordSignup position-absolute top-50 translate-middle-y"
                                      style="cursor:pointer; right: 10px;">
                                    <i class="fa fa-eye"></i>
                                </span>
                            </div>
                        
                            <div id="signupPasswordError" class="text-danger small mt-1" style="display: none;"></div>

                        </div>
                     
                        
                        
                        <div class="mb-3">
                                <div class="position-relative">
                                    <input type="password" class="form-control"
                                           placeholder="Confirm Password"
                                           id="signupConfirmPassword" required
                                           style="padding-right: 40px;">
                            
                                    <span class="togglePasswordSignup position-absolute top-50 translate-middle-y"
                                          style="cursor:pointer; right: 10px;">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                         <div id="signupConfirmPasswordError" class="text-danger small mt-1" style="display: none;"></div>
                         </div>
                    
                    
                        <div id="signupError" class="text-danger small mb-3" style="display: none;"></div>
                        <div id="signupMessage" class="mb-3" style="display: none;"></div> <button
                            class="btn logisign-colur w-100" id="signupBtnHeader">Signup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- login/signup model code end -->



    <style>
    /* ================= HEADER ================= */

    .header-main {
        background: #ffffff;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 9999;
        transition: all 0.3s ease;
    }

    body {
        padding-top: 60px;
        /* header height */
    }

    .newonepadi span {
        font-size: 14px;
    }

    /* ================= HEADER ================= */
.topnav{
    height: 42px;
    display: flex;
    justify-content:space-around;
    padding-top: 5px;
    padding-bottom: 3px;
              
}



    /* Scroll hone par effect */
    .header-main.scrolled {
        box-shadow: 0 2px 4px rgb(56 56 56 / 92%);
        background-color: #ffffff;
        transition: 1s;
    }
/* 
    .logoheader {
        position: relative;
    } */

    .sub-tittle h6 {
        color: #cb7000;
        font-family: math;
        letter-spacing: 1px;
    }

    .sub-tittle1 h6 {
        color: #cb7000;
        font-family: math;
        letter-spacing: 1px;
    }

    .sub-tittle a {
        font-family: math !important;
        letter-spacing: 1px;
        font-weight: 600;
        color: #404040;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sub-tittle1 a {
        font-family: math !important;
        letter-spacing: 1px;
        font-weight: 600;
        color: #404040;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    input::placeholder {
        font-size: 14px;
        padding: 5px
    }

    .header-search .input-group input {
        padding: 2px 0px;
        margin: 0px 10px;
    }

    .mobilenumber {
        display: flex;
        justify-content: center;
    }

    .mobilenumber ul {
        display: flex;
        align-items: center;
        justify-content: center;
        list-style: none;
        /* gap: 5px; */
        margin-bottom: 0px
    }

    .mobilenumber ul a {
        text-decoration: none;
        color: #ffffff;
        margin-right: 10px;
    }

    .mobilenumber ul a i {
        font-size: 12px;
        color: #ffffff;
        padding-right: 5px;
    }

.uplodedoc{
    display: flex;
    align-items: center;
    justify-content: center;
}
    .uplodedoc a {
    text-decoration: none;
    color: #fff;
    text-align: center;
    }
    .uplodedoc spam {
        margin-left: -15px;
    }

    .uplodedoc img{
          width: 66px;
    }


    .btnfile {
        display: flex;
        /* flex-direction: column; */
        align-items: center;
        justify-content: center;
        color: black;
    }



    .btnfile p {
    margin: 0;
    margin-left: 5px;
    text-transform: uppercase;
    font-size: 10px;
    font-family: math;
    letter-spacing: 1px;
    }

    .icons {
        display: flex;
        align-items: center;
        gap: 24px;
    }


    .icon i{
        font-size: 18px;
    }

    .newonepadi i{
        font-size: 18px;
    }

    .header-search {
        flex: 1;
        max-width: 600px;
        position: relative;
    }

    .search-box input {
        width: 220px;
        height: 38px;
        border-radius: 30px;
        border: 1px solid #ddd;
        padding: 0 15px;
    }

    .form-control {
        border: 1px solid #bfbfbffa;

    }

    .input-group-text {
        border: 1px solid #ced4da61;
    }

    .form-control:focus {
        border: 1px solid #bfbfbffa;
        box-shadow: none;
    }

    .search-wrapper {
        position: relative;
        display: flex;
        flex-direction: column;
        z-index: 999;
    }

    .search-wrapper input {
        min-width: 190px;
        height: 36px;
        padding: 6px 14px;
        border-radius: 20px;
        border: 1px solid #ccc;
        font-size: 14px;
        outline: none;
    }


    .search-results {
        position: absolute;
        top: 30px;
        left: 15px;
        width: 100%;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        max-height: 250px;
        overflow-y: auto;
        z-index: 9999;
    }

    .search-results::-webkit-scrollbar {
        width: 3px;
        /* thin width */
    }

    .search-results::-webkit-scrollbar-track {
        background: transparent;
    }

    .search-results::-webkit-scrollbar-thumb {
        background: #fdc883;
        border-radius: 10px;
    }

    .search-results::-webkit-scrollbar-thumb:hover {
        background: #999;
    }


    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0 1000px white inset !important;
        -webkit-text-fill-color: #000 !important;
        transition: background-color 5000s ease-in-out 0s;
    }




    .navmanul {
        display: flex;
        gap: 0.5rem
    }

    .mobile-back {
        display: none;
        /* default hidden */
    }

    /* ===== CENTER NAVBAR MENU ===== */
    .container,
    .container-lg,
    .container-md,
    .container-sm,
    .container-xl {
        max-width: 1240px;
    }

    .navulmain {
        align-items: unset !important;
    }

    /* Mobile auth container */
    .mobile-auth {
    background-color: #151b32;
    padding: 10px 0px 10px 0px;
    /* text-align: center; */

    }

    /* Links */
    .mobile-auth-link {
        padding: 12px 0;
        font-size: 15px;
        font-weight: 500;
        color: #fff;
        text-decoration: none;
        margin-right: 10px;
        margin-left: 10px;
      
    }

  

    .mobile-auth-link:last-child {
        border-bottom: none;
    }

 

    /* .navbar-light{

    border-bottom: 1px solid #d7d2d2;
    padding: 11px 0px;
    box-shadow: 0px 1px 6px #141528;
} */




  @media (max-width: 400px) {
.dropcontainer1 a{
    width: 50% !important;
    
}

.mobileicontop{
    margin-left: 30px !important;
}

.dropcontainer a{
width: 50%;
align-items: center !important;
}
  }

    /* ===== MOBILE FIX ===== */
    @media (max-width: 576px) {
        .search-dropdown {
            position: fixed;
            top: 70px;
            left: 10px;
            right: 10px;
            width: auto;
            border-radius: 12px;
        }

        .search-dropdown::before {
            display: none;
        }

        .dropcontainer img {
            width: 50px;
            height: 50px;
        }

        .dropcontainer1 a img {
            width: 50px;
            height: 50px;
        }

        .dropcontainer a:first-child img {
            width: 50px;
            height: 50px;
        }

        .dropcontainer a {
            font-size: 12px
        }

        .menu-left-scroll h6 {
            font-size: 12px;
            padding-top: 10px;
        }

        .menu-right-scroll h6 {
            font-size: 12px;
            padding-top: 10px;
        }

        .dropcontainer1 a {
            font-size: 12px;
            width: 33%;
            flex-wrap: wrap;
        }

        .search-toggle {
            font-size: 24px;
            line-height: unset;
            padding: unset;
        }

        .newonepadi i {
            font-size: 15px
        }

        .icon i {
            font-size: 24px !important;
        }

        .dropcontainer1 a:first-child img {
            width: 50px;
            height: 50px;
        }

        .icons {
            gap: 10px
        }

        .icon .count {
            font-size: 14px !important;
            top: 2px !important;
            width: 10px;
            height: 10px;
        }

        .navbar-toggler-icon {
            width: 1.2em;
            height: 1.2em;
        }

        .navbar-light .navbar-toggler {
            border-color: transparent;
        }

        .categories .col-md-4:nth-child(1) .category-box {
            height: 225px;
            padding: 2px;
        }

        .categories .col-md-4:nth-child(2) .category-box {
            height: 300px;
            padding: 2px;
        }

        .categories .col-md-4:nth-child(3) .category-box {
            height: 225px;
            padding: 2px;
        }

        .navbar-nav .nav-link {
            width: 96% !important;

        }

        .navbar-light {
            padding: 5px 0px !important;
        }
    }


    /* ===== NAVBAR FIX WITHOUT BREAKING YOUR CODE ===== */
    /* Desktop only */
    @media (min-width: 992px) {
        .navbar-collapse {
            display: flex !important;
            justify-content: space-around;
            align-items: center;
        }
    }

    /* Mobile + Tablet */
    @media (max-width: 991.98px) {
        .navbar-collapse {
            display: none !important;
            width: 100%;
            padding: 15px 10px;
        }

        .navbar-collapse.show {
            display: block !important;
            margin-top: 50px;
        }



        .navbar-nav {
            flex-direction: column;
            align-items: flex-start;
        }

        .navbar-nav .nav-link {
            width: 100%;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .nav-main-menu-dropdown {
            top: 10%;
            left: 168px;
            width: unset;
        }

        .logoheader {
            text-align: center;
            margin-bottom: 10px;
            display: none;
        }



        #scrollContainer {
            white-space: normal;
        }

        .navmanul {
            display: unset
        }

        .header-container .dropdown {
            display: none !important;
        }

    }

    @media (max-width: 991px) {
        .nav-main-menu-dropdown {
            position: fixed;
            inset: 0;
            background: #fff;
            z-index: 1055;
            transform: translateX(100%);
            transition: transform .3s ease;
            overflow-y: auto;
        }

        .nav-main-menu-dropdown.show-mobile {
            transform: translateX(0);
        }

        .mobile-back {
            padding: 14px 16px;
            font-weight: 600;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }

        .mobile-back {
            display: block;
        }

        .navbar-toggler:focus {
            box-shadow: unset;
        }

        .navbar-collapse {
            width: 75% !important;
            max-width: 280px;
            height: 100vh;
            position: fixed;
            top: 0px;
            left: 0;
            background: #fff;
            z-index: 1050;
            overflow-y: auto;
        }

        .navbar-collapse.show {
            display: block !important;
            border-right: 1px solid #c3bfbf;
        }


        .mobile-disable {
            display: none !important;
        }


      .uplodedoc img{
        width: 23px;
      }
      .uplodedoc spam{
        color:black;
        margin-left: 3px;
        font-weight: 500;
      }

 .mobile-auth-link:last-child {
       margin-left: 10px;
    }


        body {
            padding-top: 41px;
        }

        .navbar-toggler:focus {
            box-shadow: unset;
        }

        .navbar-light .navbar-toggler {
            border-color: transparent;
        }


        .navbar-light .navbar-nav .nav-link i {
            font-size: 15px;
            margin-left: 5px;
        }

        .topnav{
            display:none;
        }

.mobileicontop i{
    color:black;
    font-size:18px;
}

.menu-right-scroll{
    max-height: none;
}

    .mobile-header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    
}

.logoheader-mobile{
    height:40px;
    display:flex;
    align-items:center;
    overflow:visible;
}
.logoheader-mobile img{
    height:32px;
    width:auto;
    transform:scale(1.4);
    transform-origin:left center;
    /* margin-top: 10px; */
}


.mobileicontop{
    display:flex;
    align-items:center;
    gap: clamp(8px, 5vw, 30px);
    margin-left:40px;
}


.icons{
    display:flex;
    align-items:center;
    gap:12px;
}

.icon i{
    font-size:20px  !important;
}




#mobileSearchBox{
    position:absolute;
    top:50px;
    left:0;
    width:100%;
    background:#1b223c;
    padding: 5px 5px;
    display:none;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

.search-results{
top: 47px;
left: 0px;
}

.menu-right-scroll{
    overflow-y: unset;
}
.navbar{
padding:10px 0px 
}
/* .usernameunder {

text-decoration: underline;
} */

.mobiledetailsuser a{
    font-size:12px;
    color: #ffc252;
}

.mobile-auth-link{
    padding: 0px;
    margin-bottom:0px;
    text-transform: capitalize;
}
    }

    @media (min-width:991px) {
        .logoheader-mobile {
            display: none
        }

        .homemob {
            display: none;
        }

        .navmanul .nav-link i {
            display: none;
        }

        .mobileicontop .mobile-search {
            display: none;
        }
        .mobile-header{
              display: none;
              padding:10px 0px 
        }
        
    }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if we need to open login modal after password reset
        const urlParams = new URLSearchParams(window.location.search);
        const openLoginModal = "{{ session('open_login_modal') }}" === "1";

        if (openLoginModal || urlParams.get('open_login') === '1') {
            // Open login modal after short delay
            setTimeout(() => {
                const loginModal = document.getElementById('loginModalTop');
                if (loginModal) {
                    loginModal.style.display = 'block';

                    // Show success message in login modal
                    const loginMessage = document.getElementById('loginMessage');
                    if (loginMessage) {
                        loginMessage.innerHTML = `
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Password reset successful! Please login with your new password.
                        </div>
                    `;
                        loginMessage.style.display = 'block';
                    }
                }
            }, 300);
        }
    });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal elements
        const loginModal = document.getElementById('loginModalTop');
        const signupModal = document.getElementById('signupModalTop');

        // Close buttons
        const closeButtons = document.querySelectorAll('.dropdown_modalTop-close');

        // Open modal links
        const openModalLinks = document.querySelectorAll('.open-modal');

        // Forgot password link
       const forgotPasswordLink = document.querySelector('a[href="{{ route('customer.password.request') }}"]');

        // Current open modal
        let currentModal = null;
        let verifiedEmail = ''; // Store verified email for signup
        let otpResendTimer = null;
        let otpCountdown = 60;

        // Function to open modal
        function openModal(modalId) {
            // Close any open modal first
            if (currentModal) {
                currentModal.style.display = 'none';
                resetSignupForm(); // Reset form when opening new modal
            }

            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'block';
                currentModal = modal;

                // Clear all messages and errors
                clearMessages();

                // If opening signup modal, reset it
                if (modalId === 'signupModalTop') {
                    resetSignupForm();
                }
            }
        }

        // Function to close modal
        function closeModal() {
            if (currentModal) {
                currentModal.style.display = 'none';
                currentModal = null;
                clearMessages();
                resetSignupForm();
            }
        }

        // Function to close login modal specifically
        window.closeLoginModal = function() {
            if (currentModal) {
                currentModal.style.display = 'none';
                currentModal = null;
                clearMessages();
            }
        };

        // Function to clear all messages
        function clearMessages() {
            const messages = ['loginMessage', 'signupMessage', 'signupError', 'phoneError', 'otpMessage'];
            messages.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.style.display = 'none';
                    element.textContent = '';
                }
            });
        }

        // Function to clear signup errors
        function clearSignupErrors() {
            const errorIds = [
                'signupEmailError',
                'otpError',
                'signupNameError',
                'phoneError',
                'signupPasswordError',
                'signupConfirmPasswordError',
                'signupError'
            ];

            errorIds.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.style.display = 'none';
                    element.textContent = '';
                }
            });
        }

        // Function to reset signup form
        function resetSignupForm() {
            // Reset all inputs
            const inputs = ['signupEmail', 'signupOtp', 'signupName', 'signupPhone', 'signupPassword',
                'signupConfirmPassword'
            ];
            inputs.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.value = '';
                    element.classList.remove('is-invalid');
                }
            });

            // Reset steps
            const step1 = document.getElementById('signupStep1');
            const step2 = document.getElementById('signupStep2');
            const otpSection = document.getElementById('otpSection');
            const sendOtpBtn = document.getElementById('sendOtpBtn');

            if (step1) step1.style.display = 'block';
            if (step2) step2.style.display = 'none';
            if (otpSection) otpSection.style.display = 'none';
            if (sendOtpBtn) {
                sendOtpBtn.disabled = false;
                sendOtpBtn.innerHTML = 'Send OTP';
            }

            // Clear messages
            clearSignupErrors();

            // Reset timer
            if (otpResendTimer) {
                clearInterval(otpResendTimer);
                otpResendTimer = null;
            }

            // Reset verified email
            verifiedEmail = '';
        }

        // Function to start OTP resend timer
        function startOtpResendTimer() {
            const otpMessage = document.getElementById('otpMessage');
            const sendOtpBtn = document.getElementById('sendOtpBtn');

            if (!sendOtpBtn || !otpMessage) return;

            sendOtpBtn.disabled = true;
            otpCountdown = 60;

            otpResendTimer = setInterval(() => {
                otpCountdown--;

                if (otpCountdown <= 0) {
                    clearInterval(otpResendTimer);
                    sendOtpBtn.disabled = false;
                    sendOtpBtn.innerHTML = 'Resend OTP';
                    otpMessage.innerHTML = 'Didn\'t receive OTP? Click Resend OTP.';
                } else {
                    sendOtpBtn.innerHTML = `Resend OTP in ${otpCountdown}s`;
                }
            }, 1000);
        }

        // Function to validate email
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        // Function to validate password strength
        function validatePassword(password) {
            // Minimum 8 characters, at least one uppercase, one lowercase, one number and one special character
            const re = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            return re.test(password);
        }

        // Event listeners for opening modals
        openModalLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const modalId = this.getAttribute('data-modal');
                openModal(modalId);
            });
        });

        // Event listeners for closing modals
        closeButtons.forEach(btn => {
            btn.addEventListener('click', closeModal);
        });

        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            if (currentModal && e.target === currentModal) {
                closeModal();
            }
        });

        // Forgot password link - redirect to forgot password page
        if (forgotPasswordLink) {
            forgotPasswordLink.addEventListener('click', function(e) {
                e.preventDefault();
                closeModal();
                window.location.href = this.href;
            });
        }


        const loginBtnHeader = document.getElementById('loginBtnHeader');
        if (loginBtnHeader) {
            loginBtnHeader.addEventListener('click', function() {
                const email = document.getElementById('loginUser').value.trim();
                const password = document.getElementById('loginPass').value;
                const loginMessage = document.getElementById('loginMessage');

                if (!email || !password) {
                    loginMessage.style.display = 'block';
                    loginMessage.innerHTML =
                        '<div class="alert alert-danger">Please fill in all fields.</div>';
                    return;
                }

                loginBtnHeader.disabled = true;
                loginBtnHeader.innerHTML =
                    '<span class="spinner-border spinner-border-sm"></span> Logging in...';

                // ✅ HEADER से login → NO redirect parameter
                const formData = new FormData();
                formData.append('email', email);
                formData.append('password', password);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                    .getAttribute('content'));
                // ✅ NO redirect_to_checkout parameter (default false)

                fetch('/customer/login', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // ✅ HEADER login: Always reload page
                            loginMessage.style.display = 'block';
                            loginMessage.innerHTML =
                                '<div class="alert alert-success">Login successful! Reloading page...</div>';

                            setTimeout(() => {
                                closeModal();
                                window.location.reload(); // ✅ PAGE RELOAD
                            }, 1000);
                        } else {
                            // Show error message
                            loginMessage.style.display = 'block';
                            if (data.errors) {
                                const errors = Object.values(data.errors).flat();
                                loginMessage.innerHTML = '<div class="alert alert-danger">' + errors
                                    .join('<br>') + '</div>';
                            } else {
                                loginMessage.innerHTML = '<div class="alert alert-danger">' + (data
                                    .message || 'Login failed. Please try again.') + '</div>';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        loginMessage.style.display = 'block';
                        loginMessage.innerHTML =
                            '<div class="alert alert-danger">Something went wrong. Please try again.</div>';
                    })
                    .finally(() => {
                        // Reset button
                        loginBtnHeader.disabled = false;
                        loginBtnHeader.textContent = 'Login';
                    });
            });
        }
        // ==============================================
        // SIGNUP FUNCTIONALITY WITH OTP
        // ==============================================

        // Send OTP button
        const sendOtpBtn = document.getElementById('sendOtpBtn');
        if (sendOtpBtn) {
            sendOtpBtn.addEventListener('click', function() {
                const email = document.getElementById('signupEmail').value.trim();
                const signupEmailError = document.getElementById('signupEmailError');

                // Reset errors
                signupEmailError.style.display = 'none';

                // Validation
                if (!email) {
                    signupEmailError.textContent = 'Email is required.';
                    signupEmailError.style.display = 'block';
                    return;
                }

                if (!validateEmail(email)) {
                    signupEmailError.textContent = 'Please enter a valid email.';
                    signupEmailError.style.display = 'block';
                    return;
                }

                // Show loading
                const btn = this;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Sending...';

                // Send AJAX request
                fetch('/customer/send-signup-otp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            email: email
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Store email for verification
                            verifiedEmail = email;

                            // Show OTP section
                            document.getElementById('otpSection').style.display = 'block';

                            // Show success message
                            const otpMessage = document.getElementById('otpMessage');
                            otpMessage.textContent =
                                'OTP sent to your email. Please check your inbox.';
                            otpMessage.style.color = 'green';
                            otpMessage.style.display = 'block';

                            // Start resend timer
                            startOtpResendTimer();
                        } else {
                            if (data.errors && data.errors.email) {
                                signupEmailError.textContent = data.errors.email[0];
                                signupEmailError.style.display = 'block';
                            } else {
                                signupEmailError.textContent = data.message ||
                                    'Failed to send OTP.';
                                signupEmailError.style.display = 'block';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        signupEmailError.textContent = 'Something went wrong. Please try again.';
                        signupEmailError.style.display = 'block';
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.textContent = 'Send OTP';
                    });
            });
        }

        // Verify OTP button
        const verifyOtpBtn = document.getElementById('verifyOtpBtn');
        if (verifyOtpBtn) {
            verifyOtpBtn.addEventListener('click', function() {
                const otp = document.getElementById('signupOtp').value.trim();
                const otpError = document.getElementById('otpError');
                const otpMessage = document.getElementById('otpMessage');

                // Reset error
                otpError.style.display = 'none';

                // Validation
                if (!otp) {
                    otpError.textContent = 'OTP is required.';
                    otpError.style.display = 'block';
                    return;
                }

                if (otp.length !== 6 || !/^\d+$/.test(otp)) {
                    otpError.textContent = 'Please enter a valid 6-digit OTP.';
                    otpError.style.display = 'block';
                    return;
                }

                // Show loading
                const btn = this;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Verifying...';

                // Send AJAX request
                fetch('/customer/verify-signup-otp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            email: verifiedEmail,
                            otp: otp
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            otpMessage.textContent = 'Email verified successfully!';
                            otpMessage.style.color = 'green';
                            otpMessage.style.display = 'block';

                            // Hide step 1 and show step 2 after delay
                            setTimeout(() => {
                                document.getElementById('signupStep1').style.display =
                                    'none';
                                document.getElementById('signupStep2').style.display =
                                    'block';

                                // Clear timer
                                if (otpResendTimer) {
                                    clearInterval(otpResendTimer);
                                }
                            }, 800);
                        } else {
                            otpError.textContent = data.message || 'Invalid OTP.';
                            otpError.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        otpError.textContent = 'Something went wrong. Please try again.';
                        otpError.style.display = 'block';
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.textContent = 'Verify OTP';
                    });
            });
        }

        // Signup button (step 2)
        const signupBtnHeader = document.getElementById('signupBtnHeader');
        if (signupBtnHeader) {
            signupBtnHeader.addEventListener('click', function() {
                const name = document.getElementById('signupName').value.trim();
                const phone = document.getElementById('signupPhone').value.trim();
                const password = document.getElementById('signupPassword').value;
                const confirmPassword = document.getElementById('signupConfirmPassword').value;
                const email = verifiedEmail;

                // Reset errors
                clearSignupErrors();
                const signupMessage = document.getElementById('signupMessage');
                signupMessage.style.display = 'none';

                // Validation
                let isValid = true;

                if (!name) {
                    document.getElementById('signupNameError').textContent = 'Name is required.';
                    document.getElementById('signupNameError').style.display = 'block';
                    isValid = false;
                }

                if (!phone) {
                    document.getElementById('phoneError').textContent = 'Phone number is required.';
                    document.getElementById('phoneError').style.display = 'block';
                    isValid = false;
                } else if (!/^\d{10}$/.test(phone)) {
                    document.getElementById('phoneError').textContent =
                        'Please enter a valid 10-digit phone number.';
                    document.getElementById('phoneError').style.display = 'block';
                    isValid = false;
                }

                if (!password) {
                    document.getElementById('signupPasswordError').textContent =
                        'Password is required.';
                    document.getElementById('signupPasswordError').style.display = 'block';
                    isValid = false;
                } else if (!validatePassword(password)) {
                    document.getElementById('signupPasswordError').textContent =
                        'Password must be at least 8 characters with uppercase, lowercase, number and special character.';
                    document.getElementById('signupPasswordError').style.display = 'block';
                    isValid = false;
                }

                if (!confirmPassword) {
                    document.getElementById('signupConfirmPasswordError').textContent =
                        'Please confirm your password.';
                    document.getElementById('signupConfirmPasswordError').style.display = 'block';
                    isValid = false;
                } else if (password !== confirmPassword) {
                    document.getElementById('signupConfirmPasswordError').textContent =
                        'Passwords do not match.';
                    document.getElementById('signupConfirmPasswordError').style.display = 'block';
                    isValid = false;
                }

                if (!isValid) return;

                // Show loading
                const btn = this;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Signing up...';

                // Create form data
                const formData = new FormData();
                formData.append('name', name);
                formData.append('email', email);
                formData.append('phone', phone);
                formData.append('password', password);
                formData.append('password_confirmation', confirmPassword);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                    .getAttribute('content'));

                // Send AJAX request
                fetch('/customer/register', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            signupMessage.innerHTML =
                                '<div class="alert alert-success">Registration successful! Logging you in...</div>';
                            signupMessage.style.display = 'block';

                            // Close modal and reload page after delay
                            setTimeout(() => {
                                closeModal();
                                window.location.reload();
                            }, 2000);
                        } else {
                            // Show error message
                            if (data.errors) {
                                Object.keys(data.errors).forEach(field => {
                                    let errorElementId = '';
                                    switch (field) {
                                        case 'name':
                                            errorElementId = 'signupNameError';
                                            break;
                                        case 'email':
                                            errorElementId = 'signupEmailError';
                                            break;
                                        case 'phone':
                                            errorElementId = 'phoneError';
                                            break;
                                        case 'password':
                                            errorElementId = 'signupPasswordError';
                                            break;
                                        case 'password_confirmation':
                                            errorElementId = 'signupConfirmPasswordError';
                                            break;
                                    }

                                    if (errorElementId) {
                                        const errorElement = document.getElementById(
                                            errorElementId);
                                        if (errorElement) {
                                            errorElement.textContent = data.errors[field][
                                                0
                                            ];
                                            errorElement.style.display = 'block';
                                        }
                                    }
                                });
                            } else {
                                const signupError = document.getElementById('signupError');
                                signupError.textContent = data.message ||
                                    'Registration failed. Please try again.';
                                signupError.style.display = 'block';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        const signupError = document.getElementById('signupError');
                        signupError.textContent = 'Something went wrong. Please try again.';
                        signupError.style.display = 'block';
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.textContent = 'Signup';
                    });
            });
        }

        // ==============================================
        // ENTER KEY SUPPORT
        // ==============================================

        // Login form enter key
        document.getElementById('loginUser')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('loginBtnHeader')?.click();
            }
        });

        document.getElementById('loginPass')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('loginBtnHeader')?.click();
            }
        });

        // Signup step 1 enter key
        document.getElementById('signupEmail')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('sendOtpBtn')?.click();
            }
        });

        document.getElementById('signupOtp')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('verifyOtpBtn')?.click();
            }
        });

        // Signup step 2 enter key
        document.getElementById('signupName')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('signupBtnHeader')?.click();
            }
        });

        document.getElementById('signupPhone')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('signupBtnHeader')?.click();
            }
        });

        document.getElementById('signupPassword')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('signupBtnHeader')?.click();
            }
        });

        document.getElementById('signupConfirmPassword')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('signupBtnHeader')?.click();
            }
        });

        // ==============================================
        // ADDITIONAL HELPER FUNCTIONS
        // ==============================================

        // Auto-format phone number
        document.getElementById('signupPhone')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            e.target.value = value;
        });

        // Show password requirements on focus
        document.getElementById('signupPassword')?.addEventListener('focus', function() {
            const errorElement = document.getElementById('signupPasswordError');
            if (errorElement) {
                errorElement.textContent =
                    'Password must contain: 8+ characters, uppercase, lowercase, number, special character';
                errorElement.style.display = 'block';
                errorElement.style.color = '#6c757d';
            }
        });

        document.getElementById('signupPassword')?.addEventListener('blur', function() {
            const errorElement = document.getElementById('signupPasswordError');
            if (errorElement && errorElement.style.color === 'rgb(108, 117, 125)') {
                errorElement.style.display = 'none';
            }
        });

        // Validate password on input
        document.getElementById('signupPassword')?.addEventListener('input', function() {
            const password = this.value;
            const errorElement = document.getElementById('signupPasswordError');

            if (password.length > 0 && !validatePassword(password)) {
                errorElement.textContent =
                    'Password must contain: 8+ characters, uppercase, lowercase, number, special character';
                errorElement.style.display = 'block';
                errorElement.style.color = '#dc3545';
            } else if (validatePassword(password)) {
                errorElement.textContent = 'Password strength: Strong ✓';
                errorElement.style.display = 'block';
                errorElement.style.color = '#28a745';
            } else {
                errorElement.style.display = 'none';
            }
        });
    });
    </script>



    <!-- mobile , tab ke liye hai ye js  -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {

        if (window.innerWidth > 991) return; // desktop skip

        const megaMenu = document.getElementById("nav-main-menu-dropdown");
        const panes = megaMenu.querySelectorAll(".tab-pane");

        document.querySelectorAll(".nav-link[data-menu]").forEach(link => {

            link.addEventListener("click", function(e) {

                const targetId = this.dataset.menu;
                const targetPane = document.getElementById(targetId);

                if (!targetPane) return;

                // ❗ only mobile prevent
                e.preventDefault();
                e.stopPropagation();

                panes.forEach(p => p.classList.remove("active", "show"));
                targetPane.classList.add("active", "show");

                // Back button inject once
                if (!targetPane.querySelector(".mobile-back")) {
                    const back = document.createElement("div");
                    back.className = "mobile-back";
                    back.innerHTML = "← Back";

                    back.onclick = () => {
                        megaMenu.classList.remove("show-mobile");
                    };

                    targetPane.prepend(back);
                }

                megaMenu.classList.add("show-mobile");
            });

        });

    });
    </script>

    <!-- toggle ke liya extra hai ye  -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {

        if (window.innerWidth > 991) return;

        const nav = document.getElementById("mainNav");
        const toggler = document.querySelector(".navbar-toggler");
        const megaMenu = document.getElementById("nav-main-menu-dropdown");
        const panes = megaMenu.querySelectorAll(".tab-pane");

        /* ===============================
           SUB MENU OPEN + BACK HANDLING
        =============================== */
        document.querySelectorAll(".nav-link[data-menu]").forEach(link => {

            link.addEventListener("click", function(e) {

                const targetId = this.dataset.menu;
                const targetPane = document.getElementById(targetId);
                if (!targetPane) return;

                e.preventDefault();
                e.stopPropagation();

                panes.forEach(p => p.classList.remove("active", "show"));
                targetPane.classList.add("active", "show");

                if (!targetPane.querySelector(".mobile-back")) {
                    const back = document.createElement("div");
                    back.className = "mobile-back";
                    back.innerHTML = "← Back";

                    back.onclick = () => {
                        targetPane.classList.remove("active", "show");
                    };

                    targetPane.prepend(back);
                }

                megaMenu.classList.add("show-mobile");
            });

        });

        /* ===============================
           OUTSIDE CLICK → TOGGLE CLOSE
        =============================== */
        document.addEventListener("click", function(e) {

            if (!nav.classList.contains("show")) return;

            if (toggler.contains(e.target)) return;
            if (nav.contains(e.target)) return;
            if (megaMenu.contains(e.target)) return;

            // close submenu
            panes.forEach(p => p.classList.remove("active", "show"));
            megaMenu.classList.remove("show-mobile");

            // close navbar
            const bsCollapse = bootstrap.Collapse.getInstance(nav);
            if (bsCollapse) bsCollapse.hide();
        });

    });
    </script>


    <!-- header fix scroll js -->
    <script>
    window.addEventListener("scroll", function() {

        let header = document.querySelector(".header-main");

        if (window.scrollY > 50) {
            header.classList.add("scrolled");
        } else {
            header.classList.remove("scrolled");
        }

    });
    </script>








    <!-- password hide or show karna ke liye js -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const passwordInput = document.getElementById("loginPass");
        const togglePassword = document.getElementById("togglePassword");
        const icon = togglePassword.querySelector("i");

        togglePassword.addEventListener("click", function() {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        });
    });
    </script>
    
      <!-- password hide or show karna ke liye js signup -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
    const toggles = document.querySelectorAll(".togglePasswordSignup");

    toggles.forEach(toggle => {
        const input = toggle.parentElement.querySelector("input");
        const icon = toggle.querySelector("i");

        toggle.addEventListener("click", function() {
            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        });
    });
});
    </script>