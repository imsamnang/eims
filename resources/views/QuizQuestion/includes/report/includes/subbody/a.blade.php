<ol type="a" class="{{$type == 1 || $type == 2  ?  "ol-flex" : ""}}">
    @foreach ($answers as $ans)
    <li>
        @if ($type == 1)
        <input type="radio" name="answer" id="answer-{{$id}}">
        {{$ans['answer']}}
        @elseif($type == 2)
        <input type="checkbox" name="answer" id="answer-{{$id}}">
        {{$ans['answer']}}
        @elseif($type == 3)
        <div class="dot">
            <span style="visibility: hidden;">{{__('Answer')}}</span>
        </div>
        <div class="dot"></div>
        <div class="dot"></div>
        @endif

    </li>
    @endforeach
</ol>
