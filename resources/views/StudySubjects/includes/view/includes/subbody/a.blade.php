<fieldset class="p-2 border text-sm">
    <legend class="w-auto">(A)</legend>
    <table>
        <tbody>
            <tr>
                <td>
                    <table>
                        <tbody>
                            <tr>
                                <td style="width: 400px"><span>{{__('Id')}}</span>
                                </td>
                                <td colspan="3" style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['id']}}</strong>
                                </td>
                            </tr>

                            <tr>
                                <td style="width: 400px">
                                    <span>{{__('Name')}}</span>
                                </td>
                                <td colspan="3" style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['name']}}</strong>
                                </td>
                            </tr>
                            @if (config('app.languages'))
                            @foreach (config('app.languages') as $lang)

                            <tr>
                                <td style="width: 400px">
                                    <span> {{ __($lang["translate_name"]) }}</span>
                                </td>
                                <td colspan="3" style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row[$lang["code_name"]]}}</strong>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            <tr>
                                <td style="width: 400px">
                                    <span>{{__('Course Type')}}</span>
                                </td>
                                <td colspan="3" style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['course_type']}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 400px">
                                    <span>{{__('Full mark theory')}}</span>
                                </td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['full_mark_theory'].' '.__('Points')}}</strong>
                                </td>
                                <td style="width: 400px">
                                    <span>{{__('Pass mark theory')}}</span>
                                </td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['pass_mark_theory'].' '.__('Points')}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 400px">
                                    <span>{{__('Full mark practical')}}</span>
                                </td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['full_mark_practical'].' '.__('Points')}}</strong>
                                </td>
                                <td style="width: 400px">
                                    <span>{{__('Pass mark practical')}}</span>
                                </td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['pass_mark_practical'].' '.__('Points')}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 400px">
                                    <span>{{__('Description')}}</span>
                                </td>
                                <td colspan="3" style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['description']}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 400px">
                                    <span>{{__('Created')}}</span>
                                </td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['created_at']}}</strong>
                                </td>
                                <td style="width: 400px">
                                    <span>{{__('Updated')}}</span>
                                </td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['updated_at']}}</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="vertical-align: top; text-align: center;">
                    <img data-src="{{$row['image']}}" style="width: 100px;">
                </td>
            </tr>
        </tbody>
    </table>
</fieldset>
