   @extends('admin.layout.app')

   @section('content')


   <div class="container-fluid">
       <div class="page-inner">
           <div class="page-header">
               <h3 class="fw-bold mb-3">SubCategory Forms</h3>
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
                       <form action="{{ route('subcategory.store') }}" method="POST" enctype="multipart/form-data">

                           @csrf
                           <div class="card-header">
                               <div class="card-title">Form Elements</div>
                           </div>
                           <div class="card-body">
                               <div class="row">
                                   <input type="hidden" class="form-control" id="email2" name="status" value="1"
                                       placeholder="Enter Email" />
                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label for="exampleFormControlSelect1">select Category</label>
                                           <select class="form-select" name="category_id"
                                               id="exampleFormControlSelect1">
                                               <option value="">Select Category</option>
                                               @foreach($categories as $index=>$cg)
                                               <option value="{{$cg->id}}">{{$cg->title}}</option>
                                               @endforeach

                                           </select>
                                           @error('category_id')
                                           <small class="text-danger">{{ $message }}</small>
                                           @enderror
                                       </div>




                                   </div>
                                   <div class="row">
                                       <input type="hidden" class="form-control" id="email2" name="status" value="1"
                                           placeholder="Enter Email" />
                                       <div class="col-md-6 col-lg-4">
                                           <div class="form-group">
                                               <label for="email2">Title</label>
                                               <input type="text" class="form-control" name="title" id="email2"
                                                   placeholder="Enter Email" />
                                               @error('title')<small class="text-danger">{{ $message }}</small>@enderror

                                           </div>

                                       </div>

                                       <div class="col-md-6 col-lg-4">
                                           <div class="form-group">
                                               <label for="email2">Image</label>
                                               <input type="file" name="image" class="form-control" id="email2"
                                                   placeholder="Enter Email" />
                                               @error('image')<small class="text-danger">{{ $message }}</small>@enderror
                                               <small id="image-error" class="text-danger"></small>

                                           </div>
                                       </div>
                                   </div>
                                   <!-- <div class="row">
                                       <div class="col-md-12 col-lg-4">
                                           <div class="form-group">
                                               <label for="comment">Comment</label>
                                               <textarea class="form-control" id="comment" name="description" rows="5"></textarea>
                                           </div>

                                       </div>



                                   </div> -->
                               </div>
                               <div class="card-action">
                                   <button type="submit" class="btn btn-success">Submit</button>
                                   <button class="btn btn-danger">Cancel</button>
                               </div>
                       </form>
                   </div>
               </div>
           </div>
           <div class="container-fluid">
               <div class="page-inner">
                   <div class="page-header">
                       <h3 class="fw-bold mb-3">SubCategory Table</h3>
                       
                   </div>


                   <div class="col-md-12">
                       <div class="card">
                           <div class="card-header">
                           </div>
                           <div class="card-body">
                           

                               <div class="table-responsive">
                                   <table id="subcategory-table" class="display table table-striped table-hover">
    <thead>
        <tr>
            <th>Id</th>
            <th>Category</th>
            <th>Title</th>
            <th>Collaj</th>
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
 

   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script>
$(document).ready(function() {
    $('.toggle-status').click(function() {
        var button = $(this);
        var id = button.data('id');

        $.ajax({
            url: '/admin/subcategory/status-toggle/' + id,
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
$(document).on('change', '.collaj-toggle', function() {
    var id = $(this).data('id');
    var collaj = $(this).is(':checked') ? 1 : 0;

    $.ajax({
        url: "{{ route('subcolaj.status.toggle') }}",
        method: "POST",
        data: {
            id: id,
            collaj: collaj, // ✅ This will go as show_collaj in controller
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            if (response.success) {
                if (collaj === 1) {
                    // ✅ Show success message when checked
                    alert('You have added this product in collage field successfully!');
                } else {
                    // Optional message when unchecked
                    alert('This product has been removed from collage field.');
                }
            }
        },
        error: function() {
            alert('Something went wrong!');
        }
    });
});
   </script>

   <script>
$(document).on('change', '.explore-toggle', function() {
    var id = $(this).data('id');
    var explore = $(this).is(':checked') ? 1 : 0;

    $.ajax({
        url: "{{ route('subexplore.status.toggle') }}",
        method: "POST",
        data: {
            id: id,
            explore: explore,
            _token: "{{ csrf_token() }}"
        },
        // success: function(response) {
        //     if (response.success) {
        //         console.log('Updated: ', response.show_explore);
        //     }
        // },
         success: function(response) {
            if (response.success) {
                if (explore === 1) {
                    // ✅ Show success message when checked
                    alert('You have added this product in Explore field successfully!');
                } else {
                    // Optional message when unchecked
                    alert('This product has been removed from Explore field.');
                }
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
   @push('scripts')
<script>
$(document).ready(function () {
    $('#subcategory-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("subcategory.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'category', name: 'category' },
            { data: 'title', name: 'title' },
            { data: 'collaj', name: 'collaj', orderable: false, searchable: false },
            { data: 'status', name: 'status', orderable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
  @endsection