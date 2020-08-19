<div class="card-header">
    <div class="col-lg-12 p-0">
        <a href="{{config("pages.form.action.detect")}}" class="btn btn-primary" data-toggle="modal"
            data-target="#modal-add" data-backdrop="static" data-keyboard="false">
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
        <a href="#" data-toggle="report" class="float-right btn btn-success mb-3" role="button" aria-expanded="false">
            <i class="fas fa-file-export m-0"></i>
            <span class="d-none d-sm-inline">
                {{__("Report")}}
            </span>
        </a>
    </div>

</div>
<div class="card-header border-0 pb-0">
    <form role="filter" class="needs-validation" method="GET" action="{{request()->url()}}" id="form-datatable-filter1"
        enctype="multipart/form-data">
        <div class="row flex-lg-row flex-md-row flex-sm-row-reverse flex-xs-row-reverse">
            <div class="col-12 collapse mb-3 " id="filter">
                <div class="form-row">
                    <div class="col-md-6">
                        <select class="form-control" data-toggle="select" id="institute" title="Simple select"
                            data-allow-clear="true" data-text="{{ __("Add new option") }}"
                            data-placeholder="{{__('Institute')}}" name="instituteId"
                            data-select-value="{{request('instituteId')}}">
                            @foreach($instituteFilter["data"] as $o)
                            <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" data-toggle="select" id="program" title="Simple select"
                            data-allow-clear="true" data-text="{{ __("Add new option") }}"
                            data-placeholder="{{__('Study Program')}}" name="programId"
                            data-select-value="{{request('programId')}}">
                            @foreach($programFilter["data"] as $o)
                            <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">{{ $o["name"]}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary float-right"><i class="fa fa-filter-search"></i>
                            {{ __("Search filter") }}</button>
                    </div>
                </div>

            </div>
            <div class="col-8">
            </div>
            <div class="col-4 d-none">
                <div class="form-group w-100">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="dropdown" data-close="false">
                                <span class="input-group-text h-100" data-toggle="dropdown">
                                    <i class="fas fa-filter"></i>
                                </span>

                            </div>
                        </div>
                        <input type="text" class="form-control" name="search" id="search" data-toggle="table-filter"
                            data-target="#table" placeholder="{{ __('Search') }}" value="{{request("search")}}" />
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
