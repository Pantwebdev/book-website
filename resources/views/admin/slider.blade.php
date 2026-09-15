   @extends('admin.layout.app')

@section('content')

   
        <div class="container-fluid">
          <div class="page-inner">
            <div class="page-header">
              <h3 class="fw-bold mb-3">Slider Page</h3>
              <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                  <a href="">
                    <i class="icon-home"></i>
                  </a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="#">Slider Forms</a>
                </li>
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
                      <div class="alert alert-success alert-message">
                          {{ session('success') }}
                      </div>
                  @endif
                  
                  @if(session('error'))
                      <div class="alert alert-danger alert-message">
                          {{ session('error') }}
                      </div>
                  @endif

                  <form action="{{ route('slider.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header">
                      <div class="card-title">Form Elements</div>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <input type="hidden" class="form-control" id="email2" name="status" value="1" placeholder="Enter Status"/>
                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="exampleFormControlSelect1">select Category</label>
                            <select class="form-select" name="type" id="exampleFormControlSelect1">
                              <option value="">Select Category</option>
                              <option value="1">Homepage Hero Banner</option>
                              <!--<option value="2">Exam corner</option>-->
                            </select>
                            @error('type')
                              <small class="text-danger">{{ $message }}</small>
                            @enderror
                          </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="email2">Title</label>
                            <input type="text" class="form-control" name="title" id="email2" placeholder="Enter Title"/>
                            @error('title')<small class="text-danger">{{ $message }}</small>@enderror
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                             <label for="email2">Image</label>
                            <input type="file" name="image" class="form-control"  id="email2" placeholder="Enter Image"/>
                             @error('image')<small class="text-danger">{{ $message }}</small>@enderror
                             <small id="image-error" class="text-danger"></small>
                          </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="email2">Url</label>
                            <input type="text" class="form-control" name="url" id="email2" placeholder="Enter url"/>
                            @error('url')<small class="text-danger">{{ $message }}</small>@enderror
                          </div>
                        </div>
                      </div>
                      <div class="row"> 
                        <div class="col-md-12 col-lg-4">
                          <div class="form-group">
                            <label for="comment">Comment</label>
                            <textarea class="form-control" id="comment" name="description" rows="5"></textarea>
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
          <div class="container-fluid">
            <div class="page-inner">
              <div class="page-header">
                <h3 class="fw-bold mb-3">Slider Table</h3>
              </div>
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table id="slider-table" class="display table table-striped table-hover">
    <thead>
        <tr>
            <th>Id</th>
            <th>Category</th>
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
                url: '/admin/slider/status-toggle/' + id,
                method: 'GET',
                success: function(response) {
                    // Update status text
                    let statusSpan = $('#status-' + id);
                    if (response.status === 1) {
                        statusSpan.text('Active').removeClass('badge-danger').addClass('badge-success');
                        button.removeClass('btn-success').addClass('btn-warning').text('Deactivate');
                    } else {
                        statusSpan.text('Inactive').removeClass('badge-success').addClass('badge-danger');
                        button.removeClass('btn-warning').addClass('btn-success').text('Activate');
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
    $(document).ready(function() {
        // 10 seconds = 10000 milliseconds
        setTimeout(function() {
            $('.alert-message').fadeOut('slow');
        }, 10000);
    });
</script>
@push('scripts')
<script>
$(document).ready(function () {
    $('#slider-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("slider.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'category', name: 'category', orderable: false },
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


