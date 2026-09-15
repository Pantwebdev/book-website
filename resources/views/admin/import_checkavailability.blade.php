   @extends('admin.layout.app')

   @section('content')


   <div class="container-fluide">
       <div class="page-inner">
           <div class="page-header">
               <h3 class="fw-bold mb-3">Pincode Forms</h3>
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
                       @if(session('success'))
                       <div class="alert alert-success" id="success-message">{{ session('success') }}</div>
                       @endif
                       @if(session('error'))
                       <div class="alert alert-danger" id="error-message">{{ session('error') }}</div>
                       @endif
                       <div class="row">

                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                       <a href="{{ route('checkavailability.sample') }}" class="btn btn-primary mb-3">
    Download Sample Excel
</a>
</div>

                                   </div>                                  
                               </div>
                       <form action="{{ route('checkavailability.import') }}" method="POST" enctype="multipart/form-data">
                           @csrf
                          
                           <div class="card-header">
                               <div class="card-title">Form Elements</div>
                           </div>
                           <div class="card-body">
                               <div class="row">

                                   <div class="col-md-6 col-lg-4">
                                       <div class="form-group">
                                           <label for="email2">Upload Excel File:</label>
                                           <input type="file" class="form-control" name="file" id="email2"
                                               placeholder="Enter Title" required/>

                                           @error('title')<small class="text-danger">{{ $message }}</small>@enderror

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
             <div class="container">
               <div class="page-inner">
                   <div class="page-header">
                       <h3 class="fw-bold mb-3">Pincode Table</h3>
                       <!-- <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                  <a href="#">
                    <i class="icon-home"></i>
                  </a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="#">Tables</a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="#">Datatables</a>
                </li>
              </ul> -->
                   </div>


                   <div class="col-md-12">
                       <div class="card">

                           <div class="card-body">
                               <!-- Modal -->


                               <div class="table-responsive">
                                   <table id="add-row" class="display table table-striped table-hover">
                                       <thead>
                                           <tr>
                                               <th>Id</th>
                                               <th>Pincode</th>
                                              

                                               <th>Status</th>
                                               <th style="width: 10%">Action</th>
                                           </tr>
                                       </thead>

                                       <tbody>
                                           




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
setTimeout(() => {
    const successMsg = document.getElementById('success-message');
    if (successMsg) {
        successMsg.style.transition = 'opacity 0.5s ease';
        successMsg.style.opacity = '0';
        setTimeout(() => successMsg.remove(), 500);
    }
}, 3000);

setTimeout(() => {
    const errorMsg = document.getElementById('error-message');
    if (errorMsg) {
        errorMsg.style.transition = 'opacity 0.5s ease';
        errorMsg.style.opacity = '0';
        setTimeout(() => errorMsg.remove(), 500);
    }
}, 3000);
</script>

@push('scripts')
<script>
$(document).ready(function() {
    $('#add-row').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("checkavailability.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'pincode', name: 'pincode' },
            { data: 'status', name: 'status', orderable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush

@endsection