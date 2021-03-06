<div class="card-header">
    <div class="col-lg-12 p-0">
        <a href="{{config('pages.form.data.'.$key.'.action.edit',config("pages.form.action.detect"))}}?t-subjectId={{request("t-subjectId")}}"
            class="btn btn-primary" data-toggle="modal-ajax" data-target="#modal" data-backdrop="static"
            data-keyboard="false">
            <i class="fa fa-plus m-0"></i>
            <span class="d-none d-sm-inline">
                {{__("Add")}}
            </span>
        </a>
        <a href="#" data-href="{{config("pages.form.action.view")}}" class="btn btn-primary disabled"
            data-checked-show="view" data-target="#modal" data-backdrop="static" data-keyboard="false">
            <i class="fa fa-eye m-0"></i>
            <span class="d-none d-sm-inline">
                {{__("View")}}
            </span>
        </a>
        <a href="#" data-href="{{config("pages.form.action.edit")}}" class="btn btn-primary disabled"
            data-checked-show="edit" data-target="#modal" data-backdrop="static" data-keyboard="false">
            <i class="fa fa-edit m-0"></i>
            <span class="d-none d-sm-inline">
                {{__("Edit")}}
            </span>
        </a>
        <a href="#" data-href="{{config("pages.form.action.delete")}}" class="btn btn-danger disabled"
            data-toggle="sweet-alert" data-sweet-alert="confirm" sweet-alert-controls-id="" data-checked-show="delete">
            <i class="fa fa-trash m-0"></i>
            <span class="d-none d-sm-inline">
                {{__("Delete")}}
            </span>

        </a>
        <a href="#filter" data-toggle="collapse" class="btn btn-primary" role="button" aria-expanded="false">
            <i class="fa fa-filter m-0"></i>
            <span class="d-none d-sm-inline">
                {{__("Filter")}}
            </span>
        </a>
        <a href="{{str_replace("add","grid",config('pages.form.data.'.$key.'.action.edit',config("pages.form.action.detect")))}}"
            class="btn btn-primary">
            <i class="fas fa-list m-0"></i>
            <span class="d-none d-sm-inline">
                {{__("Grid")}}
            </span>
        </a>
    </div>
</div>
<div class="card-header border-0 pb-0">
    <form role="filter" class="needs-validation" method="GET" action="{{request()->url()}}" id="form-datatable-filter"
        enctype="multipart/form-data">
        <div class="row flex-lg-row flex-md-row flex-sm-row-reverse flex-xs-row-reverse">
            <div class="col-12 collapse mb-3 {{request("quizId") ? "show" : ""}}" id="filter">
                <div class="form-row">
                    <div class="col-md-8">
                        <select class="form-control" data-toggle="select" id="staff_teach_subject" 
                            data-allow-clear="true"  data-placeholder=""
                            data-select-value="{{request("t-subjectId")}}">
                            @foreach($staff_teach_subject["data"] as $o)
                            <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary float-right"><i class="fa fa-filter-search"></i>
                            {{ __("Search filter") }}</button>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
