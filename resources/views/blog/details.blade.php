@extends('layouts.blog')
@section('content')
<?php
$blog->description = preg_replace("/^<p.*?>/", "",$blog->description);
$blog->description = preg_replace("|</p>$|", "",$blog->description);
?>

  <!-- Page Content -->
  <div class="container">

    <div class="row">

      <!-- Post Content Column -->
      <div class="col-lg-9 col-md-9 col-sm-12">

        <!-- Title -->
        <h1 class="mt-4">{{@$blog->title}}</h1>

        <p><?php echo \Carbon\Carbon::parse(@$blog->created_at)->format('jS M Y'); ?></p>

        <!-- <hr> -->

        <!-- Preview Image -->
        <img src="{{ URL::to('/') }}/uploads/blogs/{{ @$blog->image }}" class="img-responsive" />

        <!-- <hr> -->

        <!-- Post Content -->
        <div class="lead">{{@$blog->description}}</div>

    </div>
      </div>

      <!-- Sidebar Widgets Column -->
      <div class="col-lg-3 col-md-3 sol-sm-3 sidenav">

      </div>

    </div>
    <!-- /.row -->

  </div>
  <!-- /.container -->

</body>

</html>
@endsection