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
                       <h3 class="fw-bold mb-3">Show Product</h3>
                       
                   </div>


                   <div class="col-md-12">
                       <div class="card">
                           <div class="card-header">

                               <div class="d-flex align-items-center justify-content-between">
                                   <h4 class="card-title">Add Product</h4>
                                   <a href="{{ url('admin/createproduct')}}"><button
                                           class="btn btn-primary btn-round ms-auto"><i class="fa fa-plus"
                                               style="padding-right:8px"></i>Add
                                           Product</button></a>
                               </div>
                           </div>
                           <div class="card-body">
                               

                               <div class="table-responsive">
                                   <table id="product-table" class="display table table-striped table-hover">
    <thead>
        <tr>
            <th>Id</th>
            <th>Category</th>
            <th>Sku</th>
            <th>Name</th>
            <th>Item Stock</th>
            <th>Image</th>
            <th style="width: 10%">Action</th>
        </tr>
    </thead>
    <tbody>
        {{-- DataTables will populate --}}
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
    $('#product-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("product.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'category', name: 'category' },
            { data: 'sku', name: 'sku' },
            { data: 'name', name: 'name' },
            { data: 'stock', name: 'stock', orderable: false },
            { data: 'image', name: 'image', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
   @endsection