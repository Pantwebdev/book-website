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
                       <div class="alert alert-success">{{ session('success') }}</div>
                       @endif
                       @if(session('error'))
                       <div class="alert alert-danger">{{ session('error') }}</div>
                       @endif

                       <form action="{{ route('link.update', $link->id) }}" method="POST" enctype="multipart/form-data">
                           @csrf
                           <div class="card-header">
                               <div class="card-title">Edit Link</div>
                           </div>
                           <div class="card-body">


                                <div class="row">
                                    <div class="col-md-12 col-lg-4">
                                       <div class="form-group">
                                           <label>Link Content</label>
                                           <textarea class="form-control" name="link"
                                               rows="5">{{ old('link', $link->link) }}</textarea>
                                           @error('link')<small class="text-danger">{{ $message }}</small>@enderror
                                       </div>
                                   </div>
                                </div>
                               <div>
                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>Upload Icon Image</label>
                                           <input type="file" name="image" class="form-control">
                                           @error('image')<small class="text-danger">{{ $message }}</small>@enderror
                                           @if($link->image)
                                           <img src="{{ asset('userassets/image/link/' . $link->image) }}" height="50"
                                               class="mt-2"><br>
                                           <a href="{{ route('link.image.remove', $link->id) }}"
                                               class="btn btn-sm btn-danger mt-2"
                                               onclick="return confirm('Are you sure you want to remove the image?')">Remove
                                               Image</a>
                                           @endif

                                       </div>
                                   </div>
                               </div>
                           </div>
                           <div class="card-action">
                               <button type="submit" class="btn btn-success">Update</button>
                               <a href="{{ route('link.create') }}" class="btn btn-danger">Cancel</a>
                           </div>
                       </form>
                   </div>
               </div>
           </div>
       </div>
   </div> 
   <style>
.cke_notifications_area {
    display: none !important;
}
   </style>
   <!-- CKEditor -->
   <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
   <script>
CKEDITOR.replace('link');
   </script>
   <script>
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    const imageInput = document.querySelector('input[name="image"]');
    const errorElement = document.getElementById('image-error');
    errorElement.textContent = ''; // Clear previous error message

    if (imageInput.files.length > 0) {
        const file = imageInput.files[0];
        const maxSize = 2 * 1024 * 1024; // 2MB in bytes
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/svg+xml'];

        // ✅ File type check
        if (!allowedTypes.includes(file.type)) {
            errorElement.textContent = '❌ Only JPG, JPEG, PNG, or SVG images are allowed.';
            e.preventDefault();
            return;
        }

        // ✅ File size check
        if (file.size > maxSize) {
            errorElement.textContent = '❌ Image size must not exceed 2MB.';
            e.preventDefault();
            return;
        }
    }
});
   </script>
   @endsection