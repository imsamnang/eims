<div class="card-body row">
    <div class="col-4">
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
                                <img data-src="{{$list["front"]}}" class="avatar avatar-xs rounded-0">
                            </div>
                            <div class="col ml--2 p-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-sm font-weight-500 title">{{$list['name']}}
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
    <div class="col-8">
        <div class="tab-content bg-white p-0 border-0" id="myTabContent">
            @foreach ($response['data'] as $key => $row)

            <div id="tab--{{$row['id']}}" class="tab-pane fade {{$key == 0 ? 'active show' :''}}" role="tabpanel"
                aria-labelledby="tab--{{$row['id']}}">
                @include(config("pages.parent").".includes.view.includes.subbody.a",['row'=>$row])
                @include(config("pages.parent").".includes.view.includes.subbody.b",['row'=>$row])
                @if (config('pages.parameters.param1') == 'view')
                <div class="py-3">
                    <div class="float-right​">
                        @if (count($response['data']) > 1)
                        <a class="btn" target="_blank"
                            href="{{config('pages.form.action.edit')}}">{{__('Edit All')}}</a>
                        @endif

                        <a class="btn btn-primary" target="_blank" href="{{$row['action']['edit']}}">
                            <i class="fas fa-edit"></i>
                            {{__('Edit')}}
                        </a>



                        <a class="btn btn-danger" target="_blank" href="{{$row['action']['delete']}}">
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
