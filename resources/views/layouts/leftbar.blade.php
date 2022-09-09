<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="{{url('/')}}">
            <img src="{{url('images/logo.png')}}" alt="logo" width="7%" style="margin-top: -5px;">
            AI System
        </a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{url('/')}}">
            <img src="{{url('images/logo.png')}}" alt="logo" class="shadow-light rounded-circle">
        </a>
    </div>

    <ul class="sidebar-menu pb-5">
        @foreach(session('menus') as $key => $menus)
        <li class="menu-header">{{$key}}</li>
            @foreach($menus as $menu)
                @if($menu['detail']['url'] == '#')
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="{{$menu['detail']['icon']}}"></i><span>{{$menu['detail']['name']}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @foreach($menu['child'] as $child)
                        <li><a class="nav-link" href="{{url($child['url'])}}">{{$child['name']}}</a></li>
                        @endforeach
                    </ul>
                </li>
                @else
                <li class="{{$menu['detail']['url']==request()->path()?'active':''}}">
                    <a class="nav-link" href="{{url($menu['detail']['url'])}}">
                        <i class="{{$menu['detail']['icon']}}"></i>
                        <span>{{$menu['detail']['name']}}</span>
                    </a>
                </li>
                @endif
            @endforeach
        @endforeach
    </ul>
</aside>
