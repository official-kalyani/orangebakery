<div id="notifications">
<?php 
  $notifications =  \App\Models\Notification::latest()->get();
  $new_notification =  \App\Models\Notification::where('status','unread')->latest()->get();
  $notification_today =  \App\Models\Notification::whereDate('created_at', \Carbon\Carbon::today())->get();
?>

<li class="nav-item dropdown">
  <a class="nav-link notification-click" data-toggle="dropdown" href="#" aria-expanded="true">
    <i class="far fa-bell"></i>
    <span class="badge badge-warning navbar-badge"><span id="new_notification">{{count($new_notification)}}</span></span>
  </a>
  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
    <span class="dropdown-item dropdown-header"><span id="notification_today">{{count($notification_today)}}</span> Orders Today So Far</span>
    @foreach($notifications as $notification)
      <div class="dropdown-divider"></div>
      <a href="{{url('/godown/order-details')}}?order_id={{$notification->order_id}}" class="dropdown-item">
        <i class="fas fa-envelope mr-2" @if($notification->action_type != NULL) @else style="color: red;" @endif></i> 1 new orders
        <span class="float-right text-muted text-sm">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
      </a>
    @endforeach
   <!--  <div class="dropdown-divider"></div>
    <a href="{{url('/godown/manage-orders')}}" class="dropdown-item dropdown-footer">See All Orders</a> -->
  </div>
</li>
</div>
