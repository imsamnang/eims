<fieldset class="p-2 border text-sm my-3">
    <legend class="w-auto">(B) {{__('Biography')}}</legend>
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
                                    <strong>{{$row['staff']['first_name_km']}}</strong>
                                </td>
                                <td style="width: 400px">
                                    <span>{{__('Last name Khmer')}}</span>
                                </td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['staff']['last_name_km']}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 400px"><span>{{__('First name Latin')}}</span>
                                </td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['staff']['first_name_en']}}</strong>
                                </td>
                                <td style="width: 400px">
                                    <span>{{__('Last name Latin')}}</span>
                                </td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['staff']['last_name_en']}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 400px"><span>{{__('Gender')}}</span></td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    @if ($row['staff']['gender'])
                                    <strong>{{$row['staff']['gender']}}</strong>
                                    @else
                                    <strong class="text-red">{{__('N/A')}}</strong>
                                    @endif

                                </td>

                            </tr>

                            <tr>
                                <td style="width: 400px"><span>{{__('Phone')}}</span></td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">

                                    @if ($row['staff']['phone'])
                                    <strong>{{$row['staff']['phone']}}</strong>
                                    @else
                                    <strong class="text-red">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                                <td style="width: 400px"><span>{{__('Email')}}</span></td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">

                                    @if ($row['staff']['email'])
                                    <strong>{{$row['staff']['email']}}</strong>
                                    @else
                                    <strong class="text-red">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="vertical-align: top; text-align: center;">
                    <img data-src="{{$row['staff']['photo']}}" style="width: 100px;">
                </td>
            </tr>
        </tbody>
    </table>
</fieldset>
