@php
    $admin  = \Auth::user();
@endphp
<nav class="page-sidebar" id="sidebar">
    <div id="sidebar-collapse">
        <div class="admin-block d-flex">
            <div>
                <img src="{{asset('assets_admin/img/admin-avatar.png')}}" width="45px" />
            </div>
            <div class="admin-info">
                <div class="font-strong">{{ $admin->name }}</div><small>Administrator</small>
            </div>
        </div>
        <ul class="side-menu metismenu">
            <li>
                <a href="{{ route('admin.dashboard') }}"><i class="sidebar-item-icon fa fa-th-large"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>
            <!-- <li class="heading">FEATURES</li> -->
            <!-- <li class="active">
                <a href="javascript:;"><i class="sidebar-item-icon fa fa-bookmark"></i>
                    <span class="nav-label">Users</span><i class="fa fa-angle-left arrow"></i></a>
                <ul class="nav-2-level collapse in">
                    <li>
                        <a class="active" href="#">All Users</a>
                    </li>
                    <li>
                        <a href="#">Add New</a>
                    </li>                    
                </ul>
            </li> -->
            
            <li>
                <a href="{{ route('admin.users') }}"><i class="sidebar-item-icon fa fa-users"></i>
                    <span class="nav-label">Users</span>
                </a>
            </li>
           
        </ul>
    </div>
</nav>