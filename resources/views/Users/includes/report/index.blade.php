<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.title') }}</title>
    <link rel="stylesheet" href="{{ asset('/assets/css/paper.css') }}" />
</head>

<body>

    <div class="side-menu open pinned d-print-none">
        <div style="display: inline-block;height: 100%;width:100%;overflow-y: auto;padding: 20px;">

            <div style="margin-top: 10px">

                <form role="filter" class="needs-validation" method="GET" action="{{ request()->url() }}"
                    id="form-filter" enctype="multipart/form-data">
                    <div style="margin: 10px 0">
                        <b> {{ __('Filter') }}</b>
                    </div>
                    <div style="display: inline-block;border: 1px solid #ccc;padding: 10px;">
                        @if (Auth::user()->role_id == 1)
                            <div>
                                <label style="display: inline-block;width:100%"
                                    for="institute">{{ __('Institute') }}</label>
                                <select style="display: inline-block" class="form-control" data-toggle="select"
                                    id="institute"  data-allow-clear="true"
                                    data-text="{{ __('Add new option') }}" data-placeholder=""
                                    data-select-value="{{ request('instituteId') }}" name="instituteId">
                                    <option value="">{{ __('Choose') }}</option>
                                    @foreach ($instituteFilter['data'] as $o)
                                        <option {{ $o['id'] == request('instituteId') ? 'selected' : '' }}
                                            data-src="{{ $o['image'] }}" value="{{ $o['id'] }}">{{ $o['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div>
                            <label style="display: inline-block;width:100%" for="role">{{ __('Role') }}</label>
                            <select style="display: inline-block" class="form-control" data-toggle="select" id="role"
                                 data-allow-clear="true" data-text="{{ __('Add new option') }}"
                                data-placeholder="" data-select-value="{{ request('roleId') }}" name="roleId">
                                <option value="">{{ __('Choose') }}</option>
                                @foreach ($roleFilter['data'] as $o)
                                    <option {{ $o['id'] == request('roleId') ? 'selected' : '' }}
                                        data-src="{{ $o['image'] }}" value="{{ $o['id'] }}">{{ $o['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary float-right"><i
                                    class="fa fa-filter-search"></i>
                                {{ __('Search filter') }}</button>
                        </div>
                    </div>

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
                            @if ($group == 0)
                                @if (@$institute['logo'])
                                    <div>
                                        <img style="position: absolute;left:10mm;" width="70"
                                            src="{{ $institute['logo'] }}" alt="{{ $institute['name'] }}">
                                    </div>
                                @endif

                                <div style="text-align: center; margin-top: 50px;">
                                    <span style="margin: 0;font-size: 20px;font-weight: 600;">
                                        {{ @$institute['name'] }}
                                    </span>
                                    <h4 style="margin-top: 0">
                                        {{ config('pages.title') }}
                                        @if (request('roleId'))
                                            " {{ $res[$group]['role'] }} "
                                        @endif
                                    </h4>
                                </div>

                            @endif
                            @include(config("pages.parent").".includes.report.includes.body",[
                            'total' => $response['total'],
                            'group' => $group,
                            'response'=> $res,
                            'date'=> $response['date'],
                            'last' => count($response['data']) == $group + 1
                            ])
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
