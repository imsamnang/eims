@extends("layouts.master-v1")
@section('meta')

@endsection
@section('style')
    <link rel="icon" href="{{ config('app.favicon') }}" type="image/png">
    <link rel="stylesheet" href="{{asset("/assets/vendor/nucleo/css/nucleo.css")}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('/assets/vendor/@fortawesome/fontawesome-pro/css/pro.min.css') }}"
        type="text/css">
    <link rel="stylesheet" href="{{ asset('/assets/css/argon.min.css?v=1.1.0') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('/assets/css/custom.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('/assets/vendor/viewerjs/dist/viewer.min.css') }}" />

    <style>
        .filemanager {
            border: 1px solid #e7eaec;
            padding: 0;
            background-color: #ffffff;
            position: relative;
        }

        .filemanager .icon,
        .filemanager .image {
            width: 100%;
            height: 100px;
            overflow: hidden;
        }

        .filemanager .image img {
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            object-fit: contain;
        }

        .filemanager .icon {
            padding: 15px 10px;
            text-align: center;
        }

        .filemanager-control {
            color: inherit;
            font-size: 11px;
            margin-right: 10px;
        }

        .filemanager-control.active {
            text-decoration: underline;
        }

        .filemanager .icon i {
            font-size: 70px;
        }

        .filemanager .filemanager-name {
            font-size: 12px;
            padding: 10px;
            background-color: #f8f8f8;
            border-top: 1px solid #e7eaec;
        }

        .filemanager-name small {
            color: #676a6c;
        }

        .corner {
            position: absolute;
            display: inline-block;
            font-size: 12px;
            height: 0;
            line-height: 0;
            border: 0.6em solid transparent;
            border-right: 0.6em solid #f1f1f1;
            border-bottom: 0.6em solid #f1f1f1;
            right: 0em;
            bottom: 0em;
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

        .fa-folder{
            color : #ffc107;
        }
        .fa-file-pdf{
            color: #f44336
        }

    </style>
    <link rel="stylesheet" href="https://www.shieldui.com/shared/components/latest/css/light-bootstrap/all.min.css">
@endsection
@section('bodyClass', 'g-sidenav-show g-sidenav-pinned')
@section('content')
    @include('FileManager.includes.navLeft',['items' => $directories])
    <div class="main-content" id="panel">
        {{-- @include(Auth::user()->role('view_path').".includes.navTop") --}}
        {{-- @include("layouts.navHeader") --}}
        <div class="page-content container-fluid my-3">
            @include('FileManager.includes.view.index',['items' => $view_directories])
        </div>
    </div>

@endsection

@section('script')

    <script src="{{ asset('/assets/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/jquery/dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/jquery/dist/jquery-2.1.4.min.js') }}"></script>
    <script src="{{asset("/assets/js/custom/urlhelper.js")}}"></script>
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

        $('[id="show-items"]').click(function(event) {
            event.preventDefault();
            var items = $(this).data('items');
            var url = $(this).attr('href');
            var urlhelper =  new UrlHelper();

            $('#filemanager-items').html('');
            $(this).parents('ul').find('.active')
                .removeClass('text-blue')
                .removeClass('active');

            $(this)
                .addClass('text-blue')
                .addClass('active');

            new Viewer($('#filemanager-items').get(0)).destroy();
            urlhelper.set(items,url);
            if (items && items.length) {
                $('#filemanager-items-result').addClass('d-none');
                $.each(items, (i, $item) => {

                    var tpl = ` <div class="filemanager-box col-2 px-2" id="dir-items" data-type="${$item['type']}">
                        <div class="filemanager mb-3">
                            <a href="${ $item['type'] == 'directory' ? $item['link'] : '#' }">
                                <span class="corner">${ $item['file_info']['size'] }</span>
                                ${$item['icon_url'] ? `
                                    <div class="image p-0">
                                        <img src="${ $item['icon_url']}" class="w-100 h-100">
                                    </div>
                                `: ` <div class="icon">  <i class="${ $item['icon_class'] }"></i> </div>` }
                                <div class="filemanager-name text-truncate" title="${ $item['name'] }">
                                    ${$item['type'] == 'file' || $item['type'] ==  'image' ? `
                                        <table>
                                            <tr>
                                                <td>{{ __('Name') }} :</td>
                                                <td>${ $item['file_info']['name'] }</td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('Type') }} :</td>
                                                <td>${ $item['file_info']['extension'] }</td>
                                            </tr>
                                            ${$item['file_info']['width'] ? `
                                            <tr>
                                                <td colspan="2">${ $item['file_info']['width'] } x ${ $item['file_info']['height'] }</td>
                                            </tr>
                                            `:''}
                                            <tr>
                                                <td colspan="2">${ $item['file_info']['date'] }</td>
                                            </tr>
                                        </table>
                                    `:$item['name']}
                                </div>
                            </a>
                        </div>
                    </div>`;
                    $('#filemanager-items').append(tpl);
                });
                //lazyLoadInstance.update();
                new Viewer($('#filemanager-items').get(0)).reset();
            } else {
                $('#filemanager-items-result').removeClass('d-none');
            }
            var total_directory =  $('#filemanager-items').find('[data-type="directory"]').length;
            var total_file =  $('#filemanager-items').find('[data-type="file"]').length;
            var total_image =  $('#filemanager-items').find('[data-type="image"]').length;
            $('#filemanager-items').parents('.card').find('.card-footer').html(`
                <div class=''>
                    <div class="col">{{__('Folders')}} :${total_directory}</div>
                    <div class="col">{{__('Files')}} : ${total_file}</div>
                    <div class="col">{{__('Image')}} :${total_image}</div>
                </div>
            `);

        });


    </script>

@endsection
