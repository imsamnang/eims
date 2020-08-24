<div class="card">
    <div class="card-header bg-white">
        <div class="row">
            <div class="col">
                <h5 class="card-title">
                    {{ $segments }}
                </h5>
            </div>
            <div class="col-4">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text p-1"><i class="fas fa-search"></i></div>
                    </div>
                    <input type="search" class="form-control form-control-sm" id="filter"
                        data-target="#file-manager-items" data-filter="#dir-items" data-result="#file-manager-items-result"
                        placeholder="{{ __('Search') }}">
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row" id="file-manager-items" data-toggle="gallery">
            @foreach ($items as $item)
                <div class="file-manager-box col-2 px-2" id="dir-items">
                    <div class="file-manager mb-3">
                        <a href="{{ $item['type'] == 'directory' ? $item['link'] : '#' }}">
                            <span class="corner"></span>
                            @if (@$item['icon_url'])
                                <div class="image p-0">
                                    <img src="{{ @$item['icon_url'] }}" class="w-100 h-100"
                                        style="background-image: url({{ @$item['icon_url'] }})">
                                </div>
                            @else
                                <div class="icon">
                                    <i class="{{ $item['icon_class'] }}"></i>
                                </div>
                            @endif

                            <div class="file-manager-name text-truncate" title="{{ $item['name'] }}">
                                @if ($item['type'] == 'file')
                                    <table>
                                        <tr>
                                            <td>{{ __('Name') }} :</td>
                                            <td>{{ $item['file_info']['name'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Size') }} :</td>
                                            <td>{{ $item['file_info']['size'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Type') }} :</td>
                                            <td>{{ $item['file_info']['extension'] }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">{{ $item['file_info']['date'] }}</td>
                                        </tr>
                                    </table>

                                @else
                                    {{ $item['name'] }}
                                @endif
                            </div>


                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="{{ $items ? 'd-none' : '' }}" id="file-manager-items-result">
            <div class="text-center p-3">
                <p class="m-0"><svg width="64" height="41" viewBox="0 0 64 41" xmlns="http://www.w3.org/2000/svg">
                        <g transform="translate(0 1)" fill="none" fill-rule="evenodd">
                            <ellipse fill="#F5F5F5" cx="32" cy="33" rx="32" ry="7"></ellipse>
                            <g fill-rule="nonzero" stroke="#D9D9D9">
                                <path
                                    d="M55 12.76L44.854 1.258C44.367.474 43.656 0 42.907 0H21.093c-.749 0-1.46.474-1.947 1.257L9 12.761V22h46v-9.24z">
                                </path>
                                <path
                                    d="M41.613 15.931c0-1.605.994-2.93 2.227-2.931H55v18.137C55 33.26 53.68 35 52.05 35h-40.1C10.32 35 9 33.259 9 31.137V13h11.16c1.233 0 2.227 1.323 2.227 2.928v.022c0 1.605 1.005 2.901 2.237 2.901h14.752c1.232 0 2.237-1.308 2.237-2.913v-.007z"
                                    fill="#FAFAFA"></path>
                            </g>
                        </g>
                    </svg>
                </p>

                <span class="no-data">{{ __('No Data') }}</span>
                <span class="not-found d-none">{{ __('No items match your search.') }}</span>
            </div>
        </div>
    </div>
    <div class="card-footer">

    </div>
</div>
