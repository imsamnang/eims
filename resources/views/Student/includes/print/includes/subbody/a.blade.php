<fieldset class="p-2 border text-sm" style="font-size: 0.871em;border: 1px solid slateblue;color: slateblue;">
    <legend class="w-auto" style="font-size: 16px;font-weight: bold;">(A) {{__('Biography')}}</legend>
    <table style="width: 100%">
        <tbody>
            <tr>
                <td>
                    <table style="width: 100%">
                        <tbody>
                            <tr>
                                <td><span>{{__('First name Khmer')}}</span>
                                </td>
                                <td class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['first_name_km']}}</strong>
                                </td>
                                <td>
                                    <span>{{__('Last name Khmer')}}</span>
                                </td>
                                <td class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['last_name_km']}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td><span>{{__('First name Latin')}}</span>
                                </td>
                                <td class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['first_name_en']}}</strong>
                                </td>
                                <td>
                                    <span>{{__('Last name Latin')}}</span>
                                </td>
                                <td class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['last_name_en']}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td><span>{{__('Gender')}}</span></td>
                                <td class="text-{{config('app.theme_color.name')}}">
                                    @if ($row['gender'])
                                    <strong>{{$row['gender']}}</strong>
                                    @else
                                    <strong style="color: red">{{__('N/A')}}</strong>
                                    @endif

                                </td>
                                <td><span>{{__('Date of birth')}}</span></td>
                                <td class="text-{{config('app.theme_color.name')}}">

                                    @if ($row['date_of_birth'])
                                    <strong>{{$row['date_of_birth']}}</strong>
                                    @else
                                    <strong style="color: red">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><span>{{__('National Id')}}</span></td>
                                <td class="text-{{config('app.theme_color.name')}}">

                                    @if ($row['national_id'])
                                    <strong>{{$row['national_id']}}</strong>
                                    @else
                                    <strong style="color: red">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><span>{{__('Marital')}}</span></td>
                                <td class="text-{{config('app.theme_color.name')}}">
                                    @if ($row['marital'])
                                    <strong>{{$row['marital']}}</strong>
                                    @else
                                    <strong style="color: red">{{__('N/A')}}</strong>
                                    @endif

                                </td>
                            </tr>

                            <tr>
                                <td><span>{{__('Permanent address')}}</span></td>
                                <td colspan="3" class="text-{{config('app.theme_color.name')}}">

                                    @if ($row['permanent_address'])
                                    <strong>{{$row['permanent_address']}}</strong>
                                    @else
                                    <strong style="color: red">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><span>{{__('Temporaray address')}}</span></td>
                                <td colspan="3" class="text-{{config('app.theme_color.name')}}">
                                    @if ($row['temporaray_address'])
                                    <strong>{{$row['temporaray_address']}}</strong>
                                    @else
                                    <strong style="color: red">{{__('N/A')}}</strong>
                                    @endif

                                </td>
                            </tr>

                            <tr>
                                <td><span>{{__('Phone')}}</span></td>
                                <td class="text-{{config('app.theme_color.name')}}">

                                    @if ($row['phone'])
                                    <strong>{{$row['phone']}}</strong>
                                    @else
                                    <strong style="color: red">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                                <td><span>{{__('Email')}}</span></td>
                                <td class="text-{{config('app.theme_color.name')}}">

                                    @if ($row['email'])
                                    <strong>{{$row['email']}}</strong>
                                    @else
                                    <strong style="color: red">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="vertical-align: top; text-align: center;width:2%">
                    <img src="{{$row['photo']}}" style="width: 100px;">
                </td>
            </tr>
        </tbody>
    </table>
</fieldset>
