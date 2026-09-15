@extends('admin.layout.app')

@section('content')
  <div class="container-fluid">
    <div class="page-inner">
      <div class="row">
        <div class="col-sm-6 col-md-3">
          <div class="card card-stats card-round">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-icon">
                  <div class="icon-big text-center icon-primary bubble-shadow-small">
                    <i class="fas fa-users"></i>
                  </div>
                </div>
                <div class="col col-stats ms-3 ms-sm-0">
                  <div class="numbers">
                    <p class="card-category">Total Customer</p>
                    <h4 class="card-title">{{ $totalCustomer }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-md-3">
          <div class="card card-stats card-round">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-icon">
                  <div class="icon-big text-center icon-info bubble-shadow-small">
                    <i class="fas fa-user-check"></i>
                  </div>
                </div>
                <div class="col col-stats ms-3 ms-sm-0">
                  <div class="numbers">
                    <p class="card-category">Today Orders</p>
                    <h4 class="card-title">{{ $todayOrders }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-md-3">
          <div class="card card-stats card-round">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-icon">
                  <div class="icon-big text-center icon-success bubble-shadow-small">
                    <i class="fas fa-luggage-cart"></i>
                  </div>
                </div>
                <div class="col col-stats ms-3 ms-sm-0">
                  <div class="numbers">
                      <p class="card-category">Total Sales</p>
                      <h4 class="card-title">{{$totalRevenue}}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-md-3">
          <div class="card card-stats card-round">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-icon">
                  <div class="icon-big text-center icon-secondary bubble-shadow-small">
                    <i class="far fa-check-circle"></i>
                  </div>
                </div>
                <div class="col col-stats ms-3 ms-sm-0">
                  <div class="numbers">
                    <p class="card-category">Total Order</p>
                    <h4 class="card-title">{{ $totalOrders }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
          <h3 class="fw-bold mb-3">Dashboard</h3>
          <h6 class="op-7 mb-2">Admin Dashboard</h6>
          
        </div>
      </div>
      <div class="col-md-12">
                       <div class="card">
                           <div class="card-header">
                              <div class="d-flex align-items-center justify-content-between">
                                <h4 class="card-title">Low Alert Product</h4>
                              </div>
                           </div>
                           <div class="card-body">
                              <div class="table-responsive">
                                <table id="add-row" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            
                                            <th>Sku</th>
                                            <th>Name</th>
                                            <th>Item Stock</th>
                                            <th>Image</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($lowStockProducts as $pp)
                                        <tr>
                                            
                                            <td>{{ $pp->sku }}</td>
                                            <td>{{ $pp->name }}</td>
                                            <td>
                                                {{ $pp->stock }}
                                                @if($pp->stock <= 5)
                                                    <span class="badge bg-danger ms-2">Low Stock!</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($pp->image)
                                                    <img src="{{ asset('userassets/image/product/' . $pp->image) }}"
                                                        width="60" height="60" alt="image">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
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
@endsection
