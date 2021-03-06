<div class="row">
    <div class="col-xl-12 col-lg-12  col-md-12 col-sm-12 col-xs-12">
        <div class="card-wrapper">
            <form role="{{config("pages.form.role")}}" class="needs-validation" novalidate="" method="POST"
                action="{{config("pages.form.action.detect")}}"
                id="form-{{config("pages.form.name")}}" enctype="multipart/form-data"
                data-validate="{{json_encode(config('pages.form.validate'))}}">
                <div class="card p-0">
                    <div class="card-header">
                        <h5 class="h3 mb-0">
                            {{ __("Register") }}
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="{{count($listData) <= 1 ? "col-md-12":"col-md-8"}}" data-list-group>
                                @include(config("pages.parent").".includes.dashboard.includes.a")
                                @include(config("pages.parent").".includes.dashboard.includes.b")
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
                    <div class="card-footer">

                        <div class="row">
                            {{-- <div class="col">
                                <select class="form-control" data-toggle="select" id="teacher_or_student"
                                     
                            data-placeholder=""
                            name="teacher_or_student"
                            {{config("pages.form.validate.rules.teacher_or_student") ? "required" : ""}}>
                            <option value="6">{{ __("Student")}}</option>
                            <option value="8">{{ __("Teacher")}}</option>
                            </select>
                        </div> --}}
                        <div class="col">
                            <button
                                class="btn btn-primary ml-auto float-right {{config("pages.form.role") == "view"? "d-none": ""}}"
                                type="submit">
                                @if (config("pages.form.role") == "add")
                                {{ __("Register") }}
                                @endif
                            </button>
                        </div>
                    </div>


                </div>

        </div>
        </form>

    </div>
</div>
</div>
