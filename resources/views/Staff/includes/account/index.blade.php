<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            @include(config("pages.parent").".includes.account.includes.body")
            @if (!request()->ajax())
            <div class="card-footer">
                <a href="{{url(config("pages.host").config("pages.path").config("pages.pathview")."list")}}"
                    class="btn btn-default" type="button">{{ __("Back") }}</a>
            </div>
            @endif

        </div>
    </div>
</div>
