<table class="table table-xs table-bordered">
    <tbody>
        <tr>
            <th>{{ __("Guardian") }}</th>

        </tr>
        <tr>
            <td>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>{{ __("father") }}</th>
                        </tr>
                        <tr>
                            <td>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>{{ __("Name") }}</th>
                                            <th>{{ __("Occupation") }}</th>
                                            <th>{{ __("Phone") }}</th>
                                            <th>{{ __("Email") }}</th>
                                        </tr>
                                        <tr>
                                            <td>{{$row["student_guardian"]["father"]["name"]}}</td>
                                            <td>{{$row["student_guardian"]["father"]["occupation"]}}</td>
                                            <td>{{$row["student_guardian"]["father"]["phone"]}}</td>
                                            <td>{{$row["student_guardian"]["father"]["email"]}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __("Extra info") }}</td>
                                            <td colspan="4" class="text-center text-wrap">
                                                {{$row["student_guardian"]["father"]["extra_info"]}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>{{ __("mother") }}</th>
                        </tr>
                        <tr>
                            <td>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>{{ __("Name") }}</th>
                                            <th>{{ __("Occupation") }}</th>
                                            <th>{{ __("Phone") }}</th>
                                            <th>{{ __("Email") }}</th>
                                        </tr>
                                        <tr>
                                            <td>{{$row["student_guardian"]["mother"]["name"]}}</td>
                                            <td>{{$row["student_guardian"]["mother"]["occupation"]}}</td>
                                            <td>{{$row["student_guardian"]["mother"]["phone"]}}</td>
                                            <td>{{$row["student_guardian"]["mother"]["email"]}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __("Extra info") }}</td>
                                            <td colspan="4" class="text-center text-wrap">
                                                {{$row["student_guardian"]["mother"]["extra_info"]}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>{{ __("Guardian") }}</th>
                        </tr>
                        <tr>

                            @if ($row["student_guardian"]["guardian_is"] == "father")
                            <td> {{ __("Father is guardian") }} </td>
                            @elseif ($row["student_guardian"]["guardian_is"] == "mother")
                            <td> {{ __("Mother is guardian") }} </td>
                            @else
                            <td>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>{{ __("Name") }}</th>
                                            <th>{{ __("Occupation") }}</th>
                                            <th>{{ __("Phone") }}</th>
                                            <th>{{ __("Email") }}</th>
                                        </tr>
                                        <tr>
                                            <td>{{$row["student_guardian"]["guardian"]["name"]}}</td>
                                            <td>{{$row["student_guardian"]["guardian"]["occupation"]}}</td>
                                            <td>{{$row["student_guardian"]["guardian"]["phone"]}}</td>
                                            <td>{{$row["student_guardian"]["guardian"]["email"]}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __("Extra info") }}</td>
                                            <td colspan="4" class="text-center text-wrap">
                                                {{$row["student_guardian"]["guardian"]["extra_info"]}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            @endif

                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>


    </tbody>
</table>
