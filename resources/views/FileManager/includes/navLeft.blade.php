<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <div class="sidenav-header d-flex align-items-center">
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
                <ul class="navbar-nav tree mb-md-3">
                    @foreach ($directories as $directory)
                    <li class="nav-item">
                        <a href="{{$directory['link']}}" class="{{!$directory['sub_directories']??'collapsed'}} text-truncate nav-link
                        {{request()->is('file-manager/directory/' .$directory['name'].'*')?'active text-blue':''}}"
                            title="{{$directory['name']}}" role="button" @if($directory['sub_directories'])
                            data-toggle="collapse" data-target="#{{$directory['name']}}" aria-expanded="false" @endif>
                            <i class="{{$directory['icon_class']}}"></i>
                            <span class="nav-link-text">{{ $directory['name']}}</span>
                        </a>

                        @if($directory['sub_directories'])
                        <div class="collapse {{request()->is('file-manager/directory/' .$directory['name'].'*')?'show':''}}"
                            id="{{$directory['name']}}" style="">
                            @include('FileManager.includes.subtreeview',[
                            'active' => 'file-manager/directory/' .$directory['name'],
                            'directories' => $directory['sub_directories']
                            ])
                        </div>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</nav>
