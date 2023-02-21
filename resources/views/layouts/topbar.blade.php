<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i
                        class="fas fa-search"></i></a></li>
        </ul>
        <div id="appSearch">
        </div>
        <div id="hits"></div>
        <div id="pagination"></div>



        {{-- <div class="search-element">
            <input class="form-control" type="search" placeholder="Search" aria-label="Search" data-width="250">
            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
        </div> --}}
    </form>

    <ul class="navbar-nav navbar-right">
        <li id="containerNotf" class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg"><i
                    class="far fa-bell"></i></a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
                <div class="dropdown-header">Notifications</div>
                <div class="dropdown-list-content dropdown-list-icons">

                </div>
                <div class="dropdown-footer text-center">
                    <a href="{{ route('notification.view_all') }}">View All <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </li>

        <li class="dropdown">
            @php
                $url = url()->current();
                $split = Str::afterLast($url, '/');
                $class = '';
                if (auth()->user()->id == $split) {
                    $class = 'image-output';
                }
            @endphp
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image"
                    src="{{ url('storage/users/' . auth()->user()->id . '/' . auth()->user()->avatar) }}"
                    class="rounded-circle mr-1 {{ $class }}">
                <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->nama }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                @php
                    $log = DB::table('user_log')
                        ->where('users_id', auth()->user()->id)
                        ->orderBy('last_login', 'desc')
                        ->first();
                @endphp
                <div class="dropdown-title">Telah masuk
                    {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $log->last_login)->diffForHumans() }}</div>
                <a href="{{ url('manajemen-web/user/' . auth()->id()) }}" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> Profile
                </a>
                @if (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b')
                    <a href="{{ url('setting') }}" class="dropdown-item has-icon">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                @endif
                <div class="dropdown-divider"></div>

                <a href="javascript:void(0)" class="dropdown-item has-icon text-danger" id="logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none">
                    @csrf</form>
            </div>
        </li>
    </ul>
</nav>
