@extends("layouts.master-v1")
@section("meta")
{!! config("app.meta_html") !!}
@endsection


@section("style")
<link rel="icon" href="{{config("app.favicon")}}" type="image/png">
<link rel="stylesheet" href="{{asset('/assets/vendor/nucleo/css/nucleo.css')}}" type="text/css">
<link rel="stylesheet" href="{{asset('/assets/vendor/@fortawesome/fontawesome-pro/css/pro.min.css')}}" type="text/css">
<link rel="stylesheet" href="{{asset('/assets/vendor/select-google-font/dist/selectGfont.min.css') }}">
<link rel="stylesheet" href="{{asset('/assets/vendor/select2/dist/css/select2.min.css') }}" />
<link rel="stylesheet" href="{{asset('/assets/vendor/sweetalert2/dist/sweetalert2.min.css')}}">
<link rel="stylesheet" href="{{asset('/assets/vendor/animate.css/animate.min.css')}}">
<link rel="stylesheet" href="{{asset('/assets/css/argon.min.css?v=1.1.0')}}" type="text/css">
<link rel="stylesheet" href="{{asset('/assets/css/custom.css')}}" type="text/css">
<link rel="stylesheet" href="{{asset('/assets/css/spinner.css')}}" type="text/css">
<link rel="stylesheet" href="{{asset("/assets/vendor/quill/dist/quill.core.css") }}" />
<link rel="stylesheet" href="{{asset("/assets/css/custom.css") }}" />
<link rel="stylesheet" href="{{asset("/assets/css/icon.css") }}" />
<link rel="stylesheet" href="{{asset('/assets/vendor/nouislider/distribute/nouislider.min.css')}}">





@endsection

@section("bodyClass","g-sidenav-show g-sidenav-pinned")

@section("content")

@include(Auth::user()->role('view_path').".includes.navLeft")
<div class="main-content" id="panel">
    @include(Auth::user()->role('view_path').".includes.navTop") @include("Layouts.navHeader")

    <div class="page-content container-fluid">
        @include(config("pages.parent").".includes.modal.index")
        @include(config("pages.view"))
        @include(Auth::user()->role('view_path').".includes.navFooter")
    </div>
</div>

@endsection

@section("script")

<script src="{{asset("/assets/vendor/jquery/dist/jquery.min.js")}}"></script>
<script src="{{asset("/assets/vendor/jquery/dist/jquery-ui.min.js")}}"></script>
<script src="{{asset('/assets/js/custom/urlhelper.js')}}"></script>
<script src="{{asset("/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js")}}"></script>
<script src="{{asset("/assets/vendor/sweetalert2/dist/sweetalert2.min.js")}}"></script>
<script src="{{asset("/assets/vendor/js-cookie/js.cookie.js")}}"></script>
<script src="{{asset("/assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js")}}"></script>
<script src="{{asset("/assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js")}}"></script>
<script src="{{asset("/assets/vendor/select2/dist/js/select2.min.js")}}"></script>
<script src="{{asset("/assets/vendor/select2/dist/js/select2.dropdownPosition.js")}}"></script>
<script src="{{asset("/assets/vendor/quill/dist/quill.min.js")}}"></script>
<script src="{{asset("/assets/vendor/dropzone/dist/min/dropzone.min.js")}}"></script>
<script src="{{asset("/assets/vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js")}}"></script>

<script src="{{asset("/assets/vendor/anchor-js/anchor.min.js")}}"></script>
<script src="{{asset("/assets/vendor/clipboard/dist/clipboard.min.js")}}"></script>
<script src="{{asset("/assets/vendor/holderjs/holder.min.js")}}"></script>
<script src="{{asset("/assets/vendor/prismjs/prism.js")}}"></script>
<script src="{{asset("/assets/vendor/lazyload/intersection-observer.js")}}"></script>
<script src="{{asset("/assets/vendor/lazyload/lazyload.min.js")}}"></script>

<script src="{{asset('/assets/js/custom/validation.js')}}"></script>
<script src="{{asset('/assets/js/custom/replace-with-tag.js')}}"></script>
<script src="{{asset('/assets/js/custom/form-modal.js')}}"></script>
<script src="{{asset('/assets/vendor/autogrow/autogrow-ui.js')}}"></script>
<script src="{{asset('/assets/vendor/nouislider/distribute/nouislider.min.js')}}"></script>
<script src="{{ asset('/assets/vendor/jquery-qrcode/jquery.qrcode.js')}}"></script>
<script src="{{asset("/assets/js/custom/ajaxTableData.js")}}"></script>
<script src="{{asset("/assets/js/argon.min.js?v=1.1.0")}}"></script>

@endsection
