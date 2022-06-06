<?php 
echo $type;
if (Auth::check()) {
    if ($type == "delivery" && count(session('cart')) > 0) {
        $user_location_change_cond = 1;
    }
    else{
        $user_location_change_cond = 0;
    }
}
?>
@if($user_location_change_cond == 1)
@else
<div class="">
    <form class="form-inline">
        <label class="radio radio-inline" style="color: black;">
            <input type="radio" value="delivery_selected" id="select_delivery_delivery" name="select_delivery_delivery"
            <?php if ($type == "" || $id == "") { ?>
                checked
            <?php } else { ?>
                <?php if ($type == "delivery") { ?>
                    checked
                <?php } ?>
            <?php } ?> >
            <i class="input-helper"></i>
            Delivery
        </label>
        <label class="radio radio-inline" style="color: black;">
            
            <input type="radio" value="takeaway_selected" @if($user_location_change_cond==1) class="select_delivery_takeaway" @else id="select_delivery_takeaway" data-toggle="modal" data-target="#delivery_pickup_modal" @endif name="select_delivery_delivery" class="prevent_formsubmit" 
                <?php if ($type == "pickup") { ?>
                    checked
                <?php } ?> >

            <i class="input-helper"></i>
            Takeaway
        </label>
    </form>
    <br>
    @if($user_location_change_cond==0)
        You can change your order from Takeaway to Delivery 
    @endif
</div>
@endif
<div>
    <h5>@if($user_location_change_cond == 1) @else Takeaway @endif </h5>
</div>
<!-- Modal -->
<div class="modal fade" id="delivery_pickup_modal" role="dialog">
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-body">
        <section class="login-page section-b-space my-5">
            <div class="container">
                <div class="row" id="loginModal_content">
                    <div class="col-lg-12">
                        <span class="online">Buy From</span> 
                        <span class="ob"> Orange Bakery</span>
                        <div class="theme-card">
                            <label class="radio radio-inline">
                                <input type="radio" name="delivery_type" id="delivery_type_delivery" class="delivery_type" value="delivery">
                                <i class="input-helper"></i>
                                Delivery
                            </label>
                            <label class="radio radio-inline">
                                <input type="radio" id="delivery_type_takeaway" class="delivery_type" value="pick_up" name="delivery_type">
                                <i class="input-helper"></i>
                                Takeaway
                            </label>
                            <div id="show_delivery">
                                <br>
                                 <div class="form-group">
                                    <br>
                                    <form method="post" action="{{url('setCookie')}}">
                                        @csrf
                                    <!-- <input type="hidden" value="delivery" name="type"> -->
                                    <!-- <button class="btn cart-btn" id="proceed">PROCEED</button> -->
                                    </form>
                                </div>
                            </div>
                            <div id="show_pick_up">
                                <br>
                                <span>Which store would you like to Takeaway order from</span>
                                <div class="form-group">
                                    <br>
                                    <select id="location" name="location" class="form-control dynamic">
                                        @foreach($location as $row)
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select id="store" name="store" class="form-control dynamic">
                                        <option value="" selected="disabled">Select Store</option>
                                    </select>
                                </div>
                                
                            </div>
                            <div class="spinimg">
                                <img alt="Spin" src="{{asset('images/spin.gif')}}" style="width:60px;height:60px;">
                            </div>
                            <div id="getstorelocation">
                                
                            </div>
                            {{ csrf_field() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>