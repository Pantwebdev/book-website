@extends('admin.layout.app')
@section('content')
    <div class="container-fluide">
        <div class="page-inner">
            <div class="page-header">
               <h3 class="fw-bold mb-3">Category Forms</h3>
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
                        <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" class="form-control" name="status" id="email2" placeholder="Enter Title"
                                value="1" />
                            <input type="hidden" class="form-control" name="show_collaj" id="email2"
                                placeholder="Enter collag" value="0" />
                            <div class="card-header">
                                <div class="card-title">Form Elements</div>
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
                                            <label for="email2">Image</label>
                                            <input type="file" name="image" class="form-control" id="email2"
                                                placeholder="Enter Image" />
                                            @error('image')<small class="text-danger">{{ $message }}</small>@enderror
                                            <small id="image-error" class="text-danger"></small>
 
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="email2">Alt Tag</label>
                                                <input type="text" class="form-control" id="email2" name="alt_tag"
                                                    placeholder="Enter Alt Lag" />
 
 
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
                    </div>
                    <div class="col-md-12">
                        <div class="card">
 
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="category-table" class="display table table-striped table-hover">
    <thead>
        <tr>
            <th>Id</th>
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
 
 
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 
   <script>
document.querySelector('form').addEventListener('submit', function(e) {
    const imageInput = document.querySelector('input[name="image"]');
    const errorElement = document.getElementById('image-error');
    errorElement.textContent = ''; // clear previous error

    if (imageInput.files.length > 0) {
        const file = imageInput.files[0];
        const maxSize = 2 * 1024 * 1024; 
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
            url: '/admin/category/status-toggle/' + id,
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
        url: "{{ route('colaj.status.toggle') }}",
        method: "POST",
        data: {
            id: id,
            collaj: collaj, // ✅ This will go as show_collaj in controller
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            if (response.success) {
                console.log('Updated: ', response.show_collaj);
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
$(document).on('change', '.collection-toggle', function() {
    var id = $(this).data('id');
    var collection = $(this).is(':checked') ? 1 : 0;

    $.ajax({
        url: "{{ route('collection.status.toggle') }}",
        method: "POST",
        data: {
            id: id,
            collection: collection,
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            if (response.success) {
                console.log('Updated: ', response.show_collection);
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
    $('#category-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("category.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'status', name: 'status', orderable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
  @endsection