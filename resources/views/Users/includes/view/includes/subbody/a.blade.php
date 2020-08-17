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
                                <td style="width: 400px"><span>{{__('Node Id')}}</span>
                                </td>
                                <td colspan="3" style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['node_id']}}</strong>
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
                                    <span>{{__('Email')}}</span>
                                </td>
                                <td colspan="3" style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['email']}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 400px">
                                    <span>{{__('Phone')}}</span>
                                </td>
                                <td colspan="3" style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['phone']}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 400px">
                                    <span>{{__('Address')}}</span>
                                </td>
                                <td colspan="3" style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['address']}}</strong>
                                </td>
                            </tr>

                            <tr>
                                <td style="width: 400px">
                                    <span>{{__('Role')}}</span>
                                </td>
                                <td colspan="3" style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['role']}}</strong>
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
                            <tr>
                                <td style="width: 400px">
                                    <span>{{__('Status')}}</span>
                                </td>
                                <td colspan="3" style="width: 400px" class="text-{{config('app.theme_color.name')}}">
                                    <strong>{{$row['status']}}</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="vertical-align: top; text-align: center;">
                    <img data-src="{{$row['profile']}}" style="width: 100px;">
                </td>
            </tr>
        </tbody>
    </table>
</fieldset>
