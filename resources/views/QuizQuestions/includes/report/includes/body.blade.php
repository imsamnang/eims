<ol>
    @foreach ($response as $id => $row)
    <li class="dl" title1="{{__('This question can answers ',['answer'=>$row['answer_limit']])}}">
        {{$row['question']}} ({{$row['score']}} {{__('Score')}})
    </li>
    @include(config("pages.parent").".includes.report.includes.subbody.a",[
    'type' => $row['quiz_answer_type_id'],
    'answers' => $row['answers']
    ])
    @endforeach
</ol>
