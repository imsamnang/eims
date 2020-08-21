<fieldset class="p-2 border text-sm">
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


                            <tr>
                                <td style="width: 400px">
                                    <span>{{__('Layout')}}</span>
                                </td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    @if ($row['layout'])
                                    <strong>{{__($row['layout'])}}</strong>
                                    @else
                                    <strong>{{__('N/A')}}</strong>
                                    @endif
                                </td>
                                <td style="width: 400px">
                                    <span>{{__('Type')}}</span>
                                </td>
                                <td style="width: 400px" class="text-{{config('app.theme_color.name')}}">

                                    @if ($row['type'])
                                    <strong>{{__($row['type'])}}</strong>
                                    @else
                                    <strong class="text-danger">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td style="width: 400px">
                                    <span>{{__('Description')}}</span>
                                </td>
                                <td colspan="3" style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    @if ($row['description'])
                                    <strong>{{$row['description']}}</strong>
                                    @else
                                    <strong class="text-danger">{{__('N/A')}}</strong>
                                    @endif

                                </td>
                            </tr>
                            <tr>
                                <td style="width: 400px">
                                    <span>{{__('Status')}}</span>
                                </td>
                                <td colspan="3" style="width: 400px">
                                    @if ($row['status'])
                                    <i class="fas fa-check-circle text-green"></i>
                                    @else
                                    <i class="fas fa-times-circle text-red"></i>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td style="width: 400px">
                                    <span>{{__('Created')}}</span>
                                </td>
                                <td colspan="3" style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    @if ($row['created_at'])
                                    <strong>{{$row['created_at']}}</strong>
                                    @else
                                    <strong class="text-danger">{{__('N/A')}}</strong>
                                    @endif

                                </td>
                            </tr>
                            <tr>
                                <td style="width: 400px">
                                    <span>{{__('Updated')}}</span>
                                </td>
                                <td colspan="3" style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    @if ($row['updated_at'])
                                    <strong>{{$row['updated_at']}}</strong>
                                    @else
                                    <strong class="text-danger">{{__('N/A')}}</strong>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="vertical-align: top; text-align: center;">
                    <img data-src="{{$row['foreground']}}" style="width: 100px;">
                </td>
                <td style="vertical-align: top; text-align: center;">
                    <img data-src="{{$row['background']}}" style="width: 100px;">
                </td>
            </tr>
        </tbody>
    </table>
</fieldset>
