@extends('admin.layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card">
              <div class="card-header">
                <h3 class="card-title">Customize Colors</h3>
                 <a href="{{ route('admin.customize-colours.create') }}" class="btn btn-primary float-right">Create</a> 
                
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th>Id</th>
                      <th>Color</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($colors as $row)
                    <tr>
                     <td>{{ @$row->id }}</td>
                      <td><input type="color" id="head" name="head" value="{{ @$row->color }}"></td>
                      <td>
                        <button form="resource-delete-{{ $row->id }}"><i style="color: red;" class="fas fa-trash-alt"></i></button>
                        
                         <form action="{{url('admin/customize-colours/delete')}}?id={{$row->id}}" id="resource-delete-{{ $row->id }}" style="display: inline-block;" onSubmit="return confirm('Are you sure you want to delete this item?');" method="post">
                      @csrf
                      </form>

                      </td>
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
