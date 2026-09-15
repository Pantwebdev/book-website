   @extends('admin.layout.app')

   @section('content')


    <div class="container-fluide">
        <div class="page-inner">
            <div class="page-header">
               <h3 class="fw-bold mb-3">Childsubcategory Forms</h3>
            </div>
           <div class="row">
               <div class="col-md-12">
                   <div class="card">
                       @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                       @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
                       <form action="{{ route('childsubcategory.store') }}" method="POST" enctype="multipart/form-data">

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
                                           <label>Select Category</label>
                                           <select class="form-select" name="category_id"
                                               id="exampleFormControlSelect1">
                                               <option value="">Select Category</option>
                                               @foreach($categories as $cg)
                                               <option value="{{ $cg->id }}"
                                                   {{ old('category_id') == $cg->id ? 'selected' : '' }}>
                                                   {{ $cg->title }}</option>
                                               @endforeach
                                           </select>
                                           @error('category_id')
                                           <small class="text-danger">{{ $message }}</small>
                                           @enderror
                                       </div>
                                   </div>

                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>Select Subcategory</label>
                                           <select class="form-select" id="subcategory" name="sub_category_id">
                                               <option value="">Select Subcategory</option>
                                           </select>
                                           @error('sub_category_id')
                                           <small class="text-danger">{{ $message }}</small>
                                           @enderror
                                       </div>
                                   </div>


                               </div>
                               <div class="row">
                                   <input type="hidden" class="form-control" id="email2" name="status" value="1"
                                       placeholder="Enter " />
                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label for="email2">Title</label>
                                           <input type="text" class="form-control" name="title" id="email2"
                                               placeholder="Enter Title" />
                                           @error('title')
                                           <small class="text-danger">{{ $message }}</small>
                                           @enderror

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
                       <h3 class="fw-bold mb-3">Childsubcategory</h3>
                       
                   </div>


                   <div class="col-md-12">
                       <div class="card">
                           
                           <div class="card-body">
                              

                               <div class="table-responsive">
                                   <table id="childsubcategory-table" class="display table table-striped table-hover">
    <thead>
        <tr>
            <th>Id</th>
            <th>Category</th>
            <th>Sub Category</th>
            <th>Title</th>
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

 

   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

   <script>
$(document).ready(function() {
    $('#exampleFormControlSelect1').on('change', function() {
        var categoryId = $(this).val();
        if (categoryId) {
            $.ajax({
                url: '/admin/get-subcategories/' + categoryId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#subcategory').empty();
                    $('#subcategory').append(
                        '<option value="">Select Subcategory</option>');
                    $.each(data, function(key, value) {
                        $('#subcategory').append('<option value="' + value.id +
                            '">' + value.title + '</option>');
                    });
                }
            });
        } else {
            $('#subcategory').empty();
            $('#subcategory').append('<option value="">Select Subcategory</option>');
        }
    });
});
   </script>

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
$(document).on('change', '.collection-toggle', function() {
    var id = $(this).data('id');
    var collection = $(this).is(':checked') ? 1 : 0;

    $.ajax({
        url: "{{ route('childsubcollection.status.toggle') }}",
        method: "POST",
        data: {
            id: id,
            collection: collection,
            _token: "{{ csrf_token() }}"
        },
        // success: function(response) {
        //     if (response.success) {
        //         console.log('Updated: ', response.show_collection);
        //     }
        // },
         success: function(response) {
            if (response.success) {
                if (collection === 1) {
                    // ✅ Show success message when checked
                    alert('You have added this product in collection field successfully!');
                } else {
                    // Optional message when unchecked
                    alert('This product has been removed from collection field.');
                }
            }
        },
        error: function() {
            alert('Something went wrong!');
        }
    });
});
   </script>
   @push('scripts')
<script>
$(document).ready(function () {
    $('#childsubcategory-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("childsubcategory.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'category', name: 'category' },
            { data: 'subcategory', name: 'subcategory' },
            { data: 'title', name: 'title' },
            { data: 'status', name: 'status', orderable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
     @endsection