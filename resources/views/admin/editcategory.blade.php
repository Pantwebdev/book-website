   @extends('admin.layout.app')

   @section('content')


   <div class="container-fluid">
       <div class="page-inner">
           <div class="page-header">
               <h3 class="fw-bold mb-3">Category Forms</h3>
               
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

                       <form action="{{ route('category.update', $category->id) }}" method="POST"
                           enctype="multipart/form-data">
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
                                           <input type="text" class="form-control" name="title"
                                               value="{{ old('title', $category->title) }}">
                                           @error('title')<small class="text-danger">{{ $message }}</small>@enderror
                                       </div>
                                   </div>

                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>Main Image</label>
                                           <input type="file" name="image" class="form-control">
                                           @error('image')<small class="text-danger">{{ $message }}</small>@enderror
                                           @if($category->image)
                                           <img src="{{ asset('userassets/image/menu/' . $category->image) }}"
                                               height="50" class="mt-2"><br>
                                           <a href="{{ route('category.image.remove', $category->id) }}"
                                               class="btn btn-sm btn-danger mt-2"
                                               onclick="return confirm('Are you sure you want to remove the image?')">Remove
                                               Image</a>
                                           @endif

                                       </div>
                                   </div>

                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>Alt Tag</label>
                                           <input type="text" class="form-control" name="alt_tag"
                                               value="{{ old('alt_tag', $category->alt_tag) }}">
                                           @error('alt_tag')<small class="text-danger">{{ $message }}</small>@enderror
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