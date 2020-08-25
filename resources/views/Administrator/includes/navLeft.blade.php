<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ config('app.logo') }}" class="navbar-brand-img" alt="...">
            </a>
            <div class="ml-auto">
                <!-- Sidenav toggler -->
                <div class="sidenav-toggler d-none d-xl-block active" data-action="sidenav-unpin"
                    data-target="#sidenav-main">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-inner">
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a data-toggle="navbar" data-loadscript='["{{ asset('/assets/vendor/fullcalendar/dist/fullcalendar.min.js') }}",
                            "{{ asset('/assets/vendor/weather/dist/skycons.js') }}"]'
                            data-loadstyle='["{{ asset('/assets/vendor/fullcalendar/dist/fullcalendar.min.css') }}",
                            "{{ asset('/assets/vendor/weather/dist/weather.css') }}","{{ asset('/assets/vendor/weather/css/weather-icons.min.css') }}"]'
                            class="nav-link {{ request()->segment(2) == null || request()->segment(2) == 'dashboard' ? 'active text-blue' : '' }}"
                            href="{{ URL::to(config('pages.host') . config('pages.path') . '/dashboard') }}">
                            <i class="fas fa-tv"></i>
                            <span class="nav-link-text">{{ __('Dashboard') }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->segment(2) == 'feed' ? 'active text-blue' : '' }}"
                            href="{{ URL::to(config('pages.host') . config('pages.path') . '/feed') }}">
                            <i class="fas fa-bullhorn"></i>
                            <span class="nav-link-text">{{ __('News & Even') }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a data-toggle="navbar"
                            class="nav-link {{ request()->segment(2) == 'staff' ? 'active text-blue' : '' }}"
                            href="{{ URL::to(config('pages.host') . config('pages.path') . '/staff') }}">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <span class="nav-link-text">{{ __('Staff & Teacher') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a data-toggle="navbar"
                            class="nav-link {{ request()->segment(2) == 'students' ? 'active text-blue' : '' }}"
                            href="{{ URL::to(config('pages.host') . config('pages.path') . '/students') }}">
                            <i class="fas fa-user-graduate"></i>
                            <span class="nav-link-text">{{ __('Students') }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a data-toggle="navbar"
                            class="nav-link {{ request()->segment(2) == 'quizzes' ? 'active text-blue' : '' }}"
                            href="{{ URL::to(config('pages.host') . config('pages.path') . '/quizzes') }}">
                            <i class="fas fa-question-circle"></i>
                            <span class="nav-link-text">{{ __('Quiz') }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a data-toggle="navbar"
                            class="nav-link {{ request()->segment(2) == 'study' ? 'active text-blue' : '' }}"
                            href="{{ URL::to(config('pages.host') . config('pages.path') . '/study') }}">
                            <i class="fas fa-graduation-cap"></i>
                            <span class="nav-link-text">{{ __('Study') }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a data-toggle="navbar"
                            class="nav-link {{ request()->segment(2) == 'general' ? 'active text-blue' : '' }}"
                            href="{{ URL::to(config('pages.host') . config('pages.path') . '/general') }}">
                            <i class="fas fa-list-alt"></i>
                            <span class="nav-link-text">{{ __('General') }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a data-toggle="navbar"
                            class="nav-link"
                            target="_blank"
                            href="{{ route('filemanager') }}">
                            <i class="fas fa-folders text-yellow"></i>
                            <span class="nav-link-text">{{ __('File Manager') }}</span>
                            <span class="badge badge-danger">{{__('New')}}</span>
                        </a>
                    </li>
                </ul>
                <hr class="my-3">
                <ul class="navbar-nav mb-md-3">
                    <li class="nav-item">
                        <a data-toggle="navbar"
                            data-loadscript='["{{ asset('/assets/vendor/list.js/dist/list.min.js') }}","{{ asset('/assets/vendor/pagination/simplePagination.js') }}"]'
                            class="nav-link {{ request()->segment(2) == 'users' ? 'active text-blue' : '' }}"
                            href="{{ URL::to(config('pages.host') . config('pages.path') . '/users/list') }}">
                            <i class="fas fa-users"></i>
                            <span class="nav-link-text">{{ __('Users') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a data-toggle="navbar"
                            class="nav-link {{ request()->segment(2) == 'settings' ? 'active text-blue' : '' }}"
                            href="{{ URL::to(config('pages.host') . config('pages.path') . '/settings') }}">
                            <i class="fas fa-cogs"></i>
                            <span class="nav-link-text">{{ __('Settings') }}</span>
                        </a>
                    </li>
                    @if (env('TELESCOPE_ENABLED'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->segment(2) == 'telescope' ? 'active text-blue' : '' }}"
                                href="{{ URL::to('/telescope') }}" target="_blank">
                                <i class="fas fa-search"></i>
                                <span class="nav-link-text">{{ __('Telescope') }}</span>
                            </a>
                        </li>
                    @endif

                </ul>
                <hr class="my-3">
                <ul class="navbar-nav mb-md-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->segment(2) == 'profile' ? 'active text-blue' : '' }}"
                            href="{{ URL::to(config('pages.host') . config('pages.path') . '/profile') }}"
                            data-toggle="collapse" data-target="#navbar-profile" profile="button"
                            aria-expanded="{{ request()->segment(2) == 'profile' ? 'true' : 'false' }}"
                            aria-controls="navbar-tables">
                            <i class="fas fa-user"></i>
                            <span class="nav-link-text">{{ __('Profile') }} </span>
                        </a>

                        <div class="collapse {{ request()->segment(2) == 'profile' ? 'show' : '' }}"
                            id="navbar-profile">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ URL::to(config('pages.host') . config('pages.path') . '/profile/general') }}"
                                        class="nav-link  {{ request()->segment(2) == 'profile' ? (request()->segment(3) == 'general' || request()->segment(3) == null ? 'active text-blue' : '') : '' }}">
                                        {{ __('General') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ URL::to(config('pages.host') . config('pages.path') . '/profile/password') }}"
                                        class="nav-link  {{ request()->segment(2) == 'profile' ? (request()->segment(3) == 'password' ? 'active text-blue' : '') : '' }}">
                                        {{ __('Password') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
