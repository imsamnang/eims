<div class="row">
    <div class="col">
        <div class="card">
            <div class="accordion" id="accordion">
                @foreach ($response['data'] as $key => $row)
                <div class="card">
                    <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapse-{{$row->id}}" aria-expanded="true"
                        aria-controls="collapse-{{$row->id}}">
                    <h3 class="mb-0">{{$row->name}}</h3>
                    </div>
                    <div id="collapse-{{$row->id}}" class="collapse {{$key == 0 ? 'show' :''}}" aria-labelledby="heading-{{$row->id}}" data-parent="#accordion">
                        @include(config("pages.parent").".includes.view.includes.body",['routines'=>$row->routines , 'days' => $days['data']])
                    </div>
                </div>
                @endforeach
            </div>

            @if (!request()->ajax())
            <div class="card-footer">
                <a href="{{url(config("pages.host").config("pages.path").config("pages.pathview")."list")}}"
                    class="btn btn-default" type="button">
                    {{ __("Back") }}
                </a>
            </div>
            @endif

        </div>
    </div>
</div>
