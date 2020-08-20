<div class="card-body p-0">
    <div class="table-responsive py-4">
        <table class="table table-flush" data-toggle="datatable-ajax">
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
                        {{__("Phrase")}}​</th>
                    @if (config('app.languages'))
                    @foreach (config('app.languages') as $lang)
                    <th> {{$lang['translate_name']}}​</th>
                    @endforeach
                    @endif
                    <th></th>

                </tr>
            </thead>
        </table>

    </div>
</div>
