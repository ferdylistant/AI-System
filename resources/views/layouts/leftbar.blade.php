<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="{{ url('/') }}">
            <img src="{{ url('images/logo.png') }}" alt="logo" width="7%" style="margin-top: -5px;">
            AI System
        </a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ url('/') }}">
            <img src="{{ url('images/logo.png') }}" alt="logo" class="shadow-light rounded-circle">
        </a>
    </div>

    <ul class="sidebar-menu">
        @foreach (json_decode(Redis::connection()->get('menus')) as $key => $menus)
            @php
                $keyChar = Str::contains($key, '&');
                $keyDisplay = $key;
            @endphp
            @if ($keyChar)
                @php
                    $key = Str::remove('&', $key);
                @endphp
            @endif
            <li class="menu-header">
                {{ $keyDisplay }}
                {{-- <span data-toggle="collapse" href="#{{Str::camel($key)}}" role="button" aria-expanded="false" aria-controls="{{Str::camel($key)}}">
                <span class="bd-highlight">{{$keyDisplay}}</span>
                <i class="fas fa-sort-down bd-highlight"></i>
            </span> --}}
            </li>
            {{-- <div class="collapse show" style="" id="{{ Str::camel($key) }}"> --}}
                @foreach ($menus as $menu)
                    @if ($menu->detail->url == '#')
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link has-dropdown">
                                <i class="{{ $menu->detail->icon }}"></i><span>{{ $menu->detail->name }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                @foreach ($menu->child as $child)
                                    <li class="{{ $child->url == request()->path() ? 'active' : '' }}"><a class="nav-link"
                                            href="{{ url($child->url) }}">{{ $child->name }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="{{ $menu->detail->url == request()->path() ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url($menu->detail->url) }}">
                                <i class="{{ $menu->detail->icon }}"></i>
                                <span>{{ $menu->detail->name }}</span>
                            </a>
                        </li>
                    @endif
                @endforeach
            {{-- </div> --}}
        @endforeach
    </ul>
</aside>
