@extends('admin.layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card">
              <div class="card-header">
                <h3 class="card-title">Testimonial Image Sliders</h3>
                <a href="{{route('admin.testimonials.create')}}" class="btn btn-primary float-right">Create</a>
                <a  href="{{ route('admin.excel') }}" class="btn btn-primary float-right">Export Testimonial Data</a>
            
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th>Image</th>
                      <th>Title</th>
                      <th>Description</th>
                      <th>Show in Website</th>
                      <th>Show in Mobile</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($banners as $row)
                    <tr>
                      <td><img src="{{ URL::to('/') }}/uploads/testimonials/{{ @$row->image }}" style="width: 40%;" /></td>
                      <td>{{@$row->title}}</td>
                      <td>{{@$row->description}}</td>
                      <td>
                        @if($row->show_in_website_home == "yes")
                          Yes
                        @else
                          No
                        @endif
                      </td>
                      <td>
                        @if($row->show_in_app_home == "yes")
                          Yes
                        @else
                          No
                        @endif
                      </td>
                      <td>
                        <a href="{{ route('admin.testimonials.edit', $row->id) }}" class="btn"><i class="fas fa-edit" style="color: blue" ></i></a>
                        <button form="resource-delete-{{ $row->id }}"><i style="color: red;" class="fas fa-trash-alt"></i></button>
                        <form id="resource-delete-{{ $row->id }}" action="{{url('admin/testimonials/delete-testimonials')}}?id={{$row->id}}" style="display: inline-block;" onSubmit="return confirm('Are you sure you want to delete this item?');" method="post">
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
