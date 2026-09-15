   @extends('admin.layout.app')

   @section('content')


   <div class="container-fluid">
       <div class="page-inner">
           <div class="page-header">
               <h3 class="fw-bold mb-3">SubCategory Forms</h3>
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

                       <form action="{{ route('subcategory.update', $subcategory->id) }}" method="POST"
                           enctype="multipart/form-data">
                           @csrf
                           <div class="card-header">
                               <div class="card-title">Edit Subcategory</div>
                           </div>
                           <div class="card-body">


                               <div class="row">
                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>Parent Category</label>
                                           <select name="category_id" class="form-control">
                                               <option value="">Select Category</option>
                                               @foreach($categories as $category)
                                               <option value="{{ $category->id }}"
                                                   {{ $subcategory->category_id == $category->id ? 'selected' : '' }}>
                                                   {{ $category->title }}
                                               </option>
                                               @endforeach
                                           </select>
                                           @error('category')<small class="text-danger">{{ $message }}</small>@enderror
                                       </div>
                                   </div>

                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>Title</label>
                                           <input type="text" class="form-control" name="title"
                                               value="{{ old('title', $subcategory->title) }}">
                                           @error('title')<small class="text-danger">{{ $message }}</small>@enderror
                                       </div>
                                   </div>

                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>Image</label>
                                           <input type="file" name="image" class="form-control">
                                           @error('image')<small class="text-danger">{{ $message }}</small>@enderror
                                           @if($subcategory->image)
                                           <img src="{{ asset('userassets/image/menu/' . $subcategory->image) }}"
                                               height="50" class="mt-2"><br>
                                           <a href="{{ route('subcategory.image.remove', $subcategory->id) }}"
                                               class="btn btn-sm btn-danger mt-2"
                                               onclick="return confirm('Are you sure you want to remove the image?')">Remove
                                               Image</a>
                                           @endif

                                       </div>
                                   </div>
                                   {{-- ✅ Show Explore Image section only if show_explore == 1 --}}
                                   @if($subcategory->show_explore == 1)
                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label>Explore Image</label>
                                           <input type="file" name="explore_image" class="form-control">
                                           @error('explore_image')<small
                                               class="text-danger">{{ $message }}</small>@enderror

                                           @if($subcategory->explore_image)
                                           <img src="{{ asset('userassets/image/menu/' . $subcategory->explore_image) }}"
                                               height="50" class="mt-2"><br>
                                           <a href="{{ route('subcategory.explore.image.remove', $subcategory->id) }}"
                                               class="btn btn-sm btn-danger mt-2"
                                               onclick="return confirm('Are you sure you want to remove the explore image?')">
                                               Remove Image
                                           </a>
                                           @endif
                                       </div>
                                   </div>
                                   <div class="col-md-6 col-lg-6">
                                       <div class="form-group">
                                           <label>Description</label>
                                            <textarea class="form-control" name="description" rows="5"
                                                oninput="countWords(this)">{{ old('description', $subcategory->description) }}</textarea>

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
document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('showExploreCheckbox');
    const exploreDiv = document.getElementById('exploreImageDiv');

    checkbox.addEventListener('change', function() {
        if (this.checked) {
            exploreDiv.style.display = 'block';
        } else {
            exploreDiv.style.display = 'none';
        }
    });
});
   </script>
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