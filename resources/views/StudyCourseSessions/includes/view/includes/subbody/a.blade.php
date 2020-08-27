<fieldset class="p-2 border text-sm my-3">
<legend class="w-auto">(A) {{__('Study Course Schedule')}}</legend>
    <table>
        <tbody>
            <tr>
                <td style="width: 200px"><span>{{ __('Study Program') }}</span>
                </td>
                <td style="width: 200px" class="text-{{ config('app.theme_color.name') }}">
                    <strong>{{ $row['study_program'] }}</strong>
                </td>
            </tr>
            <tr>
                <td style="width: 200px"><span>{{ __('Study Course') }}</span>
                </td>
                <td style="width: 200px" class="text-{{ config('app.theme_color.name') }}">
                    <strong>{{ $row['study_course'] }}</strong>
                </td>

            </tr>
            <tr>
                <td style="width: 200px"><span>{{ __('Study Generation') }}</span></td>
                <td style="width: 200px" class="text-{{ config('app.theme_color.name') }}">
                    <strong>{{ $row['study_generation'] }}</strong>
                </td>
            <tr>
                <td style="width: 200px"><span>{{ __('Study Academic Years') }}</span></td>
                <td style="width: 200px" class="text-{{ config('app.theme_color.name') }}">
                    <strong>{{ $row['study_academic_year'] }}</strong>
                </td>
            </tr>
            <tr>
                <td style="width: 200px"><span>{{ __('Study Semester') }}</span></td>
                <td style="width: 200px" class="text-{{ config('app.theme_color.name') }}">
                    <strong>{{ $row['study_semester'] }}</strong>
                </td>
            </tr>
        </tbody>
    </table>
</fieldset>
