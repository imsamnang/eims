@php
$i18n = [
"decimal" => "",
"emptyTable" => Translator::phrase("no_data"),
"info" => Translator::phrase("Showing")." _START_ ".Translator::phrase("to")." _END_ ".Translator::phrase("of")."
_TOTAL_ ".Translator::phrase("entries"),
"infoEmpty" => Translator::phrase("Showing")." 0 ".Translator::phrase("to")." 0 ".Translator::phrase("of")." 0
".Translator::phrase("entries"),
"infoFiltered" => "",
"infoPostFix" => "",
"thousands" => ",",
"lengthMenu" => Translator::phrase("show") ." _MENU_ ".Translator::phrase("entries"),
"loadingRecords" => Translator::phrase("loading")."...",
"processing" => Translator::phrase("processing")."...",
"search" => Translator::phrase("search.:"),
"zeroRecords" => '<p class="m-0"><svg width="64" height="41" viewBox="0 0 64 41" xmlns="http://www.w3.org/2000/svg">
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
<span>'.Translator::phrase("no_data").'</span>',
"paginate" => [
"first" => Translator::phrase("First"),
"last" => Translator::phrase("Last"),
"next" => '<i class="fas fa-angle-right">',
    "previous" => '<i class="fas fa-angle-left">'
        ],
        "aria" => [
        "sortAscending" => " => activate to sort column ascending",
        "sortDescending" => " => activate to sort column descending"
        ]

        ];
        @endphp


        <div class="card-body p-0">
            <div class="table-responsive py-4">
                <table
                    data-url="{{str_replace("add","list-datatable",config("pages.form.action.add"))}}{{config("pages.search")}}"
                    class="table table-flush" data-toggle="datatable-ajax" data-i18n='{!!json_encode($i18n)!!}'>
                    <thead class="thead-light">
                        <tr>
                            <th data-type="checkbox" data-key="null" width="1">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" id="table-check-all" data-toggle="table-checked"
                                        data-checked-controls="table-checked"
                                        data-checked-show-controls='["view","edit","delete"]' type="checkbox">
                                    <label class="custom-control-label" for="table-check-all"></label>
                                </div>
                            </th>
                            <th width=1 data-type="text" data-key="id" width="1" class="sort" data-sort="id">
                                {{Translator::phrase("numbering")}}​</th>

                            <th width=1 data-type="text" data-key="name">
                                {{Translator::phrase("name")}}​
                            </th>
                            @if (Auth::user()->role_id != 2)
                            <th data-type="text" data-key="institute.short_name" width="1" class="sort"
                                data-sort="institute">{{Translator::phrase("institute")}}​</th>
                            @endif
                            <th data-type="text" data-key="study_program.name" width="1" class="sort"
                                data-sort="study_program">
                                {{Translator::phrase("study_program")}}
                            </th>
                            <th data-type="text" data-key="study_course.name" width="1" class="sort"
                                data-sort="study_course">
                                {{Translator::phrase("study_course")}}
                            </th>
                            {{-- <th data-type="text" data-key="study_generation.name" width="1" class="sort" data-sort="study_generation">
                                {{Translator::phrase("study_generation")}}
                            </th> --}}
                            <th data-type="text" data-key="study_academic_year.name" width="1" class="sort"
                                data-sort="study_academic_year">
                                {{Translator::phrase("study_academic_year")}}
                            </th>
                            <th data-type="text" data-key="study_semester.name" width="1" class="sort"
                                data-sort="study_semester">
                                {{Translator::phrase("study_semester")}}
                            </th>
                            <th data-type="text" data-key="study_session.name" width="1" class="sort"
                                data-sort="study_session">
                                {{Translator::phrase("study_session")}}
                            </th>
                            <th data-type="text" data-key="status" width="1" class="sort" data-sort="status">
                                {{Translator::phrase("status")}}
                            </th>
                            <th width=1 data-type="image" data-key="photo">{{Translator::phrase("photo")}}​</th>
                            <th width=1 data-type="option" data-key="view,edit,approve">
                            </th>

                        </tr>
                    </thead>
                </table>
                <div class="d-none" id="datatable-ajax-option">
                    <div class="dropdown">
                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <a data-toggle="modal" data-target="#modal" id="btn-option-view" class="dropdown-item"
                                href="">
                                <i class="fas fa-eye"></i> {{Translator::phrase("view")}}
                            </a>

                            <a data-toggle="modal" data-target="#modal" id="btn-option-edit" class="dropdown-item">
                                <i class="fas fa-edit"></i>
                                {{Translator::phrase("edit")}}
                            </a>

                            <a data-toggle="modal" data-target="#modal" id="btn-option-approve" class="dropdown-item">
                                <i class="fas fa-check-circle"></i>
                                {{Translator::phrase("approve")}}
                            </a>

                            <div class="dropdown-divider"></div>

                            <a class="d-none dropdown-item sweet-alert-reload" data-toggle="sweet-alert"
                                id="btn-option-delete" data-sweet-alert="confirm" data-sweet-id="" href="">
                                <i class="fas fa-trash"></i> {{Translator::phrase("delete")}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
