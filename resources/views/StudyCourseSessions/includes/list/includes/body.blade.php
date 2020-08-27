<div class="card-body p-0">
    <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-basic">
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
                        {{__("Study Course")}}​</th>
                    <th>{{__("Study Session")}}​
                    </th>
                    <th>
                        {{__("Study start & Study end")}}​</th>
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
