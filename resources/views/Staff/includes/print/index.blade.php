<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.title') }}</title>
    <link rel="stylesheet" href="{{ asset('/assets/css/paper.css') }}" />
    <style>
        td strong::before {
            content: ' : ';
            color: slateblue;
        }

    </style>
</head>

<body>

    <div class="side-menu open pinned d-print-none">
        <div style="display: inline-block;height: 100%;width:100%;overflow-y: auto;padding: 20px;">

            <div style="margin-top: 10px">
                <form role="filter" class="needs-validation" method="GET" action="{{ request()->url() }}"
                    id="form-filter" enctype="multipart/form-data">
                    <div style="margin: 10px 0">
                        <b> {{ __('Sheet') }}</b>
                    </div>
                    <div style="display: inline-block;border: 1px solid #ccc;padding: 10px;width:100%">
                        <div>
                            <label style="display: inline-block;width:100%" for="size">{{ __('Size') }}</label>
                            <select style="display: inline-block" class="form-control" data-toggle="select" id="size"
                                 data-allow-clear="true" data-text="{{ __('Add new option') }}"
                                data-placeholder="" data-select-value="{{ request('size') }}" name="size">
                                <option {{ request('size') == 'A3' ? 'selected' : '' }} value="A3">
                                    {{ __('A3') }}
                                </option>
                                <option {{ request('size') == 'A4' ? 'selected' : '' }} value="A4">
                                    {{ __('A4') }}
                                </option>
                                <option {{ request('size') == 'A5' ? 'selected' : '' }} value="A5">
                                    {{ __('A5') }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label style="display: inline-block;width:100%" for="layout">{{ __('Layout') }}</label>
                            <select style="display: inline-block" class="form-control" data-toggle="select" id="layout"
                                 data-allow-clear="true" data-text="{{ __('Add new option') }}"
                                data-placeholder="" data-select-value="{{ request('layout') }}" name="layout">
                                <option {{ request('layout') == 'portrait' ? 'selected' : '' }} value="portrait">
                                    {{ __('Portrait') }}
                                </option>
                                <option {{ request('layout') == 'landscape' ? 'selected' : '' }} value="landscape">
                                    {{ __('Landscape') }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary float-right">
                                {{ __('Set') }}
                            </button>
                        </div>
                    </div>

                </form>
            </div>
            <div style="display: inline-flex;width: 100%;margin-top: 20px;">
                <button style="width: 50%" onclick="print();"
                    class="btn btn-primary {{ $response == false ? 'd-none' : '' }}">
                    <i class="fas fa-print"></i>
                    {{ __('Print') }} | (A4) {{ __(request('layout')) }}
                </button>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="content-main">
            <div class="paper {{ request('size', 'A4') }} {{ request('layout') }}">
                @if ($response['data'])
                    @foreach ($response['data'] as $group => $res)
                        <section class="sheet padding-10mm">
                            @include(config("pages.parent").".includes.print.includes.body",['row' => $res])
                            <div class="break"></div>
                        </section>
                    @endforeach
                @else
                    <section class="sheet nodata d-print-none">
                        <div class="nodata-text">{{ __('No Data') }}</div>
                    </section>
                @endif
                @include("layouts.navFooter")
            </div>
        </div>
    </div>
</body>

</html>
