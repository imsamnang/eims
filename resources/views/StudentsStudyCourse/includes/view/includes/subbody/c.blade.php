<fieldset class="p-2 border text-sm my-3">
    <legend class="w-auto">(C)</legend>
    <table>
        <tbody>
            <tr>
                <td class="text-{{config('app.theme_color.name')}}">
                    @if ($row['photo_crop'])
                    <img src="{{$row['photo_crop']}}" class="border">
                    @endif
                    @if ($row['qrcode'])
                    <img src="{{$row['qrcode']}}" class="border">
                    @endif
                </td>
            </tr>
            <tr>
                @if ($row['card'])
                <td class="text-{{config('app.theme_color.name')}}">
                    <img src="{{$row['card']}}?type=original" class="w-100 border">
                </td>
                @endif
            </tr>
            @if ($row['certificate'])
            <tr>
                <td class="text-{{config('app.theme_color.name')}}">
                    <img src="{{$row['certificate']}}?type=original" class="w-100 border">
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</fieldset>
