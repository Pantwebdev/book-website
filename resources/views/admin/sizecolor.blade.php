   @extends('admin.layout.app')

@section('content')

   
        <div class="container-fluide">
          <div class="page-inner">
            <div class="page-header">
              <h3 class="fw-bold mb-3">authors/publishers Forms</h3>
             
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                       @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
                <form action="{{ route('sizecolor.store') }}" method="POST" enctype="multipart/form-data">
                     @csrf
                      <input type="hidden" class="form-control" name="status" id="email2" placeholder="Enter Title" value="1"/>
                  <div class="card-header">
                    <div class="card-title">Form Elements</div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                              <label>Select Type</label>
                            <select class="form-select" id="subcategory" name="type">
                          <option value="">Select Type</option>
                          <option value="1">authors</option>
                          <option value="2">publishers</option>
                          </select>
                          
                        </div>
                      </div> 
                      <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                          <label for="email2">Name</label>
                          <input type="text" class="form-control" name="name" id="name" placeholder="Enter Title"/>
                          @error('title')<small class="text-danger">{{ $message }}</small>@enderror
                          
                        </div>
                     
                      </div>
                    </div>
                    <div class="row" id="authorFields">
                       
                      <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                          <label for="bio">Bio</label>
                          <input type="text" class="form-control" name="bio" id="bio" placeholder="Enter Bio"/>
                          @error('title')<small class="text-danger">{{ $message }}</small>@enderror
                          
                        </div>
                     
                      </div>
                       
                      <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                           <label for="image">Image</label>
                          <input type="file" name="image" class="form-control"  id="image" placeholder="Enter Image"/>
                           @error('image')<small class="text-danger">{{ $message }}</small>@enderror
                           <small id="image-error" class="text-danger"></small>

                        </div>
                      </div>
                    </div>
                    <div class="row" id="publisherFields">
                       
                      <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                          <label for="website">Website</label>
                          <input type="text" class="form-control" name="website" id="website" placeholder="Enter website"/>
                          @error('website')<small class="text-danger">{{ $message }}</small>@enderror

                        </div>
                     
                      </div>
                       
                      <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                           <label for="address">Address</label>
                          <input type="text" class="form-control" name="address" id="address" placeholder="Enter address"/>
                          @error('address')<small class="text-danger">{{ $message }}</small>@enderror
                           <small id="image-error" class="text-danger"></small>

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
              <h3 class="fw-bold mb-3">authors /publishers Table </h3>
              
            </div>
            

              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    
                  </div>
                  <div class="card-body">
                
                    <div class="table-responsive">
                      <table
                        id="add-row"
                        class="display table table-striped table-hover"
                      >
                        <thead>
                          <tr>
                            <th>Id</th>
                             <th>Type</th>
                            <th>Title</th>
                        
                           
                            <th style="width: 10%">Action</th>
                          </tr>
                        </thead>
                        
                        <tbody>
                            @foreach($color as $index=>$cg)
                          <tr>
                            <td>{{ $index+1 }}</td>
                            <td>
                            @if($cg->type == 1)
                                authors
                            @elseif($cg->type == 2)
                                publishers
                            @else
                                -
                            @endif
                             </td>
                            <td>{{$cg->name}}</td>
                            
                            <td>
                                <div class="form-button-action">
                                    <a href="{{ route('sizecolor.edit', $cg->id) }}" class="btn btn-link btn-primary btn-lg" title="Edit">
                                      <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('sizecolor.delete', $cg->id) }}" method="POST" style="display:inline;">
                                      @csrf
                                      @method('DELETE')
                                      <button class="btn btn-link btn-danger" type="submit" onclick="return confirm('Are you sure to delete?')" title="Delete">
                                       <i class="fa fa-times"></i>
                                      </button>
                                    </form>
                                </div>
                            </td>
                          </tr>
                            @endforeach
                          
                          
                         
                            
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
      
@endsection
<style>
#authorFields,
#publisherFields {
    display: none;
}
</style>
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

    typeSelect.addEventListener('change', toggleFields);

    toggleFields(); // page load pe run kare
});
</script>

