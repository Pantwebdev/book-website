   @extends('admin.layout.app')

@section('content')

   
        <div class="container-fluid">
          <div class="page-inner">
            <div class="page-header">
              <h3 class="fw-bold mb-3">authors /publishers Forms</h3>
              
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

            <form action="{{ route('sizecolor.update', $color->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-header">
                    <div class="card-title">Edit authors publishers</div>
                </div>
                <div class="card-body">
                    <input type="hidden" name="status" value="1">
                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                  <label>Select Type</label>
                               <select class="form-select" id="subcategory" name="type">
                                    <option value="">Select Type</option>
                                    <option value="1" {{ $color->type == 1 ? 'selected' : '' }}>authors</option>
                                    <option value="2" {{ $color->type == 2 ? 'selected' : '' }}>publishers</option>
                                </select>
    
                              
                            </div>
                        </div> 
                       <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $color->name) }}">
                                @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                     
                      </div>
                    </div>
                    <div class="row" id="authorFields">
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label>Bio</label>
                                <input type="text" class="form-control" name="bio" value="{{ old('bio', $color->bio) }}">
                                @error('bio')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control">
                            @error('image')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

 
                          <small id="image-error" class="text-danger"></small>
                            @if($color->image)
                              <img src="{{ asset('userassets/image/color/' . $color->image) }}" height="50" class="mt-2"><br>
                             <a href="{{ route('sizecolor.image.remove', $color->id) }}"class="btn btn-sm btn-danger mt-2"
                              onclick="return confirm('Are you sure you want to remove the image?')">Remove Image</a>
                              @endif

                            </div>
                        </div>

                      
                    </div>
                     <div class="row" id="publisherFields">
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label>Website</label>
                                <input type="text" class="form-control" name="website" value="{{ old('website', $color->website) }}">
                                @error('website')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" class="form-control" name="address" value="{{ old('address', $color->address) }}">
                                @error('address')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                        

                      
                    </div>
                </div>
                <div class="card-action">
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="{{ route('sizecolor.create') }}" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
          </div>
        </div>
      <script>
<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const imageInput = document.querySelector('input[name="image"]');
    const errorElement = document.getElementById('image-error');
    errorElement.textContent = ''; // clear previous error

    if (imageInput.files.length > 0) {
        const file = imageInput.files[0];
        const maxSize = 2 * 1024 * 1024; // 2MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/svg+xml'];

        if (!allowedTypes.includes(file.type)) {
            errorElement.textContent = 'Only JPG, JPEG, PNG, or SVG images are allowed.';
            e.preventDefault();
            return;
        }

        if (file.size > maxSize) {
            errorElement.textContent = 'The image is too long. Only images less than 2MB are supported.';
            e.preventDefault();
        }
    }
});
</script>

</script>

@endsection
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

<script>
document.addEventListener('DOMContentLoaded', function() {

    const typeSelect = document.getElementById('subcategory');
    const authorFields = document.getElementById('authorFields');
    const publisherFields = document.getElementById('publisherFields');

    function toggleFields() {
        if (typeSelect.value == '1') {
            authorFields.style.display = 'block';
            publisherFields.style.display = 'none';
        } 
        else if (typeSelect.value == '2') {
            authorFields.style.display = 'none';
            publisherFields.style.display = 'block';
        } 
        else {
            authorFields.style.display = 'none';
            publisherFields.style.display = 'none';
        }
    }

    toggleFields(); // important for edit page
    typeSelect.addEventListener('change', toggleFields);
});
</script>
