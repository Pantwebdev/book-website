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
                  <div class="card-header">
                    <div class="card-title">Form Elements</div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                          <label for="email2">Title</label>
                          <input type="text" class="form-control" id="email2" placeholder="Enter Email"/>
                         
                          
                        </div>
                     
                      </div>
                       
                      <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                           <label for="email2">Image</label>
                          <input type="file" class="form-control" id="email2" placeholder="Enter Email"/>
                          
                        </div>
                      </div>
                   <div class="row"> 
                      <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                          <label for="email2">Meta Title</label>
                          <input type="text" class="form-control" id="email2"  name="meta_title" placeholder="Enter Email"/>
                         
                          
                        </div>
                     
                      </div>
                    </div>
                   <div class="row"> 
                      <div class="col-md-12 col-lg-4">
                        <div class="form-group">
                          <label for="comment">Comment</label>
                          <textarea class="form-control" id="comment" name="meta_description" rows="5">
                          </textarea>
                        </div>
                     
                      </div>
                         
                       
                        
                    </div>
                  </div>
                  <div class="card-action">
                    <button class="btn btn-success">Submit</button>
                    <button class="btn btn-danger">Cancel</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      
@endsection