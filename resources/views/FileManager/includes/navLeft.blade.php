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
                <ul class="navbar-nav tree mb-md-3" id="filemanager-dir">
                    @foreach ($items as $item)
                        <li class="nav-item" data-toggle="gallery" id="dir-main">
                            @if ($item['sub_directories'])
                                <a href="{{ $item['link'] }}"
                                    class="collapsed text-truncate nav-link
                                    {{ request()->is('filemanager/directory/' . $item['name'] . '/*') ? 'active text-blue' : '' }}"
                                    title="{{ $item['name'] }}" role="button" data-toggle="collapse"
                                    data-target="#{{ $item['name'] }}" aria-expanded="false">
                                    <i class="{{ $item['icon_class'] }}"></i>
                                    <span class="nav-link-text" id="dir-items">{{ $item['name'] }}</span>
                                </a>
                                <div class="collapse {{ request()->is('filemanager/directory/' . $item['name'] . '/*') ? 'show' : '' }}"
                                    id="{{ $item['name'] }}">
                                    @include('FileManager.includes.subtreeview',[
                                    'active' => 'filemanager/directory/' .$item['name'],
                                    'items' => $item['sub_directories']
                                    ])
                                </div>
                            @elseif($item['type'] == 'image')
                            @else
                                <a href="{{ $item['link'] }}" id="show-items" data-items='{!! json_encode(@$item['sub_directories'])!!}'
                                    class="text-truncate nav-link {{ request()->is('filemanager/directory/' . $item['name'] . '/*') ? 'active text-blue' : '' }}"
                                    title="{{ $item['name'] }}" role="button">
                                    <i class="{{ $item['icon_class'] }}"></i>
                                    <span class="nav-link-text" id="dir-items">{{ $item['name'] }}</span>
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</nav>
