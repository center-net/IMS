@auth
    <div class="dropdown">
        <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi-person-circle"></i>
            {{ auth()->user()->name ?? auth()->user()->username }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
            <li>
                <a class="dropdown-item" href="#">
                    <i class="bi-person"></i> {{ __('auth.user_menu_profile') }}
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="bi-box-arrow-right"></i> {{ __('auth.logout') }}
                    </button>
                </form>
            </li>
        </ul>
    </div>
@else
    <a class="btn btn-outline-light btn-sm" href="{{ route('login') }}">
        <i class="bi-door-open"></i> {{ __('auth.login_title') }}
    </a>
@endauth

