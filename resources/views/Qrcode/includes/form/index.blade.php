<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card-wrapper">

            <div class="card p-0 m-0">
                @if (config('pages.parameters.param1') == 'add')
                <div class="card-header">
                    <h5 class="h3 mb-0">
                        {{ __(config('pages.form.role')) }}
                    </h5>
                </div>
                @elseif(config('pages.parameters.param1') == 'edit' && !request()->ajax())
                <div class="card-header">
                    <h5 class="h3 mb-0">
                        {{ __(config('pages.form.role')) }}
                    </h5>
                </div>
                @endif

                <div class="card-body p-0 m-0">
                    <div class="row">
                        @if (count($listData) > 1)
                        <div class="col-md-3">
                            <div class="card sticky-top">
                                <div class="card-header py-2 px-3">
                                    <label class="label-arrow label-primary label-arrow-right label-arrow-left w-100">
                                        {{__("List")}}
                                    </label>
                                </div>

                                <div class="card-body p-2" style="max-height: 450px;overflow: auto;">
                                    <div class="list-group list-group-flush">
                                        @foreach ($listData as $key => $list)
                                        <a data-toggle="tab" href="#tab--{{$list['id']}}" role="tab"
                                            aria-controls="tab--{{$list['id']}}" data-toggle="list-group"
                                            class="list-group-item list-group-item-action p-2 {{$key == 0 ? 'active' :''}}">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <img data-src="{{$list["photo"]}}"
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
                        <div class="{{count($listData) <= 1 ? "col-md-12":"col-md-9"}}" data-list-group>
                            <div class="tab-content p-0 border-0" id="myTabContent">
                                @foreach (config('pages.form.data') as $key => $row)
                                <div id="tab--{{config('pages.form.data.'.$key.'.id')}}"
                                    class="tab-pane fade {{$key == 0 ? 'active show' :''}}" role="tabpanel"
                                    aria-labelledby="tab--{{config('pages.form.data.'.$key.'.id')}}">
                                    <form role="{{config("pages.form.role")}}" class="needs-validation" novalidate=""
                                        method="POST"
                                        action-one="{{$row['action']['qrcode']}}"
                                        action-all="{{config("pages.form.action.detect")}}"
                                        id="form-{{config("pages.form.name")}}" enctype="multipart/form-data"
                                        data-validate="{{json_encode(config('pages.form.validate'))}}">
                                        @csrf
                                        <div class="card m-0">
                                            <div class="card-body p-0">
                                                <div class="row">
                                                    <div class="col-4">
                                                        @include(config("pages.parent").".includes.form.includes.a",['key'=>$key])
                                                    </div>
                                                    <div class="col-8">
                                                        @include(config("pages.parent").".includes.form.includes.b",['key'=>$key])
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="card-footer">
                                                <a href="" name="scrollTo"></a>
                                                @if (count(config('pages.form.data')) > 1)
                                                <input class="btn btn-primary float-left" type="submit"
                                                    value="{{__('Make All')}}" id="submit-all">
                                                @endif

                                                <input class="btn btn-primary float-right" type="submit"
                                                    value="{{__('Make')}}" id="submit-one">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
