<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-xs" style="width: 100%;">

            <thead class="thead-light">
                <tr class="{{$group == 0 ?'':'invisible' }} ">
                    <th>{{__('Id')}}</th>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Other')}}</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($response as $id => $row)
                <tr>
                    <td>{{$id + 1}}</td>
                    <td>{{$row['name']}}</td>
                    <td></td>
                </tr>
                @endforeach

            </tbody>
        </table>

        @if ($last)
        <table class="table table-bordered table-hover table-xs">

            <tr>
                <td> {{__('Total')}}</td>
                <td> {{$total}}</td>
            </tr>

        </table>

        <div style="font-size: 13px">
            <div style="float: right">
                @if (app()->getLocale() == 'km')

                <span>ថ្ងែ <strong>{{$date['_day']}}</strong> ទី <strong>{{$date['day']}}</strong> ខែ
                    <strong>{{$date['month']}}</strong> ឆ្នាំ <strong>{{$date['year']}}</strong> </span>
                @else
                <span>{{__('Date')}} {{$date['def']}}</span>
                @endif

            </div>
        </div>
        @endif

    </div>
</div>
