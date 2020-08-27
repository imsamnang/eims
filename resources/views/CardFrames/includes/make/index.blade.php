<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card-wrapper">

            <div class="card p-0 m-0">
                <div class="card-body p-0 m-0">
                    <div class="row">

                        <div class="col-md-3">
                            @if (count($response['data']) > 1)
                            <div class="card" style="z-index: 2">
                                <div class="card-header py-2 px-3">
                                    <label class="label-arrow label-primary label-arrow-right label-arrow-left w-100">
                                        {{__("List")}}
                                    </label>
                                </div>

                                <div class="card-body p-2" style="max-height: 450px;overflow: auto;">
                                    <div class="list-group list-group-flush">
                                        @foreach ($response['data'] as $key => $list)
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
                                                        <div class="text-sm font-weight-500 title">{{$list["fullname"]}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="card">
                                <div class="card-header py-2 px-3">
                                    <label class="label-arrow label-primary label-arrow-right label-arrow-left w-100">
                                        {{__("Frame")}}
                                    </label>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table id="list-table" class="table">
                                            <tbody class="list">
                                                @foreach ($cards["data"] as $card)
                                                <tr data-id="{{$card["id"]}}">
                                                    <td class="text-left image p-1">
                                                        <img width="70px" data-src="{{$card["foreground"]}}" alt="">
                                                        <img width="70px" data-src="{{$card["background"]}}" alt="">
                                                    </td>
                                                    <td class="text-right p-1">
                                                        <a href="{{$card["action"]["set"]}}" data-toggle="card-frame"
                                                            data-id="{{$card["id"]}}"
                                                            data-title="{{__("Set as default")}}"
                                                            data-text="{{$card["name"]}}"
                                                            data-confirm-button-text="{{__("Set")}}"
                                                            data-cancel-button-text="{{__("Cancel")}}"
                                                            data-text-select="{{ __("Select") }}"
                                                            data-text-selected="{{ __("Selected") }}"
                                                            data-image="{{$card["foreground"]}},{{$card["background"]}}"
                                                            data-link="{{$card["action"]["set"]}}"
                                                            class="btn btn-sm btn-primary {{$card["status"] ? 'disabled' : ''}}">
                                                            {{__("Select")}}
                                                        </a>

                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9" data-list-group>
                            <div class="tab-content p-0 border-0 sticky-top" data-spy="affix" data-offset-top="100"
                                id="myTabContent">
                                @foreach ($response['data'] as $key => $row)
                                <div id="tab--{{$row['id']}}" class="tab-pane fade {{$key == 0 ? 'active show' :''}}"
                                    role="tabpanel" aria-labelledby="tab--{{$row['id']}}">
                                    <form role="{{config("pages.form.role")}}" class="needs-validation" novalidate=""
                                        method="POST" id="form-card-make-{{$key}}" enctype="multipart/form-data"
                                        action-one="{{$row['action']['card']}}"
                                        action-all="{{config("pages.form.action.detect")}}"
                                        data-validate="{{json_encode(config('pages.form.validate'))}}">
                                        <div class="card m-0">
                                            <div class="card-body p-0">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        @csrf
                                                        @include(config("pages.parent").".includes.make.includes.a",[
                                                        'all'=> $response['all'],
                                                        'selected'=> $response['selected'],
                                                        'frame'=> $response['frame'],
                                                        'row'=>$row
                                                        ])
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">

                                                @if (count($response['data']) > 1)
                                                <input class="btn btn-primary float-left" type="submit"
                                                    value="{{__('Adjust​ All')}}" id="submit-all">
                                                @endif

                                                <input class="btn btn-primary float-right" type="submit"
                                                    value="{{__('Adjust')}}" id="submit-one">
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
