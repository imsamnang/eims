<table class="table table-xs table-bordered">
    <tbody>
        <tr>
            <th colspan="2">{{ __("Address") }}</th>
        </tr>
        <tr>
            <td>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>{{ __("place_of_birth") }}</th>
                        </tr>
                        <tr>
                            <td>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>{{ __("Province") }}</th>
                                            <th>{{ __("District") }}</th>
                                            <th>{{ __("Commune") }}</th>
                                            <th>{{ __("Village") }}</th>
                                        </tr>
                                        <tr>
                                            <td>{{$row["place_of_birth"]["province"]["name"]}}</td>
                                            <td>{{$row["place_of_birth"]["district"]["name"]}}</td>
                                            <td>{{$row["place_of_birth"]["commune"]["name"]}}</td>
                                            <td>{{$row["place_of_birth"]["village"]["name"]}}</td>
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
                            <th>{{ __("Current resident") }}</th>
                        </tr>
                        <tr>
                            <td>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>{{ __("Province") }}</th>
                                            <th>{{ __("District") }}</th>
                                            <th>{{ __("Commune") }}</th>
                                            <th>{{ __("Village") }}</th>
                                        </tr>
                                        <tr>
                                            <td>{{$row["current_resident"]["province"]["name"]}}</td>
                                            <td>{{$row["current_resident"]["district"]["name"]}}</td>
                                            <td>{{$row["current_resident"]["commune"]["name"]}}</td>
                                            <td>{{$row["current_resident"]["village"]["name"]}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>{{ __("Permanent address") }}</td>
                            <td class="text-center text-wrap"> {{$row["permanent_address"]}} </td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>{{ __("Temporaray address") }}</td>
                            <td class="text-center text-wrap"> {{$row["temporaray_address"]}} </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
