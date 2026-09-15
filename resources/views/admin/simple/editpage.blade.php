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

                       <form action="{{ route('page.update', $page->id) }}" method="POST" enctype="multipart/form-data">
                           @csrf
                           <div class="card-header">
                               <div class="card-title">Edit Subcategory</div>
                           </div>
                           <div class="card-body">
                               <div class="row">
                                   <!-- Title -->
                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>Title</label>
                                           <input type="text" class="form-control" name="title"
                                               value="{{ old('title', $page->title) }}">
                                           @error('title')<small class="text-danger">{{ $message }}</small>@enderror
                                       </div>
                                   </div>
                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label for="email2">Short Content</label>
                                           <input type="text" class="form-control" name="short_content"
                                               value="{{ old('short_content', $page->short_content) }}" id="email2"
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
                                           @if($page->image)
                                           <img src="{{ url('userassets/image/simple/' . $page->image) }}" height="50"
                                               class="mt-2"><br>
                                           <a href="{{ route('page.image.remove', $page->id) }}"
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
                                               value="{{ old('alt_tag', $page->alt_tag) }}" />


                                       </div>

                                   </div>


                                   <!-- Description -->
                                   <div class="col-md-12 col-lg-12">
                                       <div class="form-group">
                                           <label>Content</label>
                                           <textarea class="form-control" id="content" name="content"
                                               rows="5">{{ old('content', $page->content) }}</textarea>
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
@push('scripts')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    if (document.getElementById('content')) {
        CKEDITOR.replace('content');
    }
</script>
@endpush