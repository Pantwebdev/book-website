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

                       <form action="{{ route('blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
                           @csrf
                           
                           <div class="card-body">
                               <div class="row">
                                   <!-- Title -->
                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>Title</label>
                                           <input type="text" class="form-control" name="title"
                                               value="{{ old('title', $blog->title) }}">
                                           @error('title')<small class="text-danger">{{ $message }}</small>@enderror
                                       </div>
                                   </div>
                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label for="email2">Short Content</label>
                                           <input type="text" class="form-control" name="short_content"
                                               value="{{ old('short_content', $blog->short_content) }}" id="email2"
                                               placeholder="Enter Short Content" />
                                           @error('short_content')<small
                                               class="text-danger">{{ $message }}</small>@enderror

                                       </div>

                                   </div>

                                   <!-- Image -->
                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>Image</label>
                                           <input type="file" name="image" class="form-control">
                                           @error('image')<small class="text-danger">{{ $message }}</small>@enderror
                                           @if($blog->image)
                                           <img src="{{ url('userassets/image/blog/' . $blog->image) }}" height="50"
                                               class="mt-2"><br>
                                           <a href="{{ route('blog.image.remove', $blog->id) }}"
                                               class="btn btn-sm btn-danger mt-2"
                                               onclick="return confirm('Are you sure you want to remove the image?')">Remove
                                               Image</a>
                                           @endif


                                       </div>
                                   </div>

                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label for="email2">Alt Tag</label>
                                           <input type="text" class="form-control" id="email2" name="alt_tag"
                                               placeholder="Enter Alt Lag"
                                               value="{{ old('alt_tag', $blog->alt_tag) }}" />


                                       </div>

                                   </div>


                                   <!-- Description -->
                                   <div class="col-md-12 col-lg-12">
                                       <div class="form-group">
                                           <label>Content</label>
                                           <textarea class="form-control" name="content"
                                               rows="5">{{ old('description', $blog->content) }}</textarea>
                                       </div>
                                   </div>
                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>Meta Title</label>
                                           <input type="text" class="form-control" name="metatitle"
                                               value="{{ old('metatitle', $blog->metatitle) }}">
                                           @error('metatitle')<small class="text-danger">{{ $message }}</small>@enderror
                                       </div>
                                   </div>
                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label for="email2">Connitial Tag/Url</label>
                                           <input type="text" class="form-control" name="connitialtag"
                                               value="{{ old('connitialtag', $blog->connitialtag) }}" id="email2"
                                               placeholder="Enter connitial tag" />
                                           @error('connitialtag')<small
                                               class="text-danger">{{ $message }}</small>@enderror

                                       </div>

                                   </div>

                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>Image Url</label>
                                           <input type="text" class="form-control" name="imageurl"
                                               value="{{ old('imageurl', $blog->imageurl) }}">
                                           @error('imageurl')<small class="text-danger">{{ $message }}</small>@enderror
                                       </div>
                                   </div>
                                   <div class="col-md-12 col-lg-12">
                                       <div class="form-group">
                                           <label>Meta Description</label>
                                           <textarea class="form-control" name="description"
                                               rows="5">{{ old('description', $blog->description) }}</textarea>
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
    <style>
.cke_notifications_area {
    display: none !important;
}
</style>
   <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    CKEDITOR.replace('content');
});
</script>