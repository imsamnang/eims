<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card-wrapper">

            <div class="card p-0 m-0">
                @if (config('pages.parameters.param1') == 'add')
                    <div class="card-header">
                        <h5 class="h3 mb-0">
                            {{ __(config('pages.form.role')) }}
                        </h5>
                    </div>
                @elseif(config('pages.parameters.param1') == 'edit' && !request()->ajax())
                    <div class="card-header">
                        <h5 class="h3 mb-0">
                            {{ __(config('pages.form.role')) }}
                        </h5>
                    </div>
                @endif

                <div class="card-body p-0 m-0">
                    <div class="row">
                        <div class="col-md-12" data-list-group>
                            <div class="accordion" id="accordion">
                                @foreach (config('pages.form.data') as $key => $row)
                                    @if (config('pages.form.data.'.$key))
                                    <div class="card">
                                        <div class="card-header" id="headingOne" data-toggle="collapse"
                                            data-target="#collapse-{{ $row->id }}" aria-expanded="true"
                                            aria-controls="collapse-{{ $row->id }}">
                                            <h3 class="mb-0">{{ $row->name }}</h3>
                                        </div>
                                        <div id="collapse-{{ $row->id }}" class="collapse {{ $key == 0 ? 'show' : '' }}"
                                            aria-labelledby="heading-{{ $row->id }}" data-parent="#accordion">
                                            @endif
                                            <form role="{{ config('pages.form.role') }}" class="needs-validation"
                                                novalidate="" method="POST"
                                                action="{{ config('pages.form.data.' . $key . '.action.edit', config('pages.form.action.detect')) }}"
                                                id="form-course-routine" enctype="multipart/form-data"
                                                data-validation="{{ json_encode(config('pages.form.validate')) }}">
                                                <div class="card m-0">
                                                    <div class="card-body p-0">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                @csrf
                                                                @include(config("pages.parent").".includes.form.includes.a",['key'=>$key])
                                                            </div>
                                                        </div>
                                                        <a href="" name="scrollTo"></a>
                                                    </div>
                                                    @if (config('pages.parameters.param1') == 'add')
                                                        @if (!request()->ajax())
                                                            <div class="card-footer">
                                                                <a href="{{ url(config('pages.host') . config('pages.path') . config('pages.pathview') . 'list') }}"
                                                                    class="btn btn-default" type="button">
                                                                    {{ __('Back') }}
                                                                </a>

                                                                <input class="btn btn-primary float-right" type="submit"
                                                                    value="{{ __('Save') }}" id="submit">
                                                            </div>
                                                        @endif
                                                    @elseif(config('pages.parameters.param1') == 'edit')
                                                        @if (!request()->ajax())
                                                            <div class="card-footer">
                                                                <a href="{{ url(config('pages.host') . config('pages.path') . config('pages.pathview') . 'list') }}"
                                                                    class="btn btn-default" type="button">
                                                                    {{ __('Back') }}
                                                                </a>


                                                                <input class="btn btn-primary float-right" type="submit"
                                                                    value="{{ __('Update') }}" id="submit">
                                                            </div>
                                                        @else
                                                            @if (count($listData) > 1)
                                                                <div class="card-footer">

                                                                    <input class="btn btn-primary float-right"
                                                                        type="submit" value="{{ __('Update') }}"
                                                                        id="submit">
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endif

                                                </div>
                                            </form>
                                            @if (config('pages.form.data.'.$key))
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
