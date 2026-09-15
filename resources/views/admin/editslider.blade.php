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

            <form action="{{ route('slider.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-header">
                    <div class="card-title">Edit slider</div>
                </div>
                <div class="card-body">
                    

                    <div class="row">
                         <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label>Parent Category</label>
                                <select name="type" class="form-control">
                                    <option value="">Select Category</option>
                                    <option value="1" {{ $slider->type == 1 ? 'selected' : '' }}>Home Page Slider</option>
                                     <option value="2" {{ $slider->type == 2 ? 'selected' : '' }}>Shop By Trend</option>
                                    
                                </select>
                                @error('category')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" name="title" value="{{ old('title', $slider->title) }}">
                                @error('title')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control">
                            @error('image')<small class="text-danger">{{ $message }}</small>@enderror
                            @if($slider->image)
                              <img src="{{ asset('userassets/image/slider/' . $slider->image) }}" height="50" class="mt-2"><br>
                             <a href="{{ route('slider.image.remove', $slider->id) }}"class="btn btn-sm btn-danger mt-2"
                              onclick="return confirm('Are you sure you want to remove the image?')">Remove Image</a>
                              @endif

                            </div>
                        </div>

                       <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label>Url</label>
                                <input type="text" class="form-control" name="url" value="{{ old('url', $slider->url) }}">
                                @error('url')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-4">
                            <div class="form-group">
                                <label>Comment</label>
                                <textarea class="form-control" name="description" rows="5">{{ old('description', $slider->description) }}</textarea>
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
      
@endsection