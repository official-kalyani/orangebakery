@extends('admin.layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card">
              <div class="card-header">
                <h3 class="card-title">Home Page Image Sliders</h3>
                <a href="{{route('admin.sliders.create')}}" class="btn btn-primary float-right">Create</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th>Image Name</th>
                      <th>Image</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($banners as $row)
                    <tr>
                      <td>{{@$row->imagename}}</td>
                      <td><img src="{{ URL::to('/') }}/uploads/sliders/{{ @$row->image }}" style="width: 40%;" /></td>
                      <td>
                        <a href="{{ route('admin.sliders.edit', $row->id) }}" class="btn"><i class="fas fa-edit" style="color: blue" ></i></a>
                        <button form="resource-delete-{{ $row->id }}"><i style="color: red;" class="fas fa-trash-alt"></i></button>
                        <form id="resource-delete-{{ $row->id }}" action="{{url('admin/sliders/delete-sliders')}}?id={{$row->id}}" style="display: inline-block;" onSubmit="return confirm('Are you sure you want to delete this item?');" method="post">
                          @csrf
                        </form></td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
        </div>
        <!-- /.card -->
        </div>
       </div>
      </div><!-- /.container-fluid -->
    </section>
@endsection
