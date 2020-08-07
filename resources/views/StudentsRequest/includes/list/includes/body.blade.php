<div class="card-body p-0">
    <div class="table-responsive py-4">
        <table
            data-url="{{str_replace("add","list-datatable",config("pages.form.action.add"))}}{{config("pages.search")}}"
            class="table table-flush" data-toggle="datatable-ajax">
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
                    @if (Auth::user()->role_id != 2)
                    <th data-type="text" data-key="institute.short_name" width="1" class="sort" data-sort="institute">
                        {{__("Institute"}}​</th>
                    @endif
                    <th data-type="text" data-key="study_program.name" width="1" class="sort" data-sort="study_program">
                        {{__("Study Program")}}
                    </th>
                    <th data-type="text" data-key="study_course.name" width="1" class="sort" data-sort="study_course">
                        {{__("Study Course")}}
                    </th>
                    {{-- <th data-type="text" data-key="study_generation.name" width="1" class="sort" data-sort="study_generation">
                                {{__("Study Generation")}}
                    </th> --}}
                    <th data-type="text" data-key="study_academic_year.name" width="1" class="sort"
                        data-sort="study_academic_year">
                        {{__("Study Academic year")}}
                    </th>
                    <th data-type="text" data-key="study_semester.name" width="1" class="sort"
                        data-sort="study_semester">
                        {{__("Study Semester")}}
                    </th>
                    <th data-type="text" data-key="study_session.name" width="1" class="sort" data-sort="study_session">
                        {{__("Study Session")}}
                    </th>
                    <th data-type="text" data-key="status" width="1" class="sort" data-sort="status">
                        {{__("Status")}}
                    </th>
                    <th width=1 data-type="image" data-key="photo">{{__("Photo")}}​</th>
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
                    <a data-toggle="modal-ajax" data-target="#modal" id="btn-option-view" class="dropdown-item" href="">
                        <i class="fas fa-eye"></i> {{__("View")}}
                    </a>

                    <a data-toggle="modal-ajax" data-target="#modal" id="btn-option-edit" class="dropdown-item">
                        <i class="fas fa-edit"></i>
                        {{__("Edit")}}
                    </a>

                    <a data-toggle="modal-ajax" data-target="#modal" id="btn-option-approve" class="dropdown-item">
                        <i class="fas fa-check-circle"></i>
                        {{__("Approve")}}
                    </a>

                    <div class="dropdown-divider"></div>

                    <a class="d-none dropdown-item sweet-alert-reload" data-toggle="sweet-alert" id="btn-option-delete"
                        data-sweet-alert="confirm" data-sweet-id="" href="">
                        <i class="fas fa-trash"></i> {{__("Delete")}}</a>
                </div>
            </div>
        </div>
    </div>
</div>
