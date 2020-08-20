<div class="card-header border-0">
    <div class="col-lg-12 p-0">
        <a href="{{config("pages.form.action.detect")}}" class="btn btn-primary" data-toggle="modal-ajax"
            data-target="#modal">
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

        @if (request()->segment(3) !== "request")
        <a href="{{str_replace("add","list",config('pages.form.data.'.$key.'.action.edit',config("pages.form.action.detect")))}}"
            target="_blank" class="float-right full-link">
            <i class="fas fa-external-link"></i>
        </a>
        @endif
    </div>
</div>
