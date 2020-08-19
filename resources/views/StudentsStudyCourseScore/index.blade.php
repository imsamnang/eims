@extends("layouts.master-v1")
@section("meta")
{!! config("app.meta_html") !!}
@endsection


@section("style")
<link rel="icon" href="{{config("app.favicon")}}" type="image/png">

<link rel="stylesheet" href="{{asset("/assets/vendor/nucleo/css/nucleo.css")}}" type="text/css">
<link rel="stylesheet" href="{{asset("/assets/vendor/@fortawesome/fontawesome-pro/css/pro.min.css")}}" type="text/css">

<link rel="stylesheet" href="{{asset("/assets/vendor/select2/4.0.2/css/select2.min.css") }}" />
<link rel="stylesheet" href="{{asset("/assets/vendor/sweetalert2/dist/sweetalert2.min.css")}}">
<link rel="stylesheet" href="{{asset("/assets/vendor/animate.css/animate.min.css")}}">
<link rel="stylesheet" href="{{asset("/assets/css/argon.min.css?v=1.1.0")}}" type="text/css">
<link rel="stylesheet" href="{{asset("/assets/css/custom.css")}}" type="text/css">
<link rel="stylesheet" href="{{asset("/assets/css/spinner.css")}}" type="text/css">
<link rel="stylesheet" href="{{asset("/assets/css/custom.css") }}" />
<link rel="stylesheet" href="{{asset("/assets/css/icon.css") }}" />

<link rel="stylesheet" href="{{asset("/assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css")}}">
<link rel="stylesheet" href="{{asset("/assets/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css")}}">
<link rel="stylesheet" href="{{asset("/assets/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css")}}">


@endsection



@section("content")
@if (config("pages.parameters.param1") == "report")
@include(config("pages.parent").".includes.report.index")
@else
@include(Auth::user()->role('view_path').".includes.navLeft")
<div class="main-content" id="panel">
    @include(Auth::user()->role('view_path').".includes.navTop") @include("Layouts.navHeader")

    <div class="page-content container-fluid">
        @include(config("pages.parent").".includes.modal.index")
        @include(config("pages.view"))
        @include(Auth::user()->role('view_path').".includes.navFooter")
    </div>
</div>
@endif

@endsection

@section("script")
<script src="{{asset("/assets/vendor/jquery/dist/jquery.min.js")}}"></script>
<script src="{{asset("/assets/vendor/jquery/dist/jquery-ui.min.js")}}"></script>
<script src="{{asset("/assets/js/custom/urlhelper.js")}}"></script>
<script src="{{asset("/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js")}}"></script>
<script src="{{asset("/assets/vendor/sweetalert2/dist/sweetalert2.min.js")}}"></script>
<script src="{{asset("/assets/vendor/js-cookie/js.cookie.js")}}"></script>
<script src="{{asset("/assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js")}}"></script>
<script src="{{asset("/assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js")}}"></script>
<script src="{{asset("/assets/vendor/select2/4.0.2/js/select2.min.js")}}"></script>
<script src="{{asset("/assets/vendor/select2/dist/js/select2.dropdownPosition.js")}}"></script>

<script src="{{asset("/assets/vendor/nouislider/distribute/nouislider.min.js")}}"></script>
<script src="{{asset("/assets/vendor/dropzone/dist/min/dropzone.min.js")}}"></script>

<script src="{{asset("/assets/vendor/list.js/dist/list.min.js")}}"></script>
<script src="{{asset("/assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js")}}"></script>
<script src="{{asset("/assets/vendor/validatorjs/dist/validator.js")}}"></script>
@if (app()->getLocale() !== "en")
<script src="{{asset("/assets/vendor/select2/4.0.2/js/i18n/".app()->getLocale().".js")}}"></script>
<script src="{{asset("/assets/vendor/datatables.net/i18n/".app()->getLocale().".js")}}"></script>
<script src="{{asset("/assets/vendor/validatorjs/dist/lang/".app()->getLocale().".js")}}"></script>
<script
    src="{{asset("/assets/vendor/bootstrap-datepicker/dist/locales/bootstrap-datepicker.".app()->getLocale().".min.js")}}">
</script>
@endif
<script src="{{asset("/assets/vendor/lazyload/intersection-observer.js")}}"></script>
<script src="{{asset("/assets/vendor/lazyload/lazyload.min.js")}}"></script>
<script src="{{asset("/assets/js/custom/languages.js")}}"></script>
<script src="{{asset("/assets/js/custom/validation.js")}}"></script>
<script src="{{asset("/assets/js/custom/replace-with-tag.js")}}"></script>
<script src="{{asset("/assets/js/custom/form-modal.js")}}"></script>
<script src="{{ asset("/assets/vendor/table/table.js")}}"></script>
<script src="{{asset("/assets/vendor/autogrow/autogrow-ui.js")}}"></script>
<script src="{{asset("/assets/vendor/pagination/simplePagination.js")}}"></script>
<script src="{{asset("/assets/js/custom/main-content.js")}}"></script>
<script src="{{asset("/assets/vendor/printThis/printThis.js")}}"></script>
<script src="{{asset("/assets/vendor/table-to-excel/dist/tableToExcel.517b1af9.js")}}"></script>
<script src="{{asset("/assets/js/custom/ajaxTableData.js")}}"></script>

<script src="{{asset("/assets/vendor/datatables.net/js/jquery.dataTables.min.js")}}"></script>
<script src="{{asset("/assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js")}}"></script>
<script src="{{asset("/assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js")}}"></script>
<script src="{{asset("/assets/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js")}}"></script>
<script src="{{asset("/assets/vendor/datatables.net-buttons/js/buttons.html5.min.js")}}"></script>
<script src="{{asset("/assets/vendor/datatables.net-buttons/js/buttons.flash.min.js")}}"></script>
<script src="{{asset("/assets/vendor/datatables.net-buttons/js/buttons.print.min.js")}}"></script>
<script src="{{asset("/assets/vendor/datatables.net-select/js/dataTables.select.min.js")}}"></script>


<script src="{{asset("/assets/js/argon.min.js?v=1.1.0")}}"></script>
@endsection
