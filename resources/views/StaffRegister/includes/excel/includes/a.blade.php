
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
            <table class="table table-sm table-bordered ">
                <thead>
                    @foreach ($response["heading"] as $key => $heading)
                    <th>

                        @if($key == 1 || $key == 2 || $key == 3 || $key == 6 || $key == 8)
                        <i class="fas text-green fa-key"></i>
                        @elseif($key == 11 || $key == 12)
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

                        @if($key == 1)
                        <td>
                            <select style="height: 30px" class="border">
                                @foreach ($institute as $o)
                                <option {{$o == $row?"selected" : "" }} value="{{$o}}">{{$o}}</option>
                                @endforeach
                            </select>
                        </td>
                        @elseif($key == 2)
                        <td>
                            <select style="height: 30px" class="border">
                                @foreach ($designation as $o)
                                <option {{$o == $row?"selected" : "" }} value="{{$o}}">{{$o}}</option>
                                @endforeach
                            </select>
                        </td>
                        @elseif($key == 3)
                        <td>
                            <select style="height: 30px" class="border">
                                @foreach ($status as $o)
                                <option {{$o == $row?"selected" : "" }} value="{{$o}}">{{$o}}</option>
                                @endforeach
                            </select>
                        </td>
                        @elseif($key == 6)
                        <td>
                            <select style="height: 30px" class="border">
                                @foreach ($gender as $o)
                                <option {{$o == $row?"selected" : "" }} value="{{$o}}">{{$o}}</option>
                                @endforeach
                            </select>
                        </td>
                        @elseif($key == 8)
                        <td>
                            <select style="height: 30px" class="border">
                                @foreach ($marital as $o)
                                <option {{$o == $row?"selected" : "" }} value="{{$o}}">{{$o}}</option>
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
