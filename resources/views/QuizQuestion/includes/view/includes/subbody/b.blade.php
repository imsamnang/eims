<fieldset class="p-2 border text-sm my-3">
    <legend class="w-auto">(B) {{__('Answer')}}</legend>
    <div class="table-responsive py-3">
        <table class="table">
            <th width=1>{{__('Id')}}</th>
            <th>{{__('Answer')}}</th>
            <th width=1>{{__('Correct answer​')}}</th>
            <tbody>
                @foreach ($row['answers'] as $key => $ans)
                <tr>
                    <td>{{$key + 1}}</td>
                    <td>{{$ans['answer']}}</td>
                    <td>
                        @if ($ans['correct_answer'])
                        <i class="fas fa-check-circle text-green"></i>
                        @else

                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</fieldset>
