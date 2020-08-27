<fieldset class="p-2 border text-sm my-3">
    <legend class="w-auto">(B) {{__('Study Course Session')}}</legend>
    <table>
        <tbody>
            <tr>
                <td style="width: 200px"><span>{{ __('Id') }}</span>
                </td>
                <td style="width: 200px" class="text-{{ config('app.theme_color.name') }}">
                    <strong>{{ $row['id'] }}</strong>
                </td>
            </tr>
            <tr>
                <td style="width: 200px"><span>{{ __('Study Session') }}</span></td>
                <td style="width: 200px" class="text-{{ config('app.theme_color.name') }}">
                    <strong>{{ $row['study_session'] }}</strong>
                </td>
            </tr>
            <tr>
                <td style="width: 200px"><span>{{ __('Study start') }}</span></td>
                <td style="width: 200px" class="text-{{ config('app.theme_color.name') }}">
                    <strong>{{ $row['study_start'] }}</strong>
                </td>
            </tr>
            <tr>
                <td style="width: 200px"><span>{{ __('Study end') }}</span></td>
                <td style="width: 200px" class="text-{{ config('app.theme_color.name') }}">
                    <strong>{{ $row['study_end'] }}</strong>
                </td>
            </tr>
            <tr>
                <td style="width: 200px"><span>{{ __('Created') }}</span></td>
                <td style="width: 200px" class="text-{{ config('app.theme_color.name') }}">
                    <strong>{{ $row['created_at'] }}</strong>
                </td>
            </tr>
            <tr>
                <td style="width: 200px"><span>{{ __('Updated') }}</span></td>
                <td style="width: 200px" class="text-{{ config('app.theme_color.name') }}">
                    <strong>{{ $row['updated_at'] }}</strong>
                </td>
            </tr>


        </tbody>
    </table>
</fieldset>
