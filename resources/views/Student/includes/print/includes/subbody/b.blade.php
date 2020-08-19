<fieldset class="p-2 border text-sm" style="font-size: 0.871em;border: 1px solid slateblue;color: slateblue;">
    <legend class="w-auto" style="font-size: 16px;font-weight: bold;">(B) {{__('Guardian')}}</legend>
    <table style="width: 100%">
        <tbody>
            <tr>
                <td>
                    <table style="width: 100%">
                        <tbody>
                            <tr>
                                <td><span>{{__('Father fullname')}}</span>
                                </td>
                                <td class="text-{{config('app.theme_color.name')}}">
                                    <strong> {{$row["student_guardian"]["father"]["name"]}}</strong>
                                </td>
                                <td>
                                    <span>{{__('Occupation')}}</span>
                                </td>
                                <td class="text-{{config('app.theme_color.name')}}">

                                    @if ($row["student_guardian"]["father"]["occupation"])
                                    <strong>{{$row["student_guardian"]["father"]["occupation"]}}</strong>
                                    @else
                                    <strong style="color: red">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><span>{{__('Father phone')}}</span>
                                </td>
                                <td class="text-{{config('app.theme_color.name')}}">
                                    @if ($row["student_guardian"]["father"]["phone"])
                                    <strong>{{$row["student_guardian"]["father"]["phone"]}}</strong>
                                    @else
                                    <strong style="color: red">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                                <td>
                                    <span>{{__('Father email')}}</span>
                                </td>
                                <td class="text-{{config('app.theme_color.name')}}">
                                    @if ($row["student_guardian"]["father"]["email"])
                                    <strong>{{$row["student_guardian"]["father"]["email"]}}</strong>
                                    @else
                                    <strong style="color: red">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><span>{{__('Mother fullname')}}</span>
                                </td>
                                <td class="text-{{config('app.theme_color.name')}}">
                                    <strong> {{$row["student_guardian"]["mother"]["name"]}}</strong>
                                </td>
                                <td>
                                    <span>{{__('Occupation')}}</span>
                                </td>
                                <td class="text-{{config('app.theme_color.name')}}">

                                    @if ($row["student_guardian"]["mother"]["occupation"])
                                    <strong>{{$row["student_guardian"]["mother"]["occupation"]}}</strong>
                                    @else
                                    <strong style="color: red">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><span>{{__('Mother phone')}}</span>
                                </td>
                                <td class="text-{{config('app.theme_color.name')}}">
                                    @if ($row["student_guardian"]["mother"]["phone"])
                                    <strong>{{$row["student_guardian"]["mother"]["phone"]}}</strong>
                                    @else
                                    <strong style="color: red">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                                <td>
                                    <span>{{__('Mother email')}}</span>
                                </td>
                                <td class="text-{{config('app.theme_color.name')}}">
                                    @if ($row["student_guardian"]["mother"]["email"])
                                    <strong>{{$row["student_guardian"]["mother"]["email"]}}</strong>
                                    @else
                                    <strong style="color: red">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span>{{__('Guardian')}}</span>
                                </td>
                                @if ($row["student_guardian"]["guardian_is"])
                                <td colspan="3">
                                    <strong>
                                        @if ($row["student_guardian"]["guardian_is"] == 'father')
                                        {{__('Father is guardian')}}
                                        @else
                                        {{__('Mother is guardian')}}
                                        @endif

                                    </strong>
                                </td>
                                @endif

                            </tr>
                            @if (!$row["student_guardian"]["guardian_is"])
                            <tr>
                                <td><span>{{__('Guardian fullname')}}</span>
                                </td>
                                <td class="text-{{config('app.theme_color.name')}}">
                                    <strong> {{$row["student_guardian"]["guardian"]["name"]}}</strong>
                                </td>
                                <td>
                                    <span>{{__('Occupation')}}</span>
                                </td>
                                <td class="text-{{config('app.theme_color.name')}}">

                                    @if ($row["student_guardian"]["guardian"]["occupation"])
                                    <strong>{{$row["student_guardian"]["mother"]["occupation"]}}</strong>
                                    @else
                                    <strong style="color: red">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><span>{{__('Guardian phone')}}</span>
                                </td>
                                <td class="text-{{config('app.theme_color.name')}}">

                                    @if ($row["student_guardian"]["guardian"]["phone"])
                                    <strong>{{$row["student_guardian"]["mother"]["phone"]}}</strong>
                                    @else
                                    <strong style="color: red">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                                <td>
                                    <span>{{__('Guardian email')}}</span>
                                </td>
                                <td class="text-{{config('app.theme_color.name')}}">

                                    @if ($row["student_guardian"]["guardian"]["email"])
                                    <strong>{{$row["student_guardian"]["mother"]["email"]}}</strong>
                                    @else
                                    <strong style="color: red">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                            </tr>
                            @endif


                        </tbody>
                    </table>
                </td>
                <td style="vertical-align: top; text-align: center;width:2%">

                </td>
            </tr>
        </tbody>
    </table>
</fieldset>
