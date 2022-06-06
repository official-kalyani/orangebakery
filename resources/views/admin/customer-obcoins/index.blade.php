@extends('admin.layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card">
              <div class="card-header">
                <h3 class="card-title">Customer Cbcoins</h3>
              </div>
              @if(Session::has('flash_success'))
                  <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                  {{ Session::get('flash_success') }}
                  </div>
              @endif
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                <thead>                  
                  <tr>
                    <th>User</th>
                    <th>Coins</th>
                    <th>Coin Received On Purchase</th>
                    <th>Status</th>
                    <th>Coin Received On</th>
                    <th>Coin Expiry Date On</th>
                  </tr>
                </thead> 
                  <tbody>
                  @foreach($coins as $coin)
                    <?php 
                        $order = \App\Models\Order::where('order_id',$coin->order_id)->first();
                        if (isset($order)) {
                            $order_price = $order->order_price;
                        }
                        else{
                            $order_price = "SIGNUP BONOUS";
                        }
                        $user = App\User::where('id',$coin->user_id)->first();
                    ?>
                          <td>{{@$user->name}}</td>
                          <td><span style="color: green; font-size: 30px;">{{$coin->coins}}</span> @if($coin->type == "MINUS") <span style="color: red; font-size: 30px;">(-)</span> @else <span style="color: blue; font-size: 30px;">(+)</span> @endif</td>
                          <td>
                                @if($order_price == "SIGNUP BONOUS")
                                    {{$order_price}}
                                @else
                                    {{$coin->order_id}}
                                @endif
                          <td>
                            @if($coin->status == 1)
                              ACTIVE
                            @elseif($coin->status == 2)
                              USED
                                @else
                                    EXPIRED
                            @endif
                          </td>
                          <td>{{ \Carbon\Carbon::parse($coin->created_at)->format('jS M Y') }}</td>
                          <td>{{ \Carbon\Carbon::parse($coin->expiry_at)->format('jS M Y') }}</td>
                      </tr>
                   </tbody>
                 @endforeach
                </tbody>
              </table>
                {!! $coins->links() !!}
              </div>
              <!-- /.card-body -->
              
        </div>
        <!-- /.card -->
        </div>
       </div>
      </div><!-- /.container-fluid -->
    </section>
@endsection
