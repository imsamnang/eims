<div class="card-body p-0">
    <div class="table-responsive py-4">
        <table id="datatable-basic" class="table table-flush">
            <thead class="thead-light">
                <tr>
                    <th>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" id="table-check-all" data-toggle="table-checked"
                                data-checked-controls="table-checked"
                                data-checked-show-controls='["view","edit","delete"]' type="checkbox">
                            <label class="custom-control-label" for="table-check-all"></label>
                        </div>
                    </th>
                    <th>
                        {{__("Id")}}​</th>
                    @isset($provinces)
                    <th>{{__("Province")}}​</th>
                    @endisset
                    @isset($districts)
                    <th>{{__("District")}}​</th>
                    @endisset

                    @isset($communes)
                    <th>{{__("Commune")}}​</th>
                    @endisset
                    <th>
                        {{__('Name')}}​</th>
                    <th>{{__("Description")}}​</th>
                    <th></th>

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
                    @isset($provinces)
                    <td>{{$row['province']}}</td>
                    @endisset
                    @isset($districts)
                    <td>{{$row['district']}}</td>
                    @endisset

                    @isset($communes)
                    <td>{{$row['commune']}}</td>
                    @endisset
                    <td>{{$row['name']}}</td>
                    <td>{{$row['description']}}</td>

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
    </div>
</div>
