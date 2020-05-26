@if (Auth::user()->role_id == 6)
<div class="row">
    <div class="col-xl-8">
        <div class="row pt-4 mb-3 rounded bg-gradient-{{config('app.theme_color.name')}}">
            <div class="col-xl-6">

            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card widget-calendar">
            <div class="card-header">
                <div class="h5 text-muted mb-1 widget-calendar-year"></div>
                <div class="h3 mb-0 widget-calendar-day"></div>
            </div>
            <div class="card-body">
                <div data-toggle="widget-calendar" data-event-url="{{URL::to("/holiday/calendar")}}"></div>
            </div>
        </div>

    </div>

</div>
@else
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                <div class="row shortcuts">

                    @foreach ($shortcut as $item)
                    <a data-loadscript='["{{asset("/assets/vendor/list.js/dist/list.min.js")}}","{{asset("/assets/vendor/pagination/simplePagination.js")}}"]' data-toggle="shortcut-icon" href="{{$item['link']}}" class="col-lg-2 col-6 shortcut-item">
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
    </div>
</div>
@endif
