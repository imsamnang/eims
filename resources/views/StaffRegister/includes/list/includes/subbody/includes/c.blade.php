
<tr>
    <td>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>
                        {{$study["name"]}}
                    </th>
                </tr>
                    @if ($study['child'])
                        @foreach ($study['child'] as $study)
                            @if (array_key_exists("child",$study))
                                @include("Staff.includes.list.includes.subbody.includes.c",$study)
                            @endif
                        @endforeach
                    @endif
            </tbody>
        </table>
    </td>
</tr>
