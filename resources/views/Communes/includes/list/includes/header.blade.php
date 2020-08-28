<div class="card-header">
    <div class="col-lg-12 p-0">
        <a href="{{config("pages.form.action.detect")}}" class="btn btn-primary" data-toggle="modal"
            data-backdrop="static" data-keyboard="false" data-target="#modal-add">
            <i class="fa fa-plus m-0"></i>
            <span class="d-none d-sm-inline">
                {{__("Add")}}
            </span>
        </a>
        <a href="#" data-href="{{config("pages.form.action.view")}}" class="btn btn-primary disabled"
            data-backdrop="static" data-keyboard="false" data-checked-show="view" data-target="#modal">
            <i class="fa fa-eye m-0"></i>
            <span class="d-none d-sm-inline">
                {{__("View")}}
            </span>
        </a>
        <a href="#" data-href="{{config("pages.form.action.edit")}}" class="btn btn-primary disabled"
            data-backdrop="static" data-keyboard="false" data-checked-show="edit" data-target="#modal">
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
        @if(isset($provinces) ||isset($districts) || isset($communes) )
        <a href="#filter" data-toggle="collapse" class="btn btn-primary" role="button" aria-expanded="false">
            <i class="fa fa-filter m-0"></i>
            <span class="d-none d-sm-inline">
                {{__("Filter")}}
            </span>
        </a>
        @endif


    </div>


</div>
<div class="card-header border-0 pb-0 collapse" id="filter">
    <form role="search" class="needs-validation" method="GET" action="{{request()->url()}}" id="form-filter"
        enctype="multipart/form-data">
        <div class="row flex-lg-row flex-md-row flex-sm-row-reverse flex-xs-row-reverse mb-3 p-0">
            <div class="col-md-9">
                <div class="form-row">
                    @isset($provinces)
                    <div class="col-md-4 mb-3">
                        <select class="form-control" data-toggle="select" id="province" 
                            data-allow-clear="true" data-add-text="{{ __("Add new option") }}" name="provinceId"
                            data-placeholder="{{__('Province')}}" data-select-value="{{request('provinceId')}}"
                            {{isset($districts) ? " data-append-to=#district data-append-url=".$districts["action"]["list"]."?provinceId=" :""}}>
                            @foreach($provinces["data"] as $o)
                            <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">
                                {{ $o["name"]}}
                                @endforeach
                        </select>
                    </div>
                    @endisset

                    @isset($districts)
                    <div class="col-md-4 mb-3">
                        <select disabled {{request('districtId') ? "" : "disabled"}} class="form-control"
                            data-toggle="select" id="district"  data-allow-clear="true"
                            data-add-text="{{ __("Add new option") }}" data-placeholder="{{__('District')}}"
                            name="districtId" data-select-value="{{request('districtId')}}"
                            {{isset($communes) ? " data-append-to=#commune data-append-url=".$communes["action"]["list"]."?districtId=":""}}>
                            @foreach($districts["data"] as $o)
                            <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">
                                {{ $o["name"]}}
                                @endforeach
                        </select>
                    </div>
                    @endisset

                    @isset($communes)
                    <div class="col-md-4 mb-3">
                        <select disabled {{request('communeId') ? "" : "disabled"}} class="form-control"
                            data-toggle="select" id="commune"  data-allow-clear="true"
                            data-add-text="{{ __("Add new option") }}" data-placeholder="{{__('Commune')}}"
                            data-select-value="{{request('communeId')}}" ​ name="communeId">
                            @foreach($communes["data"] as $o)
                            <option data-src="{{$o["image"]}}" value="{{$o["id"]}}">
                                {{ $o["name"]}}
                                @endforeach
                        </select>
                    </div>
                    @endisset
                </div>
            </div>

            <div class="col-md-3">
                <button type="submit" class="btn btn-primary float-right">
                    <i class="fa fa-filter-search"></i>
                    {{ __("Search filter") }}
                </button>
            </div>
        </div>
    </form>
</div>
