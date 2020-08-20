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

                    <th>
                        {{__("Name")}}​
                    </th>
                    <th>
                        {{__("Layout")}}​
                    </th>
                    <th>
                        {{__("Description")}}​
                    </th>
                    <th>
                        {{__("Status")}}​
                    </th>
                    <th></th>
                </tr>

            </thead>
            <tbody>
                @foreach ($response['data'] as $row)
                @include(config("pages.parent").".includes.tpl.tr",['row'=>$row])
                @endforeach
            </tbody>
        </table>

    </div>
</div>
