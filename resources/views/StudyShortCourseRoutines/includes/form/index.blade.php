<div class="row">
    <div class="col-xl-12">
        <div class="card-wrapper">
            <form role="{{config("pages.form.role")}}" class="needs-validation" novalidate="" method="POST"
                action="{{config("pages.form.action.detect")}}"
                id="form-course-routine" enctype="multipart/form-data"
                data-validation="{{json_encode(config('pages.form.validate'))}}">
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
                            </div>
                        </div>
                    </div>


                    <div class="card-footer">
                        @if (!request()->ajax())
                        <a href="{{url(config("pages.host").config("pages.path").config("pages.pathview")."list")}}"
                            class="btn btn-default" type="button">{{ __("Back") }}</a>
                        @endif
                        <a href="" name="scrollTo"></a>
                        <button
                            class="btn btn-primary ml-auto float-right {{config("pages.form.role") == "view"? "d-none": ""}}"
                            type="submit">
                            @if (config("pages.form.role") == "add")
                            {{ __("Save") }}
                            @elseif(config("pages.form.role") == "edit")
                            {{ __("Update") }}
                            @elseif(config("pages.form.role") == "view")
                            {{ __("Goto Edit") }}
                            @endif
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>
