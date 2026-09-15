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
                   <!--<div class="page-header">
                       <h3 class="fw-bold mb-3">Seo Table</h3>
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
                               <a href="#">Tables</a>
                           </li>
                           <li class="separator">
                               <i class="icon-arrow-right"></i>
                           </li>
                           <li class="nav-item">
                               <a href="#">Datatables</a>
                           </li>
                       </ul> 
                   </div>-->


                   <div class="col-md-12">
                       <div class="card">
                           <div class="card-header">

                               <div class="d-flex align-items-center justify-content-between">
                                   <h4 class="card-title"> Seo Table</h4>
                                   <a href="{{ url('admin/createseo')}}"><button
                                           class="btn btn-primary btn-round ms-auto"><i class="fa fa-plus"
                                               style="padding-right:8px"></i>Add
                                           Seo</button></a>
                               </div>
                           </div>
                           <div class="card-body">
                               <!-- Modal -->
                               <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                                   <div class="modal-dialog" role="document">
                                       <div class="modal-content">
                                           <div class="modal-header border-0">
                                               <h5 class="modal-title">
                                                   <span class="fw-mediumbold"> New</span>
                                                   <span class="fw-light"> Row </span>
                                               </h5>
                                               <button type="button" class="close" data-dismiss="modal"
                                                   aria-label="Close">
                                                   <span aria-hidden="true">&times;</span>
                                               </button>
                                           </div>
                                           <div class="modal-body">
                                               <p class="small">
                                                   Create a new row using this form, make sure you
                                                   fill them all
                                               </p>
                                               <form>
                                                   <div class="row">
                                                       <div class="col-sm-12">
                                                           <div class="form-group form-group-default">
                                                               <label>Name</label>
                                                               <input id="addName" type="text" class="form-control"
                                                                   placeholder="fill name" />
                                                           </div>
                                                       </div>
                                                       <div class="col-md-6 pe-0">
                                                           <div class="form-group form-group-default">
                                                               <label>Position</label>
                                                               <input id="addPosition" type="text" class="form-control"
                                                                   placeholder="fill position" />
                                                           </div>
                                                       </div>
                                                       <div class="col-md-6">
                                                           <div class="form-group form-group-default">
                                                               <label>Office</label>
                                                               <input id="addOffice" type="text" class="form-control"
                                                                   placeholder="fill office" />
                                                           </div>
                                                       </div>
                                                   </div>
                                               </form>
                                           </div>
                                           <div class="modal-footer border-0">
                                               <button type="button" id="addRowButton" class="btn btn-primary">
                                                   Add
                                               </button>
                                               <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                   Close
                                               </button>
                                           </div>
                                       </div>
                                   </div>
                               </div>

                               <div class="table-responsive">
                                   <table id="seo-table" class="display table table-striped table-hover">
    <thead>
        <tr>
            <th>Id</th>
            <th>Slug</th>
            <th>Meta Title</th>
            <th>Meta Url</th>
            <th style="width: 10%">Action</th>
        </tr>
    </thead>
    <tbody>
        {{-- DataTables populate karega --}}
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
   @push('scripts')
<script>
$(document).ready(function () {
    $('#seo-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("seo.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'meta_slug', name: 'meta_slug' },
            { data: 'meta_title', name: 'meta_title' },
            { data: 'canonical_url', name: 'canonical_url' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
   @endsection