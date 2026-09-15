@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Gst /Tax</h3>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Create New Tax /Gst</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('tax.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Type *</label>
                                        <select name="type" class="form-control" required>
                                             <option value="fixed">Select Option</option>
                                            <option value="1">Gst</option>
                                            <option value="2">Tax</option>
                                        </select>
                                         @error('type')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tax *</label>
                                        <input type="text" name="tax" class="form-control" placeholder="e.g., AHU19">
                                         @error('tax')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                
                                
                            </div>

                            

                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary">Create </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <div class="card-title">All Discount Codes</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Index</th>
                                        <th>Type</th>
                                        <th>Tax</th>
                        
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($taxCodes as $index=>$code)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td>
                                            <strong>{{ $code->type == 1 ? 'GST' : ($code->type == 2 ? 'TAX' : '-') }}</strong>
                                        </td>
                                        
                                        <td>{{$code->tax}}</td>
                                        

                                        <td>
                                           
                                        </td>
                                        <td>
                                            <button type="button" 
                                                class="btn btn-link btn-primary btn-lg editBtn" 
                                                data-id="{{ $code->id }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal">
                                                <i class="fa fa-edit"></i>
                                            </button>



                                            <form action="{{ route('tax.destroy', $code->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf @method('DELETE')

                                                <button class="btn btn-link btn-danger" type="submit"
                                                    onclick="return confirm('Are you sure to delete?')" title="Delete">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </form>
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
<!-- ===================== Edit Modal ===================== -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" id="editForm">
        @csrf
        @method('PUT')

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit GST / TAX</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="form-group">
                    <label>Type *</label>
                    <select name="type" id="edit_type" class="form-control" required>
                        <option value="1">GST</option>
                        <option value="2">TAX</option>
                    </select>
                </div>

                <div class="form-group mt-3">
                    <label>Tax *</label>
                    <input type="text" name="tax" id="edit_tax" class="form-control" required>
                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </form>
  </div>
</div>

@endsection

<script>
$(document).on("click", ".editBtn", function () {

    let id = $(this).data("id");

    // Dynamic form action
    $("#editForm").attr("action", "/admin/tax/" + id);

    // DB से data fetch करना
    $.ajax({
        url: "/admin/tax/get/" + id,
        type: "GET",
        success: function (data) {

            // Dropdown selected
            $("#edit_type").val(data.type).change();

            // Tax value set
            $("#edit_tax").val(data.tax);
        }
    });

});
</script>


