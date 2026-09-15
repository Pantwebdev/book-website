   @extends('admin.layout.app')

   @section('content')


   <div class="container-fluide">
       <div class="page-inner">
           @if(session('success'))
           <div class="alert alert-success" id="success-message">{{ session('success') }}</div>
           @endif
           @if(session('error'))
           <div class="alert alert-danger" id="error-message">{{ session('error') }}</div>
           @endif


           <div class="container-fluid">
               <div class="page-inner">
                   <div class="page-header">
                       <h3 class="fw-bold mb-3">Home Banner Page</h3>
                       
                   </div>


                   <div class="col-md-12">
                       <div class="card">
                           <div class="card-header">

                               <div class="d-flex align-items-center justify-content-between">
                                   <h4 class="card-title">Banner</h4>
                                  
                               </div>
                           </div>
                           <div class="card-body">
                              

                               <div class="table-responsive">
                                   <table id="add-row" class="display table table-striped table-hover">
                                       <thead>
                                           <tr>
                                               <th>Id</th>
                                               
                                               <th>Type</th>
                                               <th>Image</th>

                                               <th style="width: 10%">Action</th>
                                           </tr>
                                       </thead>

                                       <tbody>

                                           @foreach($banner as $index=>$pp)
                                           <tr>
                                               <td>{{ $index+1 }}</td>
                                                <td>
                                                   @if($pp->type == 1)
                                                       Homepage Secondary Banner
                                                    @elseif($pp->type == 2)
                                                        Homepage Tertiary Banner
                                                        @else
                                                      List Page Banner
                                                    @endif
                                                
                                               </td>
                                               
                                               <td> @if($pp->image)
                                                   <img src="{{ asset('userassets/image/' . $pp->image) }}"
                                                       width="60" height="60" alt="image">
                                                   @else
                                                   <span class="text-muted">No Image</span>
                                                   @endif
                                               </td>
                                               <td>
                                                   <div class="form-button-action">
                                                       <a href="{{ route('homepagebanner.edit', $pp->id) }}"
                                                        class="btn btn-link btn-primary btn-lg" title="Edit">
                                                          <i class="fa fa-edit"></i>
                                                        </a>

                                                      
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
   <script>
// Remove success message after 10 seconds
setTimeout(() => {
    const successMsg = document.getElementById('success-message');
    if (successMsg) {
        successMsg.style.transition = 'opacity 0.5s ease';
        successMsg.style.opacity = '0';
        setTimeout(() => successMsg.remove(), 500);
    }
}, 300); // 10000ms = 10 seconds

// Remove error message after 10 seconds
setTimeout(() => {
    const errorMsg = document.getElementById('error-message');
    if (errorMsg) {
        errorMsg.style.transition = 'opacity 0.5s ease';
        errorMsg.style.opacity = '0';
        setTimeout(() => errorMsg.remove(), 500);
    }
}, 300);
   </script>
   @endsection