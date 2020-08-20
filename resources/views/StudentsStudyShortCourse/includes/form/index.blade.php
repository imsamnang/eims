<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card-wrapper">
            <form role="{{config("pages.form.role")}}" class="needs-validation" novalidate="" method="POST"
                action="{{config('pages.form.data.'.$key.'.action.edit',config("pages.form.action.detect"))}}"
                id="form-{{config("pages.form.name")}}" enctype="multipart/form-data"
                data-validate="{{json_encode(config('pages.form.validate'))}}">
                <div class="card p-0 m-0">
                    <div class="card-header">
                        <h5 class="h3 mb-0">
                            {{ __(config("pages.form.role")) }}
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="{{count($listData) <= 1 ? "col-md-12":"col-md-8"}}" data-list-group>
                                <div class="row">
                                    <div class="col-md-12">
                                        @csrf
                                        @include(config("pages.parent").".includes.form.includes.a")
                                    </div>

                                </div>
                            </div>
                            @if (count($listData) > 1)
                            <div class="col-md-4">
                                <div class="card sticky-top">
                                    <div class="card-header py-2 px-3">
                                        <label
                                            class="label-arrow label-primary label-arrow-right label-arrow-left w-100">
                                            {{__("List")}}
                                        </label>
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="list-group list-group-flush">
                                            @foreach ($listData as $list)
                                            <a href="{{$list["action"][config("pages.form.role")]}}"
                                                data-toggle="list-group"
                                                class="list-group-item list-group-item-action p-2 {{ config("pages.form.data.id") == $list["id"] ? "active" : null}}">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <img data-src="{{$list["image"]}}"
                                                            class="avatar avatar-xs rounded-0">
                                                    </div>
                                                    <div class="col ml--2 p-0">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="text-sm font-weight-500 title">{{$list["name"]}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <a href="" name="scrollTo"></a>


                    @if (config('pages.parameters.param1') == 'add')

                    @if (!request()->ajax())
                    <div class="card-footer">
                        <a href="{{url(config("pages.host").config("pages.path").config("pages.pathview")."list")}}"
                            class="btn btn-default" type="button">
                            {{ __("Back") }}
                        </a>

                        <input class="btn btn-primary float-right" type="submit" value="{{__('Save')}}" id="submit">
                    </div>
                    @endif
                    @elseif(config('pages.parameters.param1') == 'edit')
                    @if (!request()->ajax())
                    <div class="card-footer">
                        <a href="{{url(config("pages.host").config("pages.path").config("pages.pathview")."list")}}"
                            class="btn btn-default" type="button">
                            {{ __("Back") }}
                        </a>


                        <input class="btn btn-primary float-right" type="submit" value="{{__('Update')}}" id="submit">
                    </div>
                    @else
                    @if (count($listData) > 1)
                    <div class="card-footer">

                        <input class="btn btn-primary float-right" type="submit" value="{{__('Update')}}" id="submit">
                    </div>
                    @endif
                    @endif
                    @endif

                </div>
            </form>

        </div>
    </div>
</div>
