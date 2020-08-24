<ul class="folder-list nav nav-sm tree flex-column">
    @foreach ($directories as $directory)

    <li class="nav-item">
        <a href="{{$directory['link']}}" class="{{!$directory['sub_directories']??'collapsed'}} text-truncate nav-link
            {{request()->is($active.'/'.$directory['name'].'*')?'active text-blue':''}}" title="{{$directory['name']}}"
            role="button" @if($directory['sub_directories'] &&
            array_keys(array_column($directory['sub_directories'],'type'),'directory')) data-toggle="collapse"
            data-target="#{{str_replace('/','-',$active)}}-{{$directory['name']}}" aria-expanded="false" @endif>
            <i class="{{$directory['icon_class']}}"></i>
            {{ $directory['name']}}
        </a>
        @if ($directory['sub_directories'] &&
        array_keys(array_column($directory['sub_directories'],'type'),'directory'))
        <div class="collapse {{request()->is($active.'/'.$directory['name'].'*')?'show':''}}"
            id="{{str_replace('/','-',$active)}}-{{$directory['name']}}" style="">
            @include('FileManager.includes.subtreeview',[
            'active' => $active.'/'.$directory['name'],
            'directories' => $directory['sub_directories']
            ])
        </div>
        @endif

    </li>
    @endforeach
</ul>
