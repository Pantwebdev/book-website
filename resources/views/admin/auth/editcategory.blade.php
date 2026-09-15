   @extends('admin.layout.app')

@section('content')

   
        <div class="container">
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

            <form action="{{ route('category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-header">
                    <div class="card-title">Edit Category</div>
                </div>
                <div class="card-body">
                    <input type="hidden" name="status" value="1">

                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" name="title" value="{{ old('title', $category->title) }}">
                                @error('title')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" name="image" class="form-control">
                                @error('image')<small class="text-danger">{{ $message }}</small>@enderror
                                @if($category->image)
                                    <img src="{{ asset('userassets/image/menu/' . $category->image) }}" height="50" class="mt-2">
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label>Meta Title</label>
                                <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title', $category->meta_title) }}">
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-4">
                            <div class="form-group">
                                <label>Comment</label>
                                <textarea class="form-control" name="meta_description" rows="5">{{ old('meta_description', $category->meta_description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-action">
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="{{ route('category') }}" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
          </div>
        </div>
      
@endsection