<div class="row rounded" data-theme-bg-color="{{ config('app.theme_color.name') }}">
    <div class="col-xl-8">
        <div class="row">
            <div class="col">
                <div class="card bg-default">
                    <div class="card-body">
                        <div class="chart">
                            <!-- Chart wrapper -->
                            <canvas id="chart-bar" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                @include(Auth::user()->role('view_path').".includes.dashboard.includes.staff.index")
            </div>
            <div class="col">
                @include(Auth::user()->role('view_path').".includes.dashboard.includes.student.index")
            </div>
        </div>

        <div class="row">
            @include(Auth::user()->role('view_path').".includes.dashboard.includes.studyProgram.index")
        </div>


    </div>

    <div class="col-xl-4">
        <div class="card widget-calendar">
            <div class="card-header pb-0 border-0">
                <div class="h2 text-muted mb-1 widget-calendar-today"></div>
            </div>
            <div class="card-header text-center border-0">
                <div class="display-1" id="clock"></div>
            </div>
            <div class="card-header">
                <div class="d-flex">
                    <a href="#"
                        class="text-white widget-calendar-btn-prev btn-sm bg-{{ config('app.theme_color.name') }}"
                        data-theme-bg-color="{{ config('app.theme_color.name') }}">
                        <i class="fas fa-angle-left"></i>
                    </a>
                    <div class="h3 mb-0 widget-calendar-month m-auto"></div>
                    <a href="#"
                        class="text-white widget-calendar-btn-next btn-sm bg-{{ config('app.theme_color.name') }}"
                        data-theme-bg-color="{{ config('app.theme_color.name') }}">
                        <i class="fas fa-angle-right"></i>
                    </a>

                </div>

            </div>
            <div class="card-body">
                <div data-toggle="widget-calendar" data-event-url="{{ URL::to('/holiday/calendar') }}"></div>
            </div>
        </div>
    </div>
</div>
