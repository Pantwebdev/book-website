 <!-- Sidebar -->
 <div class="sidebar" data-background-color="dark">
     <div class="sidebar-logo">
         <!-- Logo Header -->
         <div class="logo-header" data-background-color="dark">
             <a href="{{ url('/') }}" class="logo">
                 <img src="{{url('adminassets/login/assets/images/ajhuie_logo.png')}}" alt="ajhuie_logo" class="navbar-brand"
                     height="50" />
             </a>
             <div class="nav-toggle">
                 <button class="btn btn-toggle toggle-sidebar">
                     <i class="gg-menu-right"></i>
                 </button>
                 <button class="btn btn-toggle sidenav-toggler">
                     <i class="gg-menu-left"></i>
                 </button>
             </div>
             <button class="topbar-toggler more">
                 <i class="gg-more-vertical-alt"></i>
             </button>
         </div>
         <!-- End Logo Header -->
     </div>
     <div class="sidebar-wrapper scrollbar scrollbar-inner">
         <div class="sidebar-content">
             <ul class="nav nav-secondary">
                 <li class="nav-item ">
                     <a href="{{url('admin/dashboard')}}" class="collapsed" aria-expanded="false">
                         <i class="fas fa-tachometer-alt"></i>
                         <p>Dashboard</p>
                         
                     </a>

                     
                    
                     <div class="collapse" id="dashboard">
                         <ul class="nav nav-collapse">
                             <li>
                                 <a href="{{url('admin/dashboard')}}">
                                     <span class="sub-item">Dashboard 1</span>
                                 </a>
                             </li>
                         </ul>
                     </div>
                     <div class="collapse" id="dashboard">
                         <ul class="nav nav-collapse">
                             <li>
                                 <a href="{{url('admin/contactshow')}}">
                                     <span class="sub-item">Cotact Form</span>
                                 </a>
                             </li>
                         </ul>
                     </div>
                 </li>
               
                 <li class="nav-item">
                     <a data-bs-toggle="collapse" href="#base">
                         <i class="fas fa-th-large"></i>
                         <p>Category</p>
                         <span class="caret"></span>
                     </a>
                     <div class="collapse" id="base">
                         
                         <ul class="nav nav-collapse">
                             <li>
                                 <a href="{{url('admin/category')}}">
                                    <i class="fas fa-th"></i>
                                     <p> Main Category</p>
                                 </a>
                             </li>
                             <li>
                                 <a href="{{url('admin/subcategory')}}">
                                    <i class="fas fa-people-carry"></i>
                                     <p>SubCategory</p>
                                 </a>
                             </li>
                             <li>
                                 <a href="{{url('admin/childsubcategory')}}">
                                    <i class="fas fa-pallet"></i>
                                     <p>Child SubCategory</p>
                                 </a>
                             </li>
                            
                         </ul>
                     </div>
                 </li>

                     <li class="nav-item">
                      <a data-bs-toggle="collapse" href="#homebase">
                           <i class="fas fa-home"></i>
                          <p>Main Page Content </p 
                          <span class="caret"></span>
                           <span class="caret"></span>
                      </a>

                      <div class="collapse" id="homebase">
                          <ul class="nav nav-collapse">
                              <!-- <li>
                                  <a href="{{ url('admin/link/create') }}">
                                      <i class="fas fa-book-reader"></i>
                                      <p>Header Text Slider</p>
                                  </a>
                              </li> -->
                              <li>
                                  <a href="{{url('admin/slider/create')}}">
                                  <i class="fab fa-slideshare"></i>
                                  <p>Homepage Hero Banner</p>
                                  
                                </a>
                              </li>
                              <li>
                                  <a href="{{ url('admin/showhomepagebanner') }}">
                                    <i class="fas fa-band-aid"></i>
                                      <p>Other Banner</p>
                                  </a>
                              </li>
                          </ul>
                      </div>
                  </li>
                  
                 <!--  <li class="nav-item">-->
                 <!--    <a data-bs-toggle="collapse" href="#bases">-->
                 <!--        <i class="fas fa-box-open"></i>-->
                 <!--        <p>Product</p>-->
                 <!--        <span class="caret"></span>-->
                 <!--    </a>-->
                 <!--    <div class="collapse" id="bases">-->
                 <!--        <ul class="nav nav-collapse">-->
                             
                 <!--            <li>-->
                 <!--                <a href="{{url('admin/showproduct')}}">-->
                 <!--                   <i class="fas fa-clipboard-list"></i>-->
                 <!--                    <p>Product List</p>-->
                 <!--                </a>-->
                 <!--            </li>-->
                 <!--         </ul>-->
                 <!--    </div>-->
                 <!--</li>-->

                  

                 <!-- <li class="nav-item">-->
                 <!--    <a data-bs-toggle="collapse" href="#order">-->
                 <!--        <i class="fab fa-first-order"></i>-->
                 <!--        <p>Orders</p>-->
                 <!--        <span class="caret"></span>-->
                 <!--    </a>-->
                 <!--    <div class="collapse" id="order">-->
                 <!--        <ul class="nav nav-collapse">-->
                 <!--             <li>-->
                 <!--                 <a href="{{ route('admin.orders.index') }}">-->
                 <!--                   <i class="far fa-folder-open"></i>-->
                 <!--                     <p>All Orders</p>-->
                 <!--                 </a>-->
                 <!--             </li>-->
                 <!--             <li>-->
                 <!--                 <a href="{{ route('admin.orders.index') }}?order_status=pending">-->
                 <!--                   <i class="fas fa-hourglass-half"></i>-->
                 <!--                     <p>Pending Orders</p>-->
                 <!--                 </a>-->
                 <!--             </li>-->
                 <!--             <li>-->
                 <!--                 <a href="{{ route('admin.orders.index') }}?order_status=processing">-->
                 <!--                   <i class="fas fa-cogs"></i>-->
                 <!--                     <p>Processing</p>-->
                 <!--                 </a>-->
                 <!--             </li>-->
                                            
                    
                 <!--        </ul>-->
                 <!--    </div>-->
                 <!--</li>-->
                 
                   <li class="nav-item">
                      <a href="{{url('admin/customer')}}" class="collapsed" aria-expanded="false">
                            <i class="fas fa-users"></i>
                             <p> All User</p>
                            
                      </a>
                 </li>
                 
                  <li class="nav-item">
                      <a href="{{ route('admin.orders.index') }}">
                       <i class="far fa-folder-open"></i>
                        <p>All Orders</p>
                      </a>
                 </li>
               
               <li class="nav-item">
                        <a href="{{url('admin/showproduct')}}">
                            <i class="fas fa-clipboard-list"></i>
                            <p>All Product </p>
                        </a>
                </li>

                 <li class="nav-item">
                     <a href="{{url('admin/discount-codes')}}">
                         <i class="fas fa-percent"></i>
                         <p>Discount Code</p>
                         <span class="badge badge-secondary"></span>
                     </a>
                 </li>
                 <li class="nav-item">
                  <a href="{{url('admin/contactshow')}}" class="collapsed" aria-expanded="false">
                        <i class="fas fa-file-signature"></i>
                         <p>Contact us Details</p>
                        
                     </a>
                 </li>
                 
                 <li class="nav-item">
                     <a href="{{url('/admin/checkavailability/list')}}">
                         <i class="fas fa-map-marker"></i>
                         <p>Pin Code List </p>
                         <span class="badge badge-secondary"></span>
                     </a>
                 </li>
                
                 <li class="nav-item">
                     <a href="{{url('admin/blog')}}">
                         <i class="fas fa-comment-alt"></i>
                         <p>Blog </p>
                         <span class="badge badge-secondary"></span>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="{{url('admin/showseo')}}">
                         <i class="fas fa-search"></i>
                         <p>Seo</p>
                         <span class="badge badge-secondary"></span>
                     </a>
                 </li>
                <li class="nav-item">
                     <a href="{{url('admin/printing-docunment')}}">
                         <i class="fas fa-shield-alt"></i>
                         <p>Print Details</p>
                         <span class="badge badge-secondary"></span>
                     </a>
                 </li>
                  <li class="nav-item">
                     <a href="{{url('admin/page')}}">
                         <i class="fas fa-shield-alt"></i>
                         <p>Policy Pages</p>
                         <span class="badge badge-secondary"></span>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="{{url('admin/setting/edit/1')}}">
                         <i class="fas fa-wrench"></i>
                         <p>Setting</p>
                         <span class="badge badge-secondary"></span>
                     </a>
                 </li>
                 
             </ul>
         </div>
     </div>
 </div>
 <!-- End Sidebar -->