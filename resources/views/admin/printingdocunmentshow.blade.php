   @extends('admin.layout.app')

@section('content')

   
        <div class="container-fluide">
          <div class="page-inner">
            
        <div class="container-fluide">
          <div class="page-inner">
            <div class="page-header">
              <h3 class="fw-bold mb-3">Print Docunment Page</h3>
             
            </div>
            

              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    
                  </div>
                  <div class="card-body">
                    <!-- Modal -->
                    

                    <div class="table-responsive">
                      <table id="printing-table" class="display table table-striped table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>File</th>
            <th>Paper Size</th>
            <th>Print Type</th>
            <th>Copies</th>
            <th>Pages</th>
            <th>Total Amount</th>
            <th>Paid Amount</th>
            <th>Remaining Amount</th>
            <th>Payment</th>
            <th>Date</th>
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
    $('#printing-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("printing.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            { data: 'file', name: 'file', orderable: false, searchable: false },
            { data: 'paper_size', name: 'paper_size' },
            { data: 'print_type', name: 'print_type' },
            { data: 'copies', name: 'copies' },
            { data: 'pages', name: 'pages' },
            { data: 'total_amount', name: 'total_amount' },
            { data: 'paid_amount', name: 'paid_amount' },
            { data: 'remaining_amount', name: 'remaining_amount' },
            { data: 'payment', name: 'payment', orderable: false, searchable: false },
            { data: 'date', name: 'created_at' }
        ]
    });
});
</script>
@endpush   
@endsection

