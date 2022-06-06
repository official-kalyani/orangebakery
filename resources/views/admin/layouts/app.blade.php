<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Admin Panel</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{asset('/admin/css/all.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('/admin/css/style.min.css')}}">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="{{asset('/admin/css/style.css')}}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @if(request()->is('admin/coupons/create') || request()->is('admin/orders'))
  
  @else
    <script src="{{asset('/admin/js/jquery.min.js')}}"></script>
  @endif
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('/admin/home')}}" class="nav-link">Home</a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        
      </div>
    </form>

    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item">
        <div class="row">
          <div class="col-md-8">
            <a href="{{ url('/admin/change-password') }}" class="nav-link">Change Password</a>
          </div>
          <div class="col-md-4">
            <a href="{{ url('/admin/logout') }}"
                onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();" class="nav-link">
                Logout
            </a>
            <form id="logout-form" action="{{ url('/admin/logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
          </div>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <!-- <a href="index3.html" class="brand-link">
      <img src="{{asset('/admin/images/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Admin Panel</span>
    </a> -->

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('/admin/images/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{Auth::user()->name}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{url('/admin/home')}}" class="nav-link {{ request()->is('shop/home') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item {{ request()->is('admin/products') || request()->is('admin/category') || request()->is('admin/subcategory') || request()->is('admin/additional-products') || request()->is('admin/occasion') ? 'has-treeview menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('admin/products') || request()->is('admin/category') || request()->is('admin/subcategory') || request()->is('admin/additional-products') || request()->is('admin/occasion') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Products
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/admin/products')}}" class="nav-link {{ request()->is('admin/products') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    All
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/category')}}" class="nav-link {{ request()->is('admin/category') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Category
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/subcategory')}}" class="nav-link {{ request()->is('admin/subcategory') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Subcategory
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/occasion')}}" class="nav-link {{ request()->is('admin/occasion') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Occasion
                  </p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item {{ request()->is('admin/customize-shapes') || request()->is('admin/customize-flavours') ? 'has-treeview menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('admin/customize-shapes') || request()->is('admin/customize-flavours') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Customize Cakes
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/admin/customize-shapes')}}" class="nav-link {{ request()->is('admin/customize-shapes') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Shapes
                  </p>
                </a>
              </li>
             <!--  <li class="nav-item">
                <a href="{{url('/admin/customize-flavours')}}" class="nav-link {{ request()->is('admin/customize-flavours') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Flavours
                  </p>
                </a>
              </li> -->
            <li class="nav-item">
                <a href="{{url('/admin/customize-colours')}}" class="nav-link {{ request()->is('admin/customize-colours') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Colours
                  </p>
                </a>
              </li>
             <li class="nav-item">
                <a href="{{url('/admin/customize-galleries')}}" class="nav-link {{ request()->is('admin/customize-galleries') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Galleries
                  </p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="{{url('/admin/delivery-boy')}}" class="nav-link {{ request()->is('admin/delivery-boy') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                DeliveryBoy
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('/admin/shops')}}" class="nav-link {{ request()->is('admin/shops') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Stores
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('/admin/orders')}}" class="nav-link {{ request()->is('admin/orders') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Orders
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('/admin/coupons')}}" class="nav-link {{ request()->is('admin/coupons') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Coupons
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('/admin/sliders')}}" class="nav-link {{ request()->is('admin/sliders') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Home Page Image Sliders
              </p>
            </a>
          </li>
         <!-- <li class="nav-item">
            <a href="{{url('/admin/above_news')}}" class="nav-link {{ request()->is('admin/above_news') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Above section of newsletter
              </p>
            </a>
          </li> -->
          <li class="nav-item">
            <a href="{{url('/admin/login-signup-banner')}}" class="nav-link {{ request()->is('admin/login-signup-banner') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Login Signup Adbanner
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('/admin/customers')}}" class="nav-link {{ request()->is('admin/customers') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Customers
              </p>
            </a>
          </li>
          <li class="nav-item {{ request()->is('admin/manage-obcoins') || request()->is('admin/customer-obcoins') ? 'has-treeview menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('admin/manage-obcoins') || request()->is('admin/customer-obcoins') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Obcoins
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview {{ request()->is('admin/manage-obcoins') ? 'active' : '' }}">
              <li class="nav-item">
                <a href="{{url('/admin/manage-obcoins')}}" class="nav-link {{ request()->is('admin/manage-obcoins') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Obcoins</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/customer-obcoins')}}" class="nav-link {{ request()->is('admin/customer-obcoins') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Customer Obcoins</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item {{ request()->is('admin/blogs') || request()->is('admin/blog-category') ? 'has-treeview menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('admin/blogs') || request()->is('admin/blog-category') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Blogs
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview {{ request()->is('admin/blogs') ? 'active' : '' }}">
              <li class="nav-item">
                <a href="{{url('/admin/blogs')}}" class="nav-link {{ request()->is('admin/blogs') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/blog-category')}}" class="nav-link {{ request()->is('admin/blog-category') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Blog Category</p>
                </a>
              </li>
            </ul>
          </li>
          <!-- <li class="nav-item">
            <a href="{{url('/admin/contacts')}}" class="nav-link {{ request()->is('admin/contacts') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Pages
              </p>
            </a>
          </li> -->

        <!--  <li class="nav-item {{ request()->is('admin/privacypolicy') || request()->is('admin/toc') ? 'has-treeview menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('admin/privacypolicy') || request()->is('admin/toc') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Pages
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview {{ request()->is('admin/privacypolicy') ? 'active' : '' }}">
              <li class="nav-item">
                <a href="{{url('/admin/privacypolicy')}}" class="nav-link {{ request()->is('admin/privacypolicy') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Privacy Policy</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/toc')}}" class="nav-link {{ request()->is('admin/toc') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Terms and Conditions</p>
                </a>
              </li>
            </ul>
          </li> -->
          <li class="nav-item {{ request()->is('admin/email') || request()->is('admin/sms') || request()->is('admin/push-notification') ? 'has-treeview menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('admin/email') || request()->is('admin/sms') || request()->is('admin/push-notification') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Marketing
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview {{ request()->is('admin/email') ? 'active' : '' }}">
              <li class="nav-item">
                <a href="{{url('/admin/email')}}" class="nav-link {{ request()->is('admin/email') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Email</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/sms')}}" class="nav-link {{ request()->is('admin/sms') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sms</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/push-notification')}}" class="nav-link {{ request()->is('admin/push-notification') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>In App Push Notifications</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="{{url('/admin/app-section')}}" class="nav-link {{ request()->is('admin/app-section') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Apps
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('/admin/newsletter')}}" class="nav-link {{ request()->is('admin/newsletter') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                NewsLetter Emails
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('/admin/contacts')}}" class="nav-link {{ request()->is('admin/contacts') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Contact
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('/admin/testimonials')}}" class="nav-link {{ request()->is('admin/testimonials') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Testimonials
              </p>
            </a>
          </li>
      <li class="nav-item {{ request()->is('admin/deliverycharge') || request()->is('admin/ordertime')  ? 'has-treeview menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('admin/deliverycharge') || request()->is('admin/ordertime')  ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Settings
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview {{ request()->is('admin/deliverycharge') ? 'active' : '' }}">
              <li class="nav-item">
                <a href="{{url('/admin/deliverycharge')}}" class="nav-link {{ request()->is('admin/deliverycharge') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Delivery Charge Edit</p>
                </a>
              </li>
             <!--  <li class="nav-item">
                <a href="{{url('/admin/ordertime')}}" class="nav-link {{ request()->is('admin/ordertime') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Order time edit</p>
                </a>
              </li> -->
              
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
   @yield('content')
  </div>
  <!-- /.content-wrapper -->
  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Anything you want
    </div>

  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<script src="{{asset('/admin/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('/admin/js/adminlte.min.js')}}"></script>
</body>
</html>
