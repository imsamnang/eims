<div class="tab-pane fade show active" id="a" role="tabpanel" aria-labelledby="a-tab">
    <div class="row">
        <div class="col-10">
            <div class="form-group row">
                <label class="col-sm-3 px-0 col-form-label">{{__("First name Khmer")}}</label>
                <div class="col-sm-3 px-0">
                    <div class="font-weight-bold form-control-plaintext">
                        {{$row["first_name_km"]}}</div>
                </div>

                <label class="col-sm-3 px-0 col-form-label">{{__("Last name Khmer")}}</label>
                <div class="col-sm-3 px-0">
                    <div class="font-weight-bold form-control-plaintext">
                        {{$row["last_name_km"]}}</div>
                </div>

                <label class="col-sm-3 px-0 col-form-label">{{__("First name Latin")}}</label>
                <div class="col-sm-3 px-0">
                    <div class="font-weight-bold form-control-plaintext">
                        {{$row["first_name_en"]}}</div>
                </div>

                <label class="col-sm-3 px-0 col-form-label">{{__("Last name Latin")}}</label>
                <div class="col-sm-3 px-0">
                    <div class="font-weight-bold form-control-plaintext">
                        {{$row["last_name_en"]}}</div>
                </div>

                <label class="col-sm-3 px-0 col-form-label">{{__("Gender")}}</label>
                <div class="col-sm-3 px-0">
                    <div class="font-weight-bold form-control-plaintext">
                        {{$row["gender"]["name"]}}</div>
                </div>

                <label class="col-sm-3 px-0 col-form-label">{{__("Date of birth")}}</label>
                <div class="col-sm-3 px-0">
                    <div class="font-weight-bold form-control-plaintext">
                        {{$row["date_of_birth"]}}</div>
                </div>


                <label class="col-sm-3 px-0 col-form-label">{{__("Nationality ")}}</label>
                <div class="col-sm-3 px-0">
                    <div class="font-weight-bold form-control-plaintext">
                        {{$row["nationality"]["name"]}}</div>
                </div>

                <label class="col-sm-3 px-0 col-form-label">{{__("National Id")}}</label>
                <div class="col-sm-3 px-0">
                    <div class="font-weight-bold form-control-plaintext">
                        {{$row["national_id"]}}</div>
                </div>

                <label class="col-sm-3 px-0 col-form-label">{{__("Mother tong")}}</label>
                <div class="col-sm-3 px-0">
                    <div class="font-weight-bold form-control-plaintext">
                        {{$row["mother_tong"]["name"]}}</div>
                </div>

                <label class="col-sm-3 px-0 col-form-label">{{__("Blood group")}}</label>
                <div class="col-sm-3 px-0">
                    <div class="font-weight-bold form-control-plaintext">
                        {{$row["blood_group"]["name"]}}</div>
                </div>

                <label class="col-sm-3 px-0 col-form-label">{{__("Blood group")}}</label>
                <div class="col-sm-3 px-0">
                    <div class="font-weight-bold form-control-plaintext">
                        {{$row["blood_group"]["name"]}}</div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 px-0 col-form-label">{{__("Permanent address")}}</label>
                <div class="col-sm-9 px-0">
                    <div class="font-weight-bold form-control-plaintext">
                        {{$row["permanent_address"]}}</div>
                </div>

                <label class="col-sm-3 px-0 col-form-label">{{__("Temporaray address")}}</label>
                <div class="col-sm-9 px-0">
                    <div class="font-weight-bold form-control-plaintext">
                        {{$row["temporaray_address"]}}</div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 px-0 col-form-label">{{__("Phone")}}</label>
                <div class="col-sm-3 px-0">
                    <div class="font-weight-bold form-control-plaintext">{{$row["phone"]}}
                    </div>
                </div>

                <label class="col-sm-3 px-0 col-form-label">{{__("Email")}}</label>
                <div class="col-sm-3 px-0">
                    <div class="font-weight-bold form-control-plaintext">{{$row["email"]}}
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <label class="col-sm-3 px-0 col-form-label">{{__("Father fullname")}}</label>
                <div class="col-sm-3 px-0">
                    <div class="font-weight-bold form-control-plaintext">
                        {{$row["staff_guardian"]["father"]["name"]}}</div>
                </div>

                <label class="col-sm-3 px-0 col-form-label">{{__("Occupation")}}</label>
                <div class="col-sm-3 px-0">
                    <div class="font-weight-bold form-control-plaintext">
                        {{$row["staff_guardian"]["father"]["occupation"]}}</div>
                </div>

                <label class="col-sm-3 px-0 col-form-label">{{__("Father phone")}}</label>
                <div class="col-sm-3 px-0">
                    <div class="font-weight-bold form-control-plaintext">
                        {{$row["staff_guardian"]["father"]["phone"]}}</div>
                </div>

                <label class="col-sm-3 px-0 col-form-label">{{__("Father email")}}</label>
                <div class="col-sm-3 px-0">
                    <div class="font-weight-bold form-control-plaintext">
                        {{$row["staff_guardian"]["father"]["email"]}}</div>
                </div>

                <label class="col-sm-3 px-0 col-form-label">{{__("Mother fullname")}}</label>
                <div class="col-sm-3 px-0">
                    <div class="font-weight-bold form-control-plaintext">
                        {{$row["staff_guardian"]["mother"]["name"]}}</div>
                </div>

                <label class="col-sm-3 px-0 col-form-label">{{__("Occupation")}}</label>
                <div class="col-sm-3 px-0">
                    <div class="font-weight-bold form-control-plaintext">
                        {{$row["staff_guardian"]["mother"]["occupation"]}}</div>
                </div>

                <label class="col-sm-3 px-0 col-form-label">{{__("Mother phone")}}</label>
                <div class="col-sm-3 px-0">
                    <div class="font-weight-bold form-control-plaintext">
                        {{$row["staff_guardian"]["mother"]["phone"]}}</div>
                </div>

                <label class="col-sm-3 px-0 col-form-label">{{__("Mother phone")}}</label>
                <div class="col-sm-3 px-0">
                    <div class="font-weight-bold form-control-plaintext">
                        {{$row["staff_guardian"]["mother"]["email"]}}</div>
                </div>
            </div>
        </div>
        <div class="col-2">
            <img class="img-thumbnail" data-src="{{$row["photo"]}}" alt="" />
        </div>
    </div>
</div>
