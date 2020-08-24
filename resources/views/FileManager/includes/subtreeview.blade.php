<ul class="folder-list nav tree flex-column">
    @foreach ($items as $item)
        <li class="nav-item" id="dir-main">
            @if ($item['sub_directories'] && array_keys(array_column($item['sub_directories'], 'type'), 'directory'))
                <a href="{{ $item['link'] }}" class="text-truncate nav-link {{ !$item['sub_directories'] ?? 'collapsed' }}
                    {{ request()->is($active . '/' . $item['name'] . '/*') ? 'active text-blue' : '' }}"
                    title="{{ $item['name'] }}" role="button" data-toggle="collapse"
                    data-target="#{{ str_replace('/', '-', $active) }}-{{ $item['name'] }}" aria-expanded="false">
                    <i class="{{ $item['icon_class'] }}"></i>
                    <span id="dir-items">{{ $item['name'] }}</span>
                </a>
                <div class="collapse {{ request()->is($active . '/' . $item['name'] . '/*') ? 'show' : '' }}"
                    id="{{ str_replace('/', '-', $active) }}-{{ $item['name'] }}" style="">
                    @include('FileManager.includes.subtreeview',[
                    'active' => $active.'/'.$item['name'],
                    'items' => $item['sub_directories']
                    ])
                </div>
            @elseif($item['type'] == 'image')
            @else
                <a href="{{ $item['link'] }}"
                    class="text-truncate nav-link {{ request()->is($active . '/' . $item['name'] . '*') ? 'active text-blue' : '' }}"
                    title="{{ $item['name'] }}" role="button">
                    <i class="{{ $item['icon_class'] }}"></i>
                    <span id="dir-items">{{ $item['name'] }}</span>
                </a>
            @endif
        </li>
    @endforeach
</ul>
