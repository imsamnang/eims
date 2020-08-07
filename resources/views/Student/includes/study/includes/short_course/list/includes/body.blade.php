<div class="card-body {{request()->segment(2) == "study" ? "py-0" : "p-0"}}">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-light">
                <th>
                    {{__("Id Request study")}}
                </th>
                <th>
                    {{__("Image")}}
                </th>
                <th>
                    {{__("Study subjects")}}
                </th>
                <th>
                    {{__("Study start & Study end")}}
                </th>
            </thead>

            <body>
                @if ($studys['success'])
                @foreach ($studys['data'] as $row)
                <tr>
                    <td>
                        {{$row['request_id']}}
                    </td>
                    <td>
                        <img data-src="{{$row["image"]}}" class="rounded img-fluid" width="50px">
                    </td>
                    <td>
                        {{$row["name"]}}
                    </td>
                    <td>
                        {{$row["study_start"]}} - {{$row["study_end"]}}
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="3" class="text-center p-3">
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
                        <span>{{ $studys["message"] }}</span>
                    </td>
                </tr>
                @endif
            </body>
        </table>
    </div>
</div>
