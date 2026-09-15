   @extends('admin.layout.app')

   @section('content')


   <div class="container-fluid">
       <div class="page-inner">
           <div class="page-header">
               <h3 class="fw-bold mb-3">Forms</h3>
               <ul class="breadcrumbs mb-3">
                   <li class="nav-home">
                       <a href="#">
                           <i class="icon-home"></i>
                       </a>
                   </li>
                   <li class="separator">
                       <i class="icon-arrow-right"></i>
                   </li>
                   <li class="nav-item">
                       <a href="#">Forms</a>
                   </li>
                   <li class="separator">
                       <i class="icon-arrow-right"></i>
                   </li>
                   <li class="nav-item">
                       <a href="#">Basic Form</a>
                   </li>
               </ul>
           </div>
           <div class="row">
               <div class="col-md-12">
                   <div class="card">
                       @if(session('success'))
                       <div class="alert alert-success" id="success-message">{{ session('success') }}</div>
                       @endif
                       @if(session('error'))
                       <div class="alert alert-danger" id="error-message">{{ session('error') }}</div>
                       @endif

                       <form action="{{ route('setting.update', 1) }}" method="POST" enctype="multipart/form-data">
                           @csrf
                           <div class="card-header">
                               <div class="card-title">Edit Setting</div>
                           </div>
                           <div class="card-body">


                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>Email</label>
                                   
                                                <input type="text" class="form-control" name="email"
                                               value="{{ old('email', $setting->email) }}">
                                                
                                           @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                                       </div>
                                   </div>
                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>Phone</label>
                                   
                                                <input type="text" class="form-control" name="phone"
                                               value="{{ old('phone', $setting->phone) }}">
                                                
                                           @error('phone')<small class="text-danger">{{ $message }}</small>@enderror
                                       </div>
                                   </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
  <div class="form-group">
    <label>Logo Image</label>
    <input type="file" name="image" class="form-control">
    @error('image')<small class="text-danger">{{ $message }}</small>@enderror

    @if($setting->image)
      <img src="{{ url('userassets/image/' . $setting->image) }}" height="60" class="mt-2 border p-1">
      <br>
      <a href="{{ route('setting.image.remove', $setting->id) }}" 
         class="btn btn-sm btn-danger mt-2"
         onclick="return confirm('Are you sure you want to remove this image?')">
         Remove Image
      </a>
    @endif
  </div>
</div>

                                 <div class="row">
                                    <div class="col-md-12 col-lg-4">
                                       <div class="form-group">
                                           <label>Address</label>
                                           <textarea class="form-control" name="address"
                                               rows="5">{{ old('address', $setting->address) }}</textarea>
                                           @error('address')<small class="text-danger">{{ $message }}</small>@enderror
                                       </div>
                                   </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>Facebook</label>
                                   
                                                <input type="text" class="form-control" name="facebook"
                                               value="{{ old('facebook', $setting->facebook) }}">
                                                
                                           @error('facebook')<small class="text-danger">{{ $message }}</small>@enderror
                                       </div>
                                   </div>
                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>Instagram</label>
                                   
                                                <input type="text" class="form-control" name="instagram"
                                               value="{{ old('instagram', $setting->instagram) }}">
                                                
                                           @error('instagram')<small class="text-danger">{{ $message }}</small>@enderror
                                       </div>
                                   </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>LinkedIn</label>
                                   
                                                <input type="text" class="form-control" name="linkedin"
                                               value="{{ old('linkedin', $setting->linkedin) }}">
                                                
                                           @error('linkedin')<small class="text-danger">{{ $message }}</small>@enderror
                                       </div>
                                   </div>
                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>Youtube</label>
                                   
                                                <input type="text" class="form-control" name="youtube"
                                               value="{{ old('youtube', $setting->youtube) }}">
                                                
                                           @error('youtube')<small class="text-danger">{{ $message }}</small>@enderror
                                       </div>
                                   </div>
                                </div>
                               
                           </div>
                           <div class="card-action">
                               <button type="submit" class="btn btn-success">Update</button>
                               
                           </div>
                       </form>
                   </div>
               </div>
           </div>
       </div>
   </div> 
  <script>
// Remove success message after 10 seconds
setTimeout(() => {
    const successMsg = document.getElementById('success-message');
    if (successMsg) {
        successMsg.style.transition = 'opacity 0.5s ease';
        successMsg.style.opacity = '0';
        setTimeout(() => successMsg.remove(), 500);
    }
}, 300); // 10000ms = 10 seconds

// Remove error message after 10 seconds
setTimeout(() => {
    const errorMsg = document.getElementById('error-message');
    if (errorMsg) {
        errorMsg.style.transition = 'opacity 0.5s ease';
        errorMsg.style.opacity = '0';
        setTimeout(() => errorMsg.remove(), 500);
    }
}, 300);
   </script>
   @endsection