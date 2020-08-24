<div class="card-body row">
    <div class="col-3">
        <div class="card sticky-top">
            <div class="card-header py-2 px-3">
                <label class="label-arrow label-primary label-arrow-right label-arrow-left w-100">
                    {{__("List")}}
                </label>
            </div>
            <div class="card-body p-2" style="max-height: 450px;overflow:auto">
                <div class="list-group list-group-flush text-sm">
                    @foreach ($response['data'] as $key => $list)
                    <a data-toggle="tab" href="#tab--{{$list['id']}}" role="tab" aria-controls="tab--{{$list['id']}}"
                        data-toggle="list-group"
                        class="list-group-item list-group-item-action p-2 {{$key == 0 ? 'active' :''}}">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <img data-src="{{$list["photo"]}}" class="avatar avatar-xs rounded-0">
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
    <div class="col-9">
        <div class="tab-content bg-white p-0 border-0" id="myTabContent">
            @foreach ($response['data'] as $key => $row)

            <div id="tab--{{$row['id']}}" class="tab-pane fade {{$key == 0 ? 'active show' :''}}" role="tabpanel"
                aria-labelledby="tab--{{$row['id']}}">
                @include(config("pages.parent").".includes.view.includes.subbody.a",['row'=>$row])
                @include(config("pages.parent").".includes.view.includes.subbody.b",['row'=>$row])
                @include(config("pages.parent").".includes.view.includes.subbody.c",['row'=>$row])
                @if (config('pages.parameters.param1') == 'view')
                <div class="py-3">
                    <div class="float-right​">

                        @if (count($response['data']) > 1)
                        <a class="btn mb-3" target="_blank"
                            href="{{str_replace('view','print',config('pages.form.action.view'))}}">{{__('Print All')}}</a>
                        <a class="btn mb-3" target="_blank"
                            href="{{config('pages.form.action.edit')}}">{{__('Edit All')}}</a>
                        <div class="dropdown-divider"></div>
                        @endif

                        <a class="btn btn-primary mb-3" target="_blank" href="{{$row['action']['edit']}}">
                            <i class="fas fa-edit"></i>
                            {{__('Edit')}}
                        </a>
                        @if (!$row['account'])
                        <a class="btn btn-primary mb-3" target="_blank" href="{{$row['action']['account']}}">
                            <i class="fas fa-user"></i>
                            {{__('Create account')}}
                        </a>

                        @endif

                        <a class="btn btn-primary mb-3" href="{{$row["action"]["photo"]}}" id="btn-option-photo"
                            class="dropdown-item">
                            <i class="fas fa-portrait "></i>
                            {{__("Photo")}}
                        </a>
                        <a class="btn btn-primary mb-3" href="{{$row["action"]["qrcode"]}}" id="btn-option-qrcode"
                            class="dropdown-item">
                            <i class="fas fa-qrcode "></i>
                            {{__("Qrcode")}}
                        </a>
                        <a class="btn btn-primary mb-3" href="{{$row["action"]["card"]}}" id="btn-option-card"
                            class="dropdown-item">
                            <i class="fas fa-id-card "></i>
                            {{__("Card")}}
                        </a>

                        <a class="btn btn-primary mb-3" href="{{$row["action"]["certificate"]}}"
                            id="btn-option-certificate" class="dropdown-item">
                            <i class="fas fa-file-certificate"></i>
                            {{__("Certificate")}}
                        </a>

                        <a class="btn btn-danger mb-3" target="_blank" href="{{$row['action']['delete']}}">
                            <i class="fas fa-trash"></i>
                            {{__('Delete')}}
                        </a>
                    </div>
                </div>
                @endif

            </div>
            @endforeach
        </div>
    </div>

</div>
