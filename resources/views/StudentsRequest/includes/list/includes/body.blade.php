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

                    <th width=1 data-type="text" data-key="name">
                        {{__("Name")}}​
                    </th>
                    <th width=1 data-type="text" data-key="gender">
                        {{__("Gender")}}​
                    </th>


                    <th data-type="text" data-key="study_program" width="1" class="sort" data-sort="study_program">
                        {{__("Study Program")}}
                    </th>
                    <th data-type="text" data-key="study_course" width="1" class="sort" data-sort="study_course">
                        {{__("Study Course")}}
                    </th>
                    <th data-type="text" data-key="study_generation" width="1" class="sort"
                        data-sort="study_generation">
                        {{__("Study Generation")}}
                    </th>
                    <th data-type="text" data-key="study_academic_year" width="1" class="sort"
                        data-sort="study_academic_year">
                        {{__("Study Academic year")}}
                    </th>
                    <th data-type="text" data-key="study_semester" width="1" class="sort" data-sort="study_semester">
                        {{__("Study Semester")}}
                    </th>
                    <th data-type="text" data-key="study_session" width="1" class="sort" data-sort="study_session">
                        {{__("Study Session")}}
                    </th>
                    <th data-type="text" data-key="status" width="1" class="sort" data-sort="status">
                        {{__("Status")}}
                    </th>

                    <th width=1 data-type="option" data-key="view,edit,approve">
                    </th>

                </tr>
            </thead>
            <tbody>
                @foreach ($response['data'] as $id => $row)
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
                    <td>{{$row['gender']}}</td>
                    <td>{{$row['study_program']}}</td>
                    <td>{{$row['study_course']}}</td>
                    <td>{{$row['study_generation']}}</td>
                    <td>{{$row['study_academic_year']}}</td>
                    <td>{{$row['study_semester']}}</td>
                    <td>{{$row['study_session']}}</td>
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

                                <a data-toggle="modal-ajax" data-target="#modal" class="dropdown-item"
                                    href="{{$row["action"]["approve"]}}">
                                    <i class="fas fa-check-circle"></i> {{__('Approve')}}</a>

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
