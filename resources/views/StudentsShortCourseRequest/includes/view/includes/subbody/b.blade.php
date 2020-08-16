<fieldset class="p-2 border text-sm my-3">
    <legend class="w-auto">(B) {{__('Request study')}}</legend>
    <table>
        <tbody>
            <tr>
                <td>
                    <table>
                        <tbody>

                            <tr>
                                <td style="width: 400px"><span>{{__('Study Generation')}}</span></td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['study_generation']}}</strong>
                                </td>

                            </tr>

                            <tr>
                                <td style="width: 400px"><span>{{__('Study Subjects')}}</span></td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['study_subject']}}</strong>
                                </td>
                                <td style="width: 400px"><span>{{__('Study Session')}}</span></td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['study_session']}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 400px"><span>{{__('Requested Date')}}</span></td>
                                <td colspan="3" style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['created_at']}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 400px"><span>{{__('Status')}}</span></td>
                                <td colspan="3" style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    @if ($row['status'])
                                    <strong>
                                        <i class="fas fa-check-circle text-green"></i>
                                    </strong>
                                    @else
                                    {{-- <i class="fas fa-times"></i> --}}
                                    @endif

                                </td>
                            </tr>

                        </tbody>
                    </table>
                </td>
                <td style="width: 400px"></td>
            </tr>
        </tbody>
    </table>
</fieldset>
