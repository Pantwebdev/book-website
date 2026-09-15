   @extends('admin.layout.app')

@section('content')

   
        <div class="container-fluide">
          <div class="page-inner">
            
        <div class="container-fluide">
          <div class="page-inner">
            <div class="page-header">
              <h3 class="fw-bold mb-3">Customer Form</h3>
             
            </div>
            

              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    
                  </div>
                  <div class="card-body">
                    <!-- Modal -->
                    

                    <div class="table-responsive">
                      <table id="customer-table" class="display table table-striped table-hover">
    <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
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
      @push('scripts')
<script>
$(document).ready(function () {
    $('#customer-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("customer.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' }
        ]
    });
});
</script>
@endpush
@endsection

