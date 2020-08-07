<div class="card">
    @foreach ($student as $key => $item)
    @if (($key + 1) > 2)
    <div class="collapse" id="student-more">
        @include(Auth::user()->role('view_path').".includes.dashboard.includes.student.includes.a")
    </div>
    @else
    @include(Auth::user()->role('view_path').".includes.dashboard.includes.student.includes.a")
    @endif

    @if (count($student) == ($key + 1))
    <a class="btn btn-sm" data-toggle="collapse" href="#student-more" role="button" aria-expanded="false"
        aria-controls="student-more">
        {{__("More")}}
    </a>
    @endif
    @endforeach
</div>
