<div class="row">
    <div class="col">
        <div class="card">
            @if (!request()->ajax())
            @include("Card.includes.modal")
            @endif
            <div class="table-responsive py-4">
                <table class="table table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th>{{Translator::phrase("numbering")}}</th>
                            <th>{{Translator::phrase("name")}}</th>
                            <th>{{Translator::phrase("type")}}</th>
                            <th>{{Translator::phrase("layout")}}</th>
                            <th>{{Translator::phrase("frame_front. & .frame_background")}}</th>
                            <th>{{Translator::phrase("action")}}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if ($cards["success"])
                        @foreach ($cards["data"] as $card)
                        <tr data-id="{{$card["id"]}}">
                            <td>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                        id="customCheck-{{$card["id"]}}">
                                    <label class="custom-control-label" for="customCheck-{{$card["id"]}}">
                                        {{$card["id"]}}</label>
                                </div>

                            </td>
                            <td style="width: 300px; white-space: inherit;">
                                {{$card["name"]}}
                            </td>
                            <td>
                                {{$card["type"]}}
                            </td>
                            <td>{{$card["layout"]}}</td>
                            <td class="text-left image">
                                <img width="70px" data-src="{{$card["front"]}}" alt="">
                                <img width="70px" data-src="{{$card["background"]}}" alt="">

                                @if ($card["status"])
                                <span class="active">
                                    <li class="fas fa-check"></li>
                                </span>
                                @endif
                            </td>

                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a  href="{{$card["action"]["set"]}}" data-toggle="card-frame"
                                            data-id="{{$card["id"]}}"
                                            data-title="{{Translator::phrase("set.as.default")}}"
                                            data-text="{{$card["name"]}}"
                                            data-confirm-button-text="{{Translator::phrase("set")}}"
                                            data-cancel-button-text="{{Translator::phrase("cancel")}}"
                                            data-image="{{$card["front"]}},{{$card["background"]}}"
                                            data-link="{{$card["action"]["set"]}}"
                                            class="dropdown-item {{!$card["status"] ?: "d-none"}}">
                                            <i class="fas fa-check-square"></i>{{Translator::phrase("set.as.default")}}
                                        </a>

                                        <a data-toggle="modal" data-target="#modal" class="dropdown-item"
                                            data-backdrop="static" data-keyboard="false" href="{{$card["action"]["view"]}}">
                                            <i class="fas fa-eye"></i> {{Translator::phrase("view")}}
                                        </a>

                                        <a data-toggle="modal" data-target="#modal" class="dropdown-item"
                                            data-backdrop="static" data-keyboard="false" href="{{$card["action"]["edit"]}}">
                                            <i class="fas fa-edit"></i> {{Translator::phrase("edit")}}</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" data-toggle="sweet-alert" data-sweet-alert="confirm"
                                            data-sweet-id="{{$card["id"]}}" href="{{$card["action"]["delete"]}}">
                                            <i class="fas fa-trash"></i> {{Translator::phrase("delete")}}</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tr>

                        @endforeach
                        @endif


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
