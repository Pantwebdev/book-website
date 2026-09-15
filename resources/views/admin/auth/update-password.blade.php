<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>PolluxUI Admin</title>
  <!-- base:css -->
  <link rel="stylesheet" href="{{url('adminassets/login/assets/vendors/typicons/typicons.css')}}">
  <link rel="stylesheet" href="{{url('adminassets/login/assets/vendors/css/vendor.bundle.base.css')}}">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{url('adminassets/login/assets/css/vertical-layout-light/style.css')}}">
  <!-- endinject -->
   <link rel="shortcut icon" href="{{url('adminassets/login/assets/images/favicon.png')}}" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="{{url('adminassets/login/assets/images/logo-dark.svg')}}" alt="logo">
              </div>
              <h4>Hello! let's get started</h4>
              <h6 class="font-weight-light">Sign in to continue.</h6>
             <form method="POST" action="{{ route('password.update') }}" class="pt-3">
    @csrf

    <div class="form-group">
        <input type="password" class="form-control form-control-lg" name="current_password" placeholder="Current Password">
    </div>

    <div class="form-group">
        <input type="password" class="form-control form-control-lg" name="new_password" placeholder="New Password">
    </div>

    <div class="form-group">
        <input type="password" class="form-control form-control-lg" name="new_password_confirmation" placeholder="Confirm New Password">
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
            Update Password
        </button>
    </div>
</form>

            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- base:js -->
  <script src="{{url('adminassets/login/assets/vendors/js/vendor.bundle.base.js')}}"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="{{url('adminassets/login/assets/js/off-canvas.js')}}"></script>
  <script src="{{url('adminassets/login/assets/js/hoverable-collapse.js')}}"></script>
  <script src="{{url('adminassets/login/assets/js/template.js')}}"></script>
  <script src="{{url('adminassets/login/assets/js/settings.js')}}"></script>
  <script src="{{url('adminassets/login/assets/js/todolist.js')}}"></script>
  <!-- endinject -->
</body>

</html>
