<div class="row">
    <div class="col-xl-12">
        <div class="card-wrapper">
            <form role="{{config("pages.form.role")}}" class="needs-validation" novalidate="" method="POST"
                action="{{config("pages.form.action.detect")}}"
                id="form-{{config("pages.form.name")}}" enctype="multipart/form-data"
                data-validate="{{json_encode(config('pages.form.validate'))}}">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h3 mb-0">
                            {{ __(config("pages.form.role")) }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                @csrf
                                @if(config('pages.form.data'))
                                <input type="hidden" name="id" value="{{config('pages.form.data.id')}}">
                                @endif
                                @include(config("pages.parent").".includes.form.includes.a")
                                @include(config("pages.parent").".includes.form.includes.b")
                            </div>
                        </div>
                    </div>


                    <div class="card-footer">
                        @if (!request()->ajax())
                        <a href="{{url(config("pages.host").config("pages.path").config("pages.pathview")."list")}}"
                            class="btn btn-default" type="button">{{ __("Back") }}</a>
                        @endif


                        <button href="{{config("pages.form.action.add")}}" class="btn btn-primary ml-auto pull-right"
                            data-for="save" id="btn-save" name="btn-save" type="submit">{{ __("Save") }}</button>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>
