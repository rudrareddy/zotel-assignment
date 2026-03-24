<!-- ========== Left Sidebar Start ========== -->
<?php
use App\Models\RoomType;
 $rooms = RoomType::select('id','name','slug')->get();
?>
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu">Menu</li>
               
                
                <li>
                    <a href="{{url('admin/rate-plans')}}">
                        <i data-feather="package"></i>
                        <span data-key="t-dashboard">Rate Plans</span>
                    </a>
                </li>   
                <li>
                    <a href="{{url('admin/room-types')}}">
                        <i data-feather="home"></i>
                        <span data-key="t-dashboard">Room Types</span>
                    </a>
                </li> 
                <li>
                    <a href="{{url('admin/discounts')}}">
                        <i data-feather="gift"></i>
                        <span data-key="t-dashboard">Discoun Rules</span>
                    </a>
                </li>  
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="package"></i>
                        <span>Inventory</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        @foreach($rooms as $room)
                        <li><a href="{{url('admin/inventory/'.$room->slug)}}"><span>{{$room->name}}</span></a></li>
                        @endforeach
                        
                    </ul>
                </li>
               
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
