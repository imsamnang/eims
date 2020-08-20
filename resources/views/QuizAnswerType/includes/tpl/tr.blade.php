<tr data-target="#modal" data-href="{{$row["action"]["view"]}}" data-id="{{$row["id"]}}">
    <td>
        <div class="custom-control custom-checkbox">
            <input class="custom-control-input" data-toggle="table-checked" id="table-check-{{$row["id"]}}"
                data-checked-show-controls='["view","edit","account","delete"]' type="checkbox"
                data-checked="table-checked" value="{{$row["id"]}}">
            <label class="custom-control-label" for="table-check-{{$row["id"]}}"></label>
        </div>

    </td>
    <td>{{$row['nid']}}</td>
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
                    <i class="fas fa-eye"></i>
                    {{__("View")}}
                </a>
            </div>
        </div>
    </td>
</tr>
