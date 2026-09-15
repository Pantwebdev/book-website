   @extends('admin.layout.app')

   @section('content')


   <div class="container-fluide">
       <div class="page-inner">
           <div class="page-header">
               <h3 class="fw-bold mb-3">Blog Forms</h3>
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
                       @if(session('success'))
                       <div class="alert alert-success" id="success-message">{{ session('success') }}</div>
                       @endif
                       @if(session('error'))
                       <div class="alert alert-danger" id="error-message">{{ session('error') }}</div>
                       @endif
                       <form action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
                           @csrf
                           <input type="hidden" class="form-control" name="status" id="email2" placeholder="Enter Title"
                               value="1" />

                           <div class="card-header">
                               <div class="card-title">Blog Form Elements</div>
                           </div>
                           <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label for="email2">Title</label>
                                           <input type="text" class="form-control" name="title" id="email2"
                                               placeholder="Enter Title" />
                                           @error('title')<small class="text-danger">{{ $message }}</small>@enderror

                                       </div>
                                    </div>
                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label for="email2">Short Content</label>
                                           <input type="text" class="form-control" name="short_content" id="email2"
                                               placeholder="Enter Short Content" />
                                           @error('short_content')<small
                                               class="text-danger">{{ $message }}</small>@enderror

                                       </div>

                                   </div>

                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label for="email2">Image</label>
                                           <input type="file" name="image" class="form-control" id="email2"
                                               placeholder="Enter Image" />
                                           @error('image')<small class="text-danger">{{ $message }}</small>@enderror
                                           <small id="image-error" class="text-danger"></small>

                                       </div>
                                   </div>
                                   
                                        <div class="col-md-6 col-lg-4">
                                           <div class="form-group">
                                               <label for="email2">Alt Tag</label>
                                               <input type="text" class="form-control" id="email2" name="alt_tag"
                                                   placeholder="Enter Alt Lag" />
                                            </div>
                                        </div>
                                        
                                  

                                    <div class="row">
                                       <div class="col-md-12">
                                           <div class="form-group">
                                               <label for="comment">Content</label>
                                               <textarea class="form-control" id="content" name="content"
                                                   rows="5"></textarea>
                                               @error('content')
                                               <span style="color: red;">{{ $message }}</span>
                                               @enderror
                                           </div>

                                       </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label for="email2">Meta Title</label>
                                           <input type="text" class="form-control" name="metatitle" id="email2"
                                               placeholder="Enter Title" />
                                           @error('title')<small class="text-danger">{{ $message }}</small>@enderror

                                       </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label for="email2">Connitial Tag/Url</label>
                                           <input type="text" class="form-control" name="connitialtag" id="email2"
                                               placeholder="Enter Short Content" />
                                           @error('short_content')<small
                                               class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label for="email2">Image Url</label>
                                           <input type="text" class="form-control" name="imageurl" id="email2"
                                               placeholder="Enter Short Content" />
                                           @error('short_content')<small
                                               class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-md-12 col-lg-4">
                                           <div class="form-group">
                                               <label for="comment">Meta Description</label>
                                               <textarea class="form-control" id="comment" name="description"
                                                   rows="5"></textarea>
                                               @error('description')
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
                       <h3 class="fw-bold mb-3">Blog Table</h3>
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
                                  <table id="blog-table" class="display table table-striped table-hover">
    <thead>
        <tr>
            <th>Id</th>
            <th>Title</th>
            <th>Image</th>
            <th>Status</th>
            <th style="width: 10%">Action</th>
        </tr>
    </thead>
    <tbody>
        {{-- DataTables populate karega --}}
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

   <script>
document.querySelector('form').addEventListener('submit', function(e) {
    const imageInput = document.querySelector('input[name="image"]');
    const errorElement = document.getElementById('image-error');
    errorElement.textContent = ''; // clear previous error

    if (imageInput.files.length > 0) {
        const file = imageInput.files[0];
        const maxSize = 2 * 1024 * 1024; // 2MB in bytes
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/svg+xml'];

        if (!allowedTypes.includes(file.type)) {
            errorElement.textContent = 'Only JPG, JPEG, PNG, or SVG images are allowed.';
            e.preventDefault();
            return;
        }

        if (file.size > maxSize) {
            errorElement.textContent = 'Image size must not exceed 2MB.';
            e.preventDefault();
        }
    }
});
   </script>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script>
$(document).ready(function() {
    $('.toggle-status').click(function() {
        var button = $(this);
        var id = button.data('id');

        $.ajax({
            url: '/admin/blog/status-toggle/' + id,
            method: 'GET',
            success: function(response) {
                // Update status text
                let statusSpan = $('#status-' + id);
                if (response.status === 1) {
                    statusSpan.text('Active').removeClass('badge-danger').addClass(
                        'badge-success');
                    button.removeClass('btn-success').addClass('btn-warning').text(
                        'Deactivate');
                } else {
                    statusSpan.text('Inactive').removeClass('badge-success').addClass(
                        'badge-danger');
                    button.removeClass('btn-warning').addClass('btn-success').text(
                        'Activate');
                }
            },
            error: function() {
                alert('Something went wrong!');
            }
        });
    });
});
   </script>


   <script>
$(document).on('change', '.explore-toggle', function() {
    var id = $(this).data('id');
    var explore = $(this).is(':checked') ? 1 : 0;

    $.ajax({
        url: "{{ route('explore.status.toggle') }}",
        method: "POST",
        data: {
            id: id,
            explore: explore,
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            if (response.success) {
                console.log('Updated: ', response.show_explore);
            }
        },
        error: function() {
            alert('Something went wrong!');
        }
    });
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
  <!-- CKEditor -->
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    CKEDITOR.replace('content');
});
</script>
@push('scripts')
<script>
$(document).ready(function () {
    $('#blog-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("blog.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'image', name: 'image', orderable: false, searchable: false },
            { data: 'status', name: 'status', orderable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
 @endsection