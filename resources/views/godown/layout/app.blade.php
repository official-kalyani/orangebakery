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
  <script src="{{asset('/admin/js/jquery.min.js')}}"></script>
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
      @include('godown.layout.notifications')
      <!-- Messages Dropdown Menu -->
      <li class="nav-item">
        <div class="row">
          <div class="col-md-8">
            <a href="{{ url('/godown/change-password') }}" class="nav-link">Change Password</a>
          </div>
          <div class="col-md-4">
            <a href="{{ url('/godown/logout') }}"
                onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();" class="nav-link">
                Logout
            </a>
            <form id="logout-form" action="{{ url('/godown/logout') }}" method="POST" style="display: none;">
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
            <a href="{{url('/godown/home')}}" class="nav-link {{ request()->is('godown/home') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item {{ request()->is('godown/manage-orders') ? 'has-treeview menu-open' : '' }}">
            <a href="{{url('godown/manage-orders')}}" class="nav-link {{ request()->is('godown/manage-orders') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Manage Orders
              </p>
            </a>
          </li>
          <li class="nav-item {{ request()->is('godown/delivery-boy') ? 'has-treeview menu-open' : '' }}">
            <a href="{{url('godown/delivery-boy')}}" class="nav-link {{ request()->is('godown/delivery-boy') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Delivery Boys
              </p>
            </a>
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
<script type="text/javascript">
$(document).ready(function() {
  setInterval(get_refresh, 10000);
  function get_refresh(){
    $.ajax({
        url: "{{url('godown/get_refresh')}}",
        method: "POST",
        data: {_token: '{{ csrf_token() }}'},
        dataType: "json",
        success: function (response) {
          $("#notifications").html(response.notifications);
          console.log(response.notifications);
          // if (response.new_notification_audio > 0) {
          //   $('<audio id="chatAudio"><source src="{{ url('/') }}/audio/notify.mp3" type="audio/mpeg"></audio>').appendTo('body');
          //   $('#chatAudio')[0].play();
          // }
          
        }
    });  
  }
  $('body').on('click', '.notification-click', function(e){
      $.ajax({
          url: "{{url('godown/mark-read-notifications')}}",
          method: "POST",
          data: {_token: '{{ csrf_token() }}'},
          dataType: "json",
          success: function (response) {
            $("#new_notification").html(response.new_notification);
            $("#notification_today").html(response.notification_today);
          }
      });   
  });
});
</script>
</body>
</html>
