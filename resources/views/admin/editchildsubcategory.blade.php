   @extends('admin.layout.app')

   @section('content')


   <div class="container-fluid">
       <div class="page-inner">
           <div class="page-header">
               <h3 class="fw-bold mb-3">Childsubcategory Forms</h3>
               
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

                       <form action="{{ route('childsubcategory.update', $childsubcategory->id) }}" method="POST"
                           enctype="multipart/form-data">
                           @csrf
                           <div class="card-header">
                               <div class="card-title">Edit Subcategory</div>
                           </div>
                           <div class="card-body">
                               <div class="row">
                                   <!-- Parent Category -->
                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>Parent Category</label>
                                           <select name="category_id" class="form-control">
                                               <option value="">Select Category</option>
                                               @foreach($categories as $category)
                                               <option value="{{ $category->id }}"
                                                   {{ $childsubcategory->category_id == $category->id ? 'selected' : '' }}>
                                                   {{ $category->title }}
                                               </option>
                                               @endforeach
                                           </select>
                                           @error('category_id')<small
                                               class="text-danger">{{ $message }}</small>@enderror
                                       </div>
                                   </div>

                                   <!-- Subcategory -->
                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>Subcategory</label>
                                           <select name="sub_category_id" class="form-control">
                                               <option value="">Select Subcategory</option>
                                               @foreach($subcategories as $sub)
                                               <option value="{{ $sub->id }}"
                                                   {{ $childsubcategory->sub_category_id == $sub->id ? 'selected' : '' }}>
                                                   {{ $sub->title }}
                                               </option>
                                               @endforeach
                                           </select>
                                           @error('sub_category_id')<small
                                               class="text-danger">{{ $message }}</small>@enderror
                                       </div>
                                   </div>

                                   <!-- Title -->
                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>Title</label>
                                           <input type="text" class="form-control" name="title"
                                               value="{{ old('title', $childsubcategory->title) }}">
                                           @error('title')<small class="text-danger">{{ $message }}</small>@enderror
                                       </div>
                                   </div>

                                   <!-- Image -->
                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>Image</label>
                                           <input type="file" name="image" class="form-control">
                                           @error('image')<small class="text-danger">{{ $message }}</small>@enderror
                                           @if($childsubcategory->image)
                                           <img src="{{ url('userassets/image/menu/' . $childsubcategory->image) }}"
                                               height="50" class="mt-2"><br>
                                           <a href="{{ route('childsubcategory.image.remove', $childsubcategory->id) }}"
                                               class="btn btn-sm btn-danger mt-2"
                                               onclick="return confirm('Are you sure you want to remove the image?')">Remove
                                               Image</a>
                                           @endif


                                       </div>
                                   </div>
                                    @if($childsubcategory->show_collection == 1)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label>Shop By Collection Image</label>
                                            <input type="file" name="collection_image" class="form-control">
                                            @error('collection_image')<small class="text-danger">{{ $message }}</small>@enderror
                                    
                                            @if($childsubcategory->collection_image)
                                                <img src="{{ asset('userassets/image/menu/' . $childsubcategory->collection_image) }}" height="50" class="mt-2"><br>
                                                <a href="{{ route('childsubcategory.collection.image.remove', $childsubcategory->id) }}"
                                                   class="btn btn-sm btn-danger mt-2"
                                                   onclick="return confirm('Are you sure you want to remove the explore image?')">
                                                   Remove Image
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                  
                                   
                                   <!-- Description -->
                                    <div class="col-md-12 col-lg-12">
                                       <div class="form-group">
                                           <label>Description</label>
                                           <textarea class="form-control" name="description" rows="5"
                                                oninput="countWords(this)">{{ old('description', $childsubcategory->description) }}</textarea>

                                            <small id="wordCount" class="text-muted"></small>
                                            @error('description')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                       </div>
                                    </div>
                                     @endif
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
   <script>
function countWords(el) {
    let words = el.value.trim().split(/\s+/).filter(word => word.length > 0);
    let count = words.length;
    document.getElementById('wordCount').innerText = "Words: " + count + "/15";

    if(count > 15){
        document.getElementById('wordCount').style.color = "red";
    } else {
        document.getElementById('wordCount').style.color = "green";
    }
}
</script>