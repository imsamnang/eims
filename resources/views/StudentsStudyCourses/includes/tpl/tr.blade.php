<tr data-target="#modal" data-href="{{$row["action"]["view"]}}" data-id="{{$row["id"]}}">
    <td>
        <div class="custom-control custom-checkbox">
            <input class="custom-control-input" data-toggle="table-checked" id="table-check-{{$row["id"]}}"
                data-checked-show-controls='["view","edit","account","photo","qrcode","card","certificate","delete"]'
                type="checkbox" data-checked="table-checked" value="{{$row["id"]}}">
            <label class="custom-control-label" for="table-check-{{$row["id"]}}"></label>
        </div>

    </td>
    <td>{{$row['nid']}}</td>
    <td>{{$row['name']}}</td>
    <td>{{$row['gender']}}</td>
    <td>{{$row['study']}}</td>
    <td>{{$row['study_status']}}</td>
    <td>
        @if ($row['account'])
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
                    href="{{$row["action"]["account"]}}">
                    <i class="fas fa-user"></i> {{__('Create account')}}</a>

                <div class="dropdown-divider"></div>
                <a href="{{$row["action"]["photo"]}}" data-toggle="modal-ajax" data-target="#modal"
                    id="btn-option-photo" class="dropdown-item">
                    <i class="fas fa-portrait "></i>
                    {{__("Photo")}}
                </a>
                <a href="{{$row["action"]["qrcode"]}}" data-toggle="modal-ajax" data-target="#modal"
                    id="btn-option-qrcode" class="dropdown-item">
                    <i class="fas fa-qrcode "></i>
                    {{__("Qrcode")}}
                </a>
                <a href="{{$row["action"]["card"]}}" data-toggle="modal-ajax" data-target="#modal" id="btn-option-card"
                    class="dropdown-item">
                    <i class="fas fa-id-card "></i>
                    {{__("Card")}}
                </a>

                <a href="{{$row["action"]["certificate"]}}" data-toggle="modal-ajax" data-target="#modal"
                    id="btn-option-certificate" class="dropdown-item">
                    <i class="fas fa-file-certificate"></i>
                    {{__("Certificate")}}
                </a>

                <div class="dropdown-divider"></div>

                <a class="dropdown-item" data-toggle="sweet-alert" data-sweet-alert="confirm"
                    data-sweet-id="{{$row["id"]}}" href="{{$row["action"]["delete"]}}"
                    data-sweet-alert-controls-id="{{$row["id"]}}">
                    <i class="fas fa-trash"></i> {{__("Delete")}}</a>
            </div>
        </div>
    </td>
</tr>
