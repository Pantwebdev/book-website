   @extends('admin.layout.app')

   @section('content')


   <div class="container-fluide">
       <div class="page-inner">
           <div class="page-header">
               <h3 class="fw-bold mb-3">Link Forms</h3>
               <ul class="breadcrumbs mb-3">
                   <!-- <li class="nav-home">
                  <a href="#">
                    <i class="icon-home"></i>
                  </a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li> -->
                   <!-- <li class="nav-item">
                  <a href="#">Forms</a>
                </li> -->
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
                       <!-- @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif -->
                       @if(session('success'))
                       <div class="alert alert-success" id="success-message">{{ session('success') }}</div>
                       @endif
                       @if(session('error'))
                       <div class="alert alert-danger" id="error-message">{{ session('error') }}</div>
                       @endif
                       <form action="{{ route('seo.store') }}" method="POST" enctype="multipart/form-data">
                           @csrf
                           <input type="hidden" class="form-control" name="status" id="email2" placeholder="Enter Title"
                               value="1" />

                           <div class="card-header">
                               <div class="card-title">Form Elements</div>
                           </div>
                           <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="sku">Slug:*</label>
                                            <input type="text" class="form-control" name="meta_slug" id="sku"
                                                placeholder="Enter SKU" />
                                            @error('meta_slug')<small class="text-danger">{{ $message }}</small>@enderror
                                            <small id="sku-error" class="text-danger"></small>
                                        </div>
                                    </div>
                                     <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="name">Meta Title :*</label>
                                            <input type="text" name="meta_title" class="form-control" id="name"
                                                placeholder="Enter Name" />
                                            @error('meta_title')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="sku">Image url:*</label>
                                            <input type="text" class="form-control" name="image_url" id="sku"
                                                placeholder="Enter SKU" />
                                            @error('image_url')<small class="text-danger">{{ $message }}</small>@enderror
                                            <small id="sku-error" class="text-danger"></small>
                                        </div>
                                    </div>
                                     <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="name">Canonical Tag /Url :*</label>
                                            <input type="text" name="canonical_url" class="form-control" id="name"
                                                placeholder="Enter Name" />
                                            @error('canonical_url')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="sku">Meta Keyword:*</label>
                                            <input type="text" class="form-control" name="meta_keyword" id="sku"
                                                placeholder="Enter SKU" />
                                            @error('meta_keyword')<small class="text-danger">{{ $message }}</small>@enderror
                                            <small id="sku-error" class="text-danger"></small>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                       <div class="col-md-12 col-lg-4">
                                           <div class="form-group">
                                               <label for="comment">Link Content</label>
                                               <textarea class="form-control" id="comment" name="meta_description"
                                                   rows="5"></textarea>
                                               @error('link')
                                               <span style="color: red;">{{ $message }}</span>
                                               @enderror
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
           
       </div>
   </div>
   </div>
  
   <script>
// Remove success message after 10 seconds
setTimeout(() => {
    const successMsg = document.getElementById('success-message');
    if (successMsg) {
        successMsg.style.transition = 'opacity 0.5s ease';
        successMsg.style.opacity = '0';
        setTimeout(() => successMsg.remove(), 500);
    }
}, 1000); // 10000ms = 10 seconds

// Remove error message after 10 seconds
setTimeout(() => {
    const errorMsg = document.getElementById('error-message');
    if (errorMsg) {
        errorMsg.style.transition = 'opacity 0.5s ease';
        errorMsg.style.opacity = '0';
        setTimeout(() => errorMsg.remove(), 500);
    }
}, 1000);
   </script>
   @endsection