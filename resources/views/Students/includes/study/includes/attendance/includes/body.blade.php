<div class="card-body {{request()->segment(2) == "study" ? "py-0" : "p-0"}}">
    <div class="table-responsive" data-toggle="list" data-list-values='["id", "name"'>
        <table class="table table-bordered table-hover table-xs" id="t1 list-table">
            @include(config("pages.parent").".includes.study.includes.attendance.includes.subbody.a")
            @include(config("pages.parent").".includes.study.includes.attendance.includes.subbody.b")
        </table>
        <div id="form-edit" class="d-none">
            <form role="edit" class="needs-validation" method="POST"
                action="{{config('pages.form.data.'.$key.'.action.edit',config("pages.form.action.detect"))}}"
                id="form-edit-attendance" enctype="multipart/form-data">
                <select class="form-control" data-toggle="select" id="attendance_type" 
                    data-minimum-results-for-search="Infinity" data-placeholder="">
                    @foreach($attendances_type["data"] as $o)
                    <option value="{{$o["id"]}}" data-absent="{{$o["credit_absent"]}}">{{ $o["name"]}}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
</div>
