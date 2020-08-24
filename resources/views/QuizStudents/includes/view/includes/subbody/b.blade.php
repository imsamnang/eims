<div class="card-body">
    <div class="table-responsive">
        <table class="table border">
            <thead>
                <tr>
                    <th>{{__("Id")}}​</th>
                    <th>{{__("Questions & Answered")}}​</th>
                    <th>{{__('Score')}}​</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($row['quiz_answered'] as $id => $q)

                <tr>
                    <td>{{ $id + 1 }}</td>
                    <td>
                        <div>
                            <span class="text-red">{{__("Question")}} :​</span>
                            <span class="ml-2 text-pre-wrap text-break">{{ $q['questions']['question']}}
                            </span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex mb-3">
                            <span class="text-yellow">{{__("Answer")}}​ :</span>
                            @if ($q['questions']["quiz_answer_type_id"] == 1)

                            @foreach ($q["answers"] as $ans)
                            <div class="custom-control custom-radio mx-2">
                                <input disabled {{$ans["correct_answer"] ? "checked" : ""}} type="radio"
                                    class="custom-control-input position-absolute">
                                <label class="custom-control-label">{{$ans["answer"]}}</label>
                            </div>

                            @endforeach
                            @elseif ($q['questions']["quiz_answer_type_id"] == 2)
                            <div data-toggle="checkbox-limit1" data-limit="{{$q['answer_limit']}}">
                                <div>
                                    {{__("This question can answers ",['answer'=>$q['answer_limit']." ".__('answer')])}}
                                </div>
                                @foreach ($q["answers"] as $ans)
                                <div class="custom-control custom-checkbox">
                                    <input disabled {{$ans["correct_answer"] ? "checked" : ""}} type="checkbox"
                                        value="{{$ans["id"]}}" name="answer[]"
                                        class="custom-control-input position-absolute">
                                    <label class="custom-control-label">{{$ans["answer"]}}</label>
                                </div>
                                @endforeach
                            </div>
                            @elseif ($q['questions']["quiz_answer_type_id"] == 3)
                            <span class="ml-2 text-pre-wrap text-break">{{ $q['answers'][0]['answer']}}
                            </span>
                            @endif

                        </div>
                        <div class="d-flex mb-3">
                            <span class="text-green">{{__("Answered")}} :​</span>

                            @if ($q['questions']["quiz_answer_type_id"] == 1)
                            @foreach ($q["answers"] as $ans)
                            <div class="custom-control custom-radio mx-2">
                                <input disabled {{in_array($ans["id"],explode(",",$q['answered'])) ? "checked"  : "" }}
                                    type="radio" class="custom-control-input position-absolute">
                                <label class="custom-control-label">{{$ans["answer"]}}</label>
                            </div>
                            @endforeach
                            @elseif ($q['questions']["quiz_answer_type_id"] == 2)
                            <div data-toggle="checkbox-limit1" data-limit="{{$q["answer_limit"]}}">
                                <div>
                                    {{__("This question can answers ",['answer'=>$q["answer_limit"]." ".__('answer')])}}
                                </div>
                                @foreach ($q["answers"] as $ans)
                                <div class="custom-control custom-checkbox">
                                    <input disabled
                                        {{in_array($ans["id"],explode(",",$q['answered'])) ? "checked"  : "" }}
                                        type="checkbox" value="{{$ans["id"]}}" name="answer[]"
                                        class="custom-control-input position-absolute">
                                    <label class="custom-control-label">{{$ans["answer"]}}</label>
                                </div>
                                @endforeach
                            </div>
                            @elseif ($q['questions']["quiz_answer_type_id"] == 3)
                            <span class="form-control text-pre-wrap text-break">{{$q['answered']}}</span>
                            @endif

                        </div>
                        <form role="update" class="needs-validation" novalidate="" method="POST"
                            action="{{str_replace("add","score/update",config('pages.form.data.'.$key.'.action.edit',config("pages.form.action.detect")))}}"
                            id="form-quiz-answer" enctype="multipart/form-data"
                            data-validation="{{json_encode(config("pages.form2.validate"))}}">
                            <div class="d-flex mb-3">
                                @csrf
                                <input type="hidden" name="id" value="{{$q['id']}}">
                                <span class="text-blue">{{__("Scored")}} :​</span>
                                <div class="form-row">
                                    <div class="col">
                                        <span class="form-control form-control-sm ml-2"
                                            max="{{ $q['questions']['score']}}" type="number" name="score"
                                            id="score">{{$q['score'] ? $q['score'] :$q['correct_marks']}}</span>
                                    </div>
                                    <div class="col">
                                        <div class="ml-1">
                                            <button class="btn btn-danger btn-sm d-none" id="cancel" type="button"
                                                data-attr-remove="style" data-control="score" data-change-tag="span">
                                                <i class="fas fa-times-circle"></i>
                                                {{__("Cancel")}}
                                            </button>
                                            <button class="btn btn-primary btn-sm" id="edit" type="button"
                                                data-attr-remove="style" data-control="score" data-change-tag="input">
                                                <i class="fas fa-edit"></i>
                                                {{__("Edit")}}
                                            </button>
                                            <button class="btn btn-primary btn-sm d-none"
                                                type="submit">{{__("Update")}}</button>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </form>
                        @php
                        $validate = [
                        'rules' => [],
                        'attributes' => [],
                        'messages' => [],
                        'questions' => [],
                        ];
                        @endphp
                        <form
                            action="{{str_replace("add","answer_again",config('pages.form.data.'.$key.'.action.edit',config("pages.form.action.detect")))}}"
                            method="POST" id="form-quiz-answer" data-validate='{{json_encode($validate)}}'>
                            @csrf
                            <input type="hidden" name="id" value="{{$q['id']}}">
                            <button class="btn btn-info btn-sm" type="submit">{{__("Answer again")}}</button>
                        </form>

                    </td>
                    <td>
                        <span> {{ $q['questions']['score']}} </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="card-footer">
    @if (count($response['data']) > 1)
    <form method="POST" action="{{str_replace('view','auto-score',config('pages.form.action.view'))}}">
        @csrf
        <input type="hidden" name="quiz" value="{{$row['quiz_id']}}">
        <button type="submit" class="btn">{{__('Automatic all scoring')}}</button>

    </form>
    @endif
    @if (count($row['quiz_answered']))
    <form method="POST" action="{{$row['action']['auto-score']}}">
        @csrf
        <input type="hidden" name="quiz" value="{{$row['quiz_id']}}">
        <button type="submit" class="btn btn-primary">{{__('Automatic scoring')}}</button>
    </form>
    @endif


</div>
