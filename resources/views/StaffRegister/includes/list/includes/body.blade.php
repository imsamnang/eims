<div class="card-body p-0">
    <div class="table-responsive" data-toggle="list" data-list-values='["id", "name"]'>
        <table id="list-table" class="table">
            <thead class="thead-light">
                <tr>
                    @if(Auth::user()->role_id != 8)
                    <th width="1">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" id="table-check-all" data-toggle="table-checked"
                                data-checked-controls="table-checked"
                                {{$response["success"] ? "" : "disabled=disabled"}}
                                data-checked-show-controls='["view","edit","delete"]' type="checkbox">
                            <label class="custom-control-label" for="table-check-all"></label>
                        </div>

                    </th>
                    @endif
                    <th width="1" class="sort" data-sort="id">{{Translator::phrase("numbering")}}​</th>
                    <th width="1" class="sort" data-sort="name">{{Translator::phrase("name")}}​</th>
                    <th width="1">{{Translator::phrase("gender")}}</th>
                    <th width="1" class="sort" data-sort="status"> {{Translator::phrase("study_status")}}​</th>
                    <th width="1">{{Translator::phrase("designation")}}</th>
                    @if (Auth::user()->role_id == 1)
                    <th width="1">{{Translator::phrase("institute")}}</th>
                    @endif
                    <th width="1">{{Translator::phrase("email. & .phone")}}</th>
                    <th width="1" class="sort" data-sort="account"> {{Translator::phrase("account")}}​</th>
                    <th width="1">{{Translator::phrase("photo")}}</th>
                </tr>

            </thead>

            <tbody class="list">
                @if ($response["success"])
                @foreach ($response["data"] as $row)

                <tr data-id="{{$row["id"]}}">
                    @if(Auth::user()->role_id != 8)
                    <td>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" data-toggle="table-checked"
                                id="table-check-{{$row["id"]}}" data-checked-show-controls='["view","edit","delete"]'
                                type="checkbox" data-checked="table-checked" value="{{$row["id"]}}">
                            <label class="custom-control-label" for="table-check-{{$row["id"]}}"></label>
                        </div>

                    </td>
                    @endif

                    <td class="id">{{$row["id"]}}</td>
                    <td class="name">
                        <button class="btn btn-sm collapsed" data-toggle="collapse-table"
                            data-target="#group-of-{{$row["id"]}}" aria-expanded="false"
                            aria-controls="group-of-{{$row["id"]}}">
                            <i class="fas fa-plus-minus"></i>
                        </button>

                        {{$row["first_name"]." ".$row["last_name"]}}
                    </td>
                    <td>{{$row["gender"]["name"]}}</td>
                    <td class="status">
                        <span class="rounded p-1 {{$row["staff_status"]['color']}}">
                            {{$row["staff_status"]["name"]}}
                        </span>
                    </td>
                    <td>{{$row["staff_institute"]["designation"]["name"]}}</td>
                    @if (Auth::user()->role_id == 1)
                    <td>{{$row["staff_institute"]["institute"]["short_name"]}}</td>
                    @endif
                    <td>
                        <span>
                            {{$row["email"]}}
                        </span>
                        <br>
                        <span>
                            {{$row["phone"]}}
                        </span>
                    </td>
                    <td class="account">
                        <span class="rounded p-1">
                            @if ($row["account"])
                            {{Translator::phrase("has_account")}}
                            <i class="fas fa-check-circle text-green"></i>
                            @else
                            <a href="{{$row["action"]["account"]}}" class="btn btn-sm btn-secondary"
                                data-checked-show="account"
                                data-target="#modal">{{Translator::phrase("create_account")}}</a>
                            @endif
                        </span>
                    </td>
                    <td>
                        <a href="#!" class="avatar">
                            <img data-src="{{$row["photo"]}}">
                        </a>
                    </td>
                </tr>
                @include("Staff.includes.list.includes.subbody.index")
                @endforeach
                @else
                <tr>
                    <td colspan="10" class="text-center p-3">
                        <p class="m-0"><svg width="64" height="41" viewBox="0 0 64 41"
                                xmlns="http://www.w3.org/2000/svg">
                                <g transform="translate(0 1)" fill="none" fill-rule="evenodd">
                                    <ellipse fill="#F5F5F5" cx="32" cy="33" rx="32" ry="7"></ellipse>
                                    <g fill-rule="nonzero" stroke="#D9D9D9">
                                        <path
                                            d="M55 12.76L44.854 1.258C44.367.474 43.656 0 42.907 0H21.093c-.749 0-1.46.474-1.947 1.257L9 12.761V22h46v-9.24z">
                                        </path>
                                        <path
                                            d="M41.613 15.931c0-1.605.994-2.93 2.227-2.931H55v18.137C55 33.26 53.68 35 52.05 35h-40.1C10.32 35 9 33.259 9 31.137V13h11.16c1.233 0 2.227 1.323 2.227 2.928v.022c0 1.605 1.005 2.901 2.237 2.901h14.752c1.232 0 2.237-1.308 2.237-2.913v-.007z"
                                            fill="#FAFAFA"></path>
                                    </g>
                                </g>
                            </svg></p>
                        <span>{{ $response["message"] }}</span>
                    </td>
                </tr>
                @endif

            </tbody>
        </table>
    </div>
</div>
