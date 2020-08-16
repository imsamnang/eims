<fieldset class="p-2 border text-sm">
    <legend class="w-auto">(A) {{__('Biography')}}</legend>
    <table>
        <tbody>
            <tr>
                <td>
                    <table>
                        <tbody>
                            <tr>
                                <td style="width: 400px"><span>{{__('First name Khmer')}}</span>
                                </td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['first_name_km']}}</strong>
                                </td>
                                <td style="width: 400px">
                                    <span>{{__('Last name Khmer')}}</span>
                                </td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['last_name_km']}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 400px"><span>{{__('First name Latin')}}</span>
                                </td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['first_name_en']}}</strong>
                                </td>
                                <td style="width: 400px">
                                    <span>{{__('Last name Latin')}}</span>
                                </td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['last_name_en']}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 400px"><span>{{__('Gender')}}</span></td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    @if ($row['gender'])
                                    <strong>{{$row['gender']}}</strong>
                                    @else
                                    <strong class="text-red">{{__('N/A')}}</strong>
                                    @endif

                                </td>
                                <td style="width: 400px"><span>{{__('Date of birth')}}</span></td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">

                                    @if ($row['date_of_birth'])
                                    <strong>{{$row['date_of_birth']}}</strong>
                                    @else
                                    <strong class="text-red">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 400px"><span>{{__('National Id')}}</span></td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">

                                    @if ($row['national_id'])
                                    <strong>{{$row['national_id']}}</strong>
                                    @else
                                    <strong class="text-red">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 400px"><span>{{__('Marital')}}</span></td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    @if ($row['marital'])
                                    <strong>{{$row['marital']}}</strong>
                                    @else
                                    <strong class="text-red">{{__('N/A')}}</strong>
                                    @endif

                                </td>
                            </tr>

                            <tr>
                                <td style="width: 400px"><span>{{__('Permanent address')}}</span></td>
                                <td colspan="3" style="width: 400px" class="text-{{config('app.theme_color.name')}}">

                                    @if ($row['permanent_address'])
                                    <strong>{{$row['permanent_address']}}</strong>
                                    @else
                                    <strong class="text-red">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 400px"><span>{{__('Temporaray address')}}</span></td>
                                <td colspan="3" style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    @if ($row['temporaray_address'])
                                    <strong>{{$row['temporaray_address']}}</strong>
                                    @else
                                    <strong class="text-red">{{__('N/A')}}</strong>
                                    @endif

                                </td>
                            </tr>

                            <tr>
                                <td style="width: 400px"><span>{{__('Phone')}}</span></td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">

                                    @if ($row['phone'])
                                    <strong>{{$row['phone']}}</strong>
                                    @else
                                    <strong class="text-red">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                                <td style="width: 400px"><span>{{__('Email')}}</span></td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">

                                    @if ($row['email'])
                                    <strong>{{$row['email']}}</strong>
                                    @else
                                    <strong class="text-red">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="vertical-align: top; text-align: center;">
                    <img data-src="{{$row['photo']}}" style="width: 100px;">
                </td>
            </tr>
        </tbody>
    </table>
</fieldset>
