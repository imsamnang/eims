<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <div class="sidenav-header d-flex align-items-center">
            <div class="container">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text p-1"><i class="fas fa-search"></i></div>
                    </div>
                    <input type="search" class="form-control form-control-sm" id="filter"
                        data-target="#file-manager-dir" data-filter="#dir-items" data-showhide="#dir-main"
                        data-result="#file-manager-dir-result" placeholder="{{ __('Search') }}">
                </div>
            </div>
        </div>
        <div class="navbar-inner">
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <ul class="navbar-nav tree mb-md-3" id="file-manager-dir">
                    @foreach ($items as $item)
                        <li class="nav-item" data-toggle="gallery" id="dir-main">
                            @if ($item['sub_directories'])
                                <a href="{{ $item['link'] }}"
                                    class="collapsed text-truncate nav-link
                                    {{ request()->is('file-manager/directory/' . $item['name'] . '/*') ? 'active text-blue' : '' }}"
                                    title="{{ $item['name'] }}" role="button" data-toggle="collapse"
                                    data-target="#{{ $item['name'] }}" aria-expanded="false">
                                    <i class="{{ $item['icon_class'] }}"></i>
                                    <span class="nav-link-text" id="dir-items">{{ $item['name'] }}</span>
                                </a>
                                <div class="collapse {{ request()->is('file-manager/directory/' . $item['name'] . '/*') ? 'show' : '' }}"
                                    id="{{ $item['name'] }}">
                                    @include('FileManager.includes.subtreeview',[
                                    'active' => 'file-manager/directory/' .$item['name'],
                                    'items' => $item['sub_directories']
                                    ])
                                </div>
                            @elseif($item['type'] == 'image')
                            @else
                                <a href="{{ $item['link'] }}"
                                    class="text-truncate nav-link {{ request()->is('file-manager/directory/' . $item['name'] . '/*') ? 'active text-blue' : '' }}"
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
