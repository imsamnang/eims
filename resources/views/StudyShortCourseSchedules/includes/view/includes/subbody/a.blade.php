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
                                    <span>{{__('Study Generation')}}</span>
                                </td>
                                <td colspan="3" style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['study_generation']}}</strong>
                                </td>
                            </tr>
                            
                            <tr>
                                <td style="width: 400px">
                                    <span>{{__('Study Subjects')}}</span>
                                </td>
                                <td colspan="3" style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['study_subjects']}}</strong>
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
                    
                </td>
            </tr>
        </tbody>
    </table>
</fieldset>
