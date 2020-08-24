@extends("layouts.master-v1")
@section('meta')

@endsection
@section('style')
    <link rel="icon" href="{{ config('app.favicon') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('/assets/vendor/@fortawesome/fontawesome-pro/css/pro.min.css') }}"
        type="text/css">
    <link rel="stylesheet" href="{{ asset('/assets/css/argon.min.css?v=1.1.0') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('/assets/css/custom.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('/assets/vendor/viewerjs/dist/viewer.min.css') }}" />

    <style>
        .file-manager {
            border: 1px solid #e7eaec;
            padding: 0;
            background-color: #ffffff;
            position: relative;
        }

        .file-manager .icon,
        .file-manager .image {
            width: 100%;
            height: 100px;
            overflow: hidden;
        }

        .file-manager .image img {
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            object-fit: contain;
        }

        .file-manager .icon {
            padding: 15px 10px;
            text-align: center;
        }

        .file-manager-control {
            color: inherit;
            font-size: 11px;
            margin-right: 10px;
        }

        .file-manager-control.active {
            text-decoration: underline;
        }

        .file-manager .icon i {
            font-size: 70px;
            color: #dadada;
        }

        .file-manager .file-manager-name {
            font-size: 12px;
            padding: 10px;
            background-color: #f8f8f8;
            border-top: 1px solid #e7eaec;
        }

        .file-manager-name small {
            color: #676a6c;
        }

        .corner {
            position: absolute;
            display: inline-block;
            width: 0;
            height: 0;
            line-height: 0;
            border: 0.6em solid transparent;
            border-right: 0.6em solid #f1f1f1;
            border-bottom: 0.6em solid #f1f1f1;
            right: 0em;
            bottom: 0em;
        }

        #sidenav-main {
            max-width: 300px !important;
        }

        .main-content {
            margin-left: 300px !important;
        }

        ul.tree,
        ul.tree ul {
            list-style: none;
            margin: 0;
            padding: 10px;
        }

        ul.tree ul {
            margin-left: 1.0em;
        }

        ul.tree li {
            position: relative;
            margin-left: 0;
            padding-left: 0em;
            margin-top: 0;
            margin-bottom: 0;
            border-left: thin solid #e8e8e8;
        }

        ul.tree li a.nav-link {
            padding-left: 1em !important;
        }

        ul.tree li:last-child {
            border-left: none;
        }

        ul.tree li:before {
            position: absolute;
            top: 0;
            left: 0;
            width: 0.8em;
            /* width of horizontal line */
            height: 1.2em;
            /* vertical position of line */
            vertical-align: top;
            border-bottom: thin solid #e8e8e8;
            content: "";
            display: inline-block;
        }

        ul.tree li:last-child:before {
            border-left: thin solid #e8e8e8;
        }

        .navbar-vertical .navbar-nav .nav-link[data-toggle=collapse]:after {
            content: none
        }

        .navbar-vertical .navbar-nav .nav-link[data-toggle=collapse][aria-expanded=true]:before {
            transform: rotate(90deg);
            color: #5e72e4;
        }

        .navbar-vertical .navbar-nav .nav-link[data-toggle=collapse]:before {
            font-family: 'Font Awesome 5 Pro';
            font-weight: 700;
            font-style: normal;
            font-variant: normal;
            display: inline-block;
            content: '\f105';
            margin-right: 5px;
            transition: all .15s ease;
            color: #ced4da;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
        }

    </style>
    <link rel="stylesheet" href="https://www.shieldui.com/shared/components/latest/css/light-bootstrap/all.min.css">
@endsection
@section('bodyClass', 'g-sidenav-show g-sidenav-pinned')
@section('content')
    @include('FileManager.includes.navLeft',['items' => $directories])
    <div class="main-content" id="panel">
        <div class="page-content container-fluid my-3">
            @include('FileManager.includes.view.index',['items' => $view_directories])
        </div>
    </div>

@endsection

@section('script')

    <script src="{{ asset('/assets/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/jquery/dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/jquery/dist/jquery-2.1.4.min.js') }}"></script>

    <script src="{{ asset('/assets/vendor/js-cookie/js.cookie.js') }}"></script>
    <script src="{{ asset('/assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/lazyload/intersection-observer.js') }}"></script>
    <script src="{{ asset('/assets/vendor/lazyload/lazyload.min.js') }}"></script>

    <script src="{{ asset('/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/viewerjs/dist/viewer.min.js') }}"></script>
    <script src="{{ asset('/assets/js/argon.min.js?v=1.1.0') }}"></script>

    <script>
        //Filter
        $("input#filter").on("input", function() {
            var allmatch = null;
            var value = $(this).val().toLowerCase();
            var target = $(this).data('target');
            var filter = $(this).data('filter');
            var result = $(this).data('result');
            var showhiden = $(this).data('showhide');

            $(target).find(filter).filter(function() {
                var m = $(this).text().toLowerCase().indexOf(value) > -1;

                if (m) {
                    allmatch = true;
                    if (showhiden) {
                        $(this).parents(showhiden).removeClass('d-none');
                    } else {
                        $(this).removeClass('d-none');
                    }
                } else {
                    if (showhiden) {
                        $(this).parents(showhiden).addClass('d-none');
                    } else {
                        $(this).addClass('d-none');
                    }
                }

            });
            if (value) {
                $(result).find('.not-found').removeClass('d-none');
                $(result).find('.no-data').addClass('d-none');
            } else {
                $(result).find('.not-found').addClass('d-none');
                $(result).find('.no-data').removeClass('d-none');
            }

            if (allmatch) {
                $(result).addClass('d-none');
            } else {
                $(result).removeClass('d-none');
            }
        });

    </script>

@endsection
