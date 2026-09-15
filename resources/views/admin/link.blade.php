   @extends('admin.layout.app')

   @section('content')


   <div class="container-fluide">
       <div class="page-inner">
           <div class="page-header">
               <h3 class="fw-bold mb-3">Link Forms</h3>
               <ul class="breadcrumbs mb-3">
                   <!-- <li class="nav-home">
                  <a href="#">
                    <i class="icon-home"></i>
                  </a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li> -->
                   <!-- <li class="nav-item">
                  <a href="#">Forms</a>
                </li> -->
                   <!-- <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="#">Basic Form</a>  
                </li> -->
               </ul>
           </div>
           <div class="row">
               <div class="col-md-12">
                   <div class="card">
                       <!-- @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif -->
                       @if(session('success'))
                       <div class="alert alert-success" id="success-message">{{ session('success') }}</div>
                       @endif
                       @if(session('error'))
                       <div class="alert alert-danger" id="error-message">{{ session('error') }}</div>
                       @endif
                       <form action="{{ route('link.store') }}" method="POST" enctype="multipart/form-data">
                           @csrf
                           <input type="hidden" class="form-control" name="status" id="email2" placeholder="Enter Title"
                               value="1" />

                           <div class="card-header">
                               <div class="card-title">Form Elements</div>
                           </div>
                           <div class="card-body">
                               <div class="row">



                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label for="email2">Upload Icon Image</label>
                                           <input type="file" name="image" class="form-control" id="email2"
                                               placeholder="Enter Image" />
                                           @error('image')<small class="text-danger">{{ $message }}</small>@enderror
                                           <small id="image-error" class="text-danger"></small>

                                       </div>
                                   </div>

                                   <div class="row">
                                       <div class="col-md-12 col-lg-4">
                                           <div class="form-group">
                                               <label for="comment">Link Content</label>
                                               <textarea class="form-control" id="comment" name="link"
                                                   rows="5"></textarea>
                                               @error('link')
                                               <span style="color: red;">{{ $message }}</span>
                                               @enderror
                                           </div>

                                       </div>



                                   </div>
                               </div>
                               <div class="card-action">
                                   <button type="submit" class="btn btn-success">Submit</button>
                                   <button class="btn btn-danger">Cancel</button>
                               </div>
                       </form>
                   </div>
               </div>
           </div>
           <div class="container">
               <div class="page-inner">
                   <div class="page-header">
                       <h3 class="fw-bold mb-3">Category Table</h3>
                       <!-- <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                  <a href="#">
                    <i class="icon-home"></i>
                  </a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="#">Tables</a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="#">Datatables</a>
                </li>
              </ul> -->
                   </div>


                   <div class="col-md-12">
                       <div class="card">

                           <div class="card-body">
                               <!-- Modal -->


                               <div class="table-responsive">
                                   <table id="add-row" class="display table table-striped table-hover">
                                       <thead>
                                           <tr>
                                               <th>Id</th>
                                               <th>Link</th>
                                               <th>Image</th>

                                               <th>Status</th>
                                               <th style="width: 10%">Action</th>
                                           </tr>
                                       </thead>

                                       <tbody>
                                           @foreach($links as $index=>$l)
                                           <tr>
                                               <td>{{ $index+1 }}</td>
                                               <td>{{$l->link}}</td>
                                               <td> @if($l->image)
                                                   <img src="{{ asset('userassets/image/link/' . $l->image) }}"
                                                       width="60" height="60" alt="image">
                                                   @else
                                                   <span class="text-muted">No Image</span>
                                                   @endif
                                               </td>


                                               <td>
                                                   @if($l->status == 1)
                                                   <a href="{{ route('link.status.toggle', $l->id) }}"
                                                       class="badge badge-success">Active</a>
                                                   @else
                                                   <a href="{{ route('link.status.toggle', $l->id) }}"
                                                       class="badge badge-danger">Inactive</a>
                                                   @endif
                                               </td>
                                               <td>
                                                   <div class="form-button-action">
                                                       <a href="{{ route('link.edit', $l->id) }}"
                                                           class="btn btn-link btn-primary btn-lg" title="Edit">
                                                           <i class="fa fa-edit"></i>
                                                       </a>
                                                       <form action="{{ route('link.delete', $l->id) }}" method="POST"
                                                           style="display:inline;">
                                                           @csrf
                                                           @method('DELETE')
                                                           <button class="btn btn-link btn-danger" type="submit"
                                                               onclick="return confirm('Are you sure to delete?')"
                                                               title="Delete">
                                                               <i class="fa fa-times"></i>
                                                           </button>
                                                       </form>
                                                   </div>
                                               </td>
                                           </tr>
                                           @endforeach




                                       </tbody>
                                   </table>
                               </div>
                           </div>
                       </div>
                   </div>
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