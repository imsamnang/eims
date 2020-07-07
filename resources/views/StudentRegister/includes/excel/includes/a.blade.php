<style>
    .table-xs td,
    .table-xs th {
        border: 1px solid #ccc;
        padding: .3rem .07rem !important;
        font-size: .8rem !important;
        text-align: center;
    }

    .table-xs td,
    .table-xs th {
        vertical-align: middle;

    }
</style>
<div class="card">
    <div class="card-header p-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            (A) គំរូ
        </label>
        <a class="float-right" href="{{str_replace("/add","excel/template",config("pages.form.action.add"))}}">
            ទាញយកគំរូ
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-xs table-bordered ">
                <thead>
                    @foreach ($response["heading"] as $key => $heading)
                    <th>

                        @if($key == 3 || $key == 5)
                        <i class="fas text-green fa-key"></i>
                        @elseif($key == 8 || $key == 9)
                        <i class="fas text-red fa-key"></i>
                        @endif
                        {{$heading}}
                    </th>
                    @endforeach
                </thead>
                <tbody>

                    @foreach ($response["data"] as $columns)
                    <tr>
                        @foreach (array_values($columns)  as $key => $row)

                        @if($key == 3)
                        <td>
                            <select class="form-control">
                                @foreach ($gender as $o)
                                <option value="{{$o}}">{{$o}}</option>
                                @endforeach
                            </select>
                        </td>
                        @elseif($key == 5)
                        <td>
                            <select class="form-control">
                                @foreach ($marital as $o)
                                <option value="{{$o}}">{{$o}}</option>
                                @endforeach
                            </select>
                        </td>

                        @else
                        <td>{{$row}}</td>
                        @endif
                        @endforeach
                    </tr>


                    @endforeach


                </tbody>
            </table>
        </div>
    </div>
</div>
