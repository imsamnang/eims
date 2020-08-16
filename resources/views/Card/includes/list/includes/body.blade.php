<div class="card-body p-0">
    <div class="table-responsive py-4">
        <table id="datatable-basic"
            data-url="{{str_replace("add","list-datatable",config("pages.form.action.add"))}}{{config("pages.search")}}"
            class="table table-flush">
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
                        {{__("Id")}}​</th>

                    <th data-type="text" data-key="name">
                        {{__("Name")}}​
                    </th>
                    <th width=1 data-type="text" data-key="name">
                        {{__("Layout")}}​
                    </th>
                    <th data-type="text" data-key="description">
                        {{__("Description")}}​
                    </th>
                    <th width=1 data-type="icon" data-key="status">
                        {{__("Status")}}​
                    </th>
                    <th width=1 data-type="option" data-key="view,edit,delete"></th>
                </tr>

            </thead>
            <tbody>
                @foreach ($response['data'] as $row)
                <tr data-target="#modal" data-href="{{$row["action"]["view"]}}">
                    <td>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" data-toggle="table-checked"
                                id="table-check-{{$row["id"]}}"
                                data-checked-show-controls='["view","edit","account","delete"]' type="checkbox"
                                data-checked="table-checked" value="{{$row["id"]}}">
                            <label class="custom-control-label" for="table-check-{{$row["id"]}}"></label>
                        </div>

                    </td>
                    <td>{{$row['id']}}</td>
                    <td>{{$row['name']}}</td>
                    <td>{{$row['layout']}}</td>
                    <td>{{$row['description']}}</td>
                    <td>
                        @if ($row['status'])
                        <i class="fas fa-check-circle text-green"></i>
                        @endif
                    </td>

                    <td class="text-right">
                        <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a data-toggle="modal-ajax" data-target="#modal" class="dropdown-item"
                                    href="{{$row["action"]["view"]}}">
                                    <i class="fas fa-eye"></i> {{__("View")}}
                                </a>

                                <a data-toggle="modal-ajax" data-target="#modal" class="dropdown-item"
                                    href="{{$row["action"]["edit"]}}">
                                    <i class="fas fa-edit"></i> {{__("Edit")}}</a>

                                <a href="#" data-toggle="card-frame" data-id="{{$row['id']}}" id="btn-option-set"
                                    data-title="{{__("Set as default")}}" data-text="{{$row['name']}}"
                                    data-confirm-button-text="{{__("Set")}}" data-cancel-button-text="{{__("Cancel")}}"
                                    data-text-select="{{ __("Select") }}" data-text-selected="{{ __("Selected") }}"
                                    data-image="{{$row['front']}},{{$row['background']}}"
                                    data-link="{{$row['action']['set']}}" class="dropdown-item">
                                    <i class="fas fa-check-square"></i>
                                    {{__("Set as default")}}

                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" data-toggle="sweet-alert" data-sweet-alert="confirm"
                                    data-sweet-id="{{$row["id"]}}" href="{{$row["action"]["delete"]}}">
                                    <i class="fas fa-trash"></i> {{__("Delete")}}</a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-none" id="datatable-ajax-option">
            <div class="dropdown">
                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <a data-toggle="modal-ajax" data-target="#modal" id="btn-option-view" class="dropdown-item" href="">
                        <i class="fas fa-eye"></i> {{__("View")}}
                    </a>

                    <a data-toggle="modal-ajax" data-target="#modal" id="btn-option-edit" class="dropdown-item" href="">

                        <i class="fas fa-edit"></i> {{__("Edit")}}</a>


                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item sweet-alert-reload" data-toggle="sweet-alert" id="btn-option-delete"
                        data-sweet-alert="confirm" data-sweet-id="" href="">
                        <i class="fas fa-trash"></i> {{__("Delete")}}</a>
                </div>
            </div>
        </div>
    </div>
</div>
