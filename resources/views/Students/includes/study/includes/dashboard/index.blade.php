<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        @if($shortcuts)
        @foreach ($shortcuts as $shortcut)
        <div class="card">
            @if($shortcut['title'])
            <div class="card-header">
                <h4 class="title">
                    {{$shortcut['title']}}
                </h4>
            </div>
            @endif
            <div class="card-body">

                <div class="row shortcuts">
                    @foreach ($shortcut['children'] as $item)
                    <a data-loadscript='["{{asset("/assets/vendor/list.js/dist/list.min.js")}}","{{asset("/assets/vendor/pagination/simplePagination.js")}}"]'
                        data-toggle="shortcut-icon" href="{{$item['link']}}" class="col-lg-2 col-6 shortcut-item"
                        {{isset($item['target']) ? 'target='.$item['target'] : ""}}>
                        <span class="shortcut-media avatar avatar-xl rounded mb-3 {{$item['color']}}">
                            @if ($item['icon'])
                            <i class="fa-2x {{$item['icon']}}"></i>
                            @else
                            <span class="w-100 text-xs">{{$item['text']}}</span>
                            @endif
                        </span>
                        <h4 class="text-sm">{{$item['name']}}</h4>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
        @endif

    </div>
</div>
