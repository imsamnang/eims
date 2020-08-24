<div class="card m-0">
    <div class="card-header">
        <label class="btn btn-outline-primary m-0" for="foreground_card-{{$row['id']}}">
            <img width="25px" src="{{config("pages.form.data.0.foreground")}}" alt="">
            {{__("Frame foreground")}}
        </label>
        <input hidden type="file" name="foreground_card" id="foreground_card-{{$row['id']}}">

        <button id="layout" class="btn btn-outline-primary">
            <i class="fas fa-columns"></i>
            {{__("Layout")}}
        </button>
        <div class="dropdown" data-close="false">
            <a class="btn btn-outline-primary " href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <i class="fad fa-cog"></i>
                <span class="caret"></span>
            </a>
            <div class="p-2 dropdown-menu dropdown-menu-lg dropdown-menu-right dropdown-menu-arrow">
                @foreach ($all as $key => $item)
                <div class="custom-control custom-checkbox">
                    <input {{array_key_exists($key,$selected) ? "checked" : "" }} value="{{$key}}" type="checkbox"
                        class="custom-control-input card-value-check" id="customCheck-{{$row['id']}}-{{$key}}">
                    <label class="custom-control-label" for="customCheck-{{$row['id']}}-{{$key}}">{{$item}}</label>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card-body p-0 bg-translucent-dark">
        <style>
            [id^="stage"] {
                margin: auto;
                background: #fff;
            }

            [id^="stage"] .konvajs-content {
                margin: auto;
            }
        </style>

        <div class="col-12">
            <div id="stage" data-toggle="certificate-maker" data-layout="{{$frame["layout"]}}"
                data-foreground="{{$frame["foreground"]}}" data-background="{{$frame["background"]}}"
                data-user='{!! json_encode($row)!!}'></div>
        </div>
    </div>
</div>
