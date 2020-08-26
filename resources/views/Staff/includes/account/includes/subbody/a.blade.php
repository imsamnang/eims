<div class="sticky-top" data-spy="affix" data-offset-top="100">
    <form role="account" action="{{ $row['action']['account'] }}" method="POST" id="form-create-account"
        data-validate="{{ json_encode(config('pages.form.validate')) }}">
        @csrf
        <div class="card m-0">
            <div class="card-header">
                <div class="font-weight-600">{{ __('Account') }}</div>
                <div class="list-group list-group-flush">
                    <div href="#" class="list-group-item">
                        <div class="row">
                            <div class="avatar avatar-xl rounded">
                                <img data-src="{{ $row['photo'] }}?type=original" alt="" id="crop-image">
                            </div>
                            <div class="col ml--2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="mb-0 text-sm">
                                            {{ $row['name'] }}
                                        </h4>
                                    </div>
                                </div>
                                <p class="text-sm mb-0">
                                    {{ $row['institute'] }}
                                </p>
                                <p class="text-sm mb-0">
                                    {{ $row['designation'] }}
                                </p>
                                @if ($row['account'])
                                    <p class="text-sm mb-0 text-green">
                                        {{ __('Has account') }}
                                        <i class="fas fa-check-circle"></i>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (!$row['account'])
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                                title="{{ config('pages.form.validate.questions.email') }}" class="form-control-label"
                                for="email">

                                {{ __('Email') }}
                                @if (config('pages.form.validate.rules.email'))
                                    <span class="badge badge-md badge-circle badge-floating badge-danger"
                                        style="background:unset">
                                        <i class="fas fa-asterisk fa-xs"></i>
                                    </span>
                                @endif

                            </label>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input disabled type="email" class="form-control" id="email" placeholder=""
                                        value="{{ $row['email'] }}"
                                        {{ config('pages.form.validate.rules.email') ? 'required' : '' }}
                                        name="email" />
                                </div>
                            </div>

                        </div>
                        <div class="col-md-4 mb-3">
                            <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                                title=""
                                class="form-control-label" for="password">
                                {{ __('Password') }}
                                @if (config('pages.form.validate.rules.password'))
                                    <span class="badge badge-md badge-circle badge-floating badge-danger"
                                        style="background:unset">
                                        <i class="fas fa-asterisk fa-xs"></i>
                                    </span>
                                @endif

                            </label>

                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="password" placeholder="" value="123456"
                                        {{ config('pages.form.validate.rules.password') ? 'required' : '' }}
                                        name="password" />

                                </div>
                            </div>

                        </div>

                        <div class="col-md-4 mb-3">
                            <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                                title="{{ config('pages.form.validate.questions.role') }}" class="form-control-label"
                                for="role">
                                {{ __('Role') }}
                                @if (config('pages.form.validate.rules.role'))
                                    <span class="badge badge-md badge-circle badge-floating badge-danger"
                                        style="background:unset">
                                        <i class="fas fa-asterisk fa-xs"></i>
                                    </span>
                                @endif
                            </label>

                            <select class="form-control" data-toggle="select" id="role" name="role"
                                title="Simple select" data-text="{{ __('Add new option') }}" data-placeholder=""
                                data-select-value="{{ @$row['suggest_role'] }}"
                                {{ config('pages.form.validate.rules.role') ? 'required' : '' }}>
                                @foreach ($roles['data'] as $o)
                                    <option data-src="{{ $o['image'] }}" value="{{ $o['id'] }}">{{ $o['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    @if (count($formData) > 1)
                        <a class="btn" target="_blank" href="#create-all-auto" data-toggle="collapse"
                            href="">{{ __('Create all auto') }}</a>
                    @endif

                    <button type="submit" class="btn float-right btn-primary">
                        {{ __('Create') }}
                    </button>
                </div>
            @endif
        </div>
    </form>

    <div class="collapse" id="create-all-auto">

        <form role="account" action="{{ str_replace('account', 'account/create', config('pages.form.action.view')) }}"
            method="POST" id="form-create-account-auto"
            data-validate="{{ json_encode(config('pages.form.validate')) }}">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h4>
                        {{ __('Create all auto') }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                                title="{{ __('Old password will be use if Field password is not input.') }}"
                                class="form-control-label" for="password">
                                {{ __('Password') }}
                                @if (config('pages.form.validate.rules.password'))
                                    <span class="badge badge-md badge-circle badge-floating badge-danger"
                                        style="background:unset"><i class="fas fa-asterisk fa-xs"></i></span> </beautify
                                    end="   @endif">

                            </label>

                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="password" placeholder="" value="123456"
                                        {{ config('pages.form.validate.rules.password') ? 'required' : '' }}
                                        name="password" />

                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label data-toggle="tooltip" rel="tooltip" data-placement="top"
                                title="{{ config('pages.form.validate.questions.role') }}" class="form-control-label"
                                for="role">
                                {{ __('Role') }}
                                @if (config('pages.form.validate.rules.role'))
                                    <span class="badge badge-md badge-circle badge-floating badge-danger"
                                        style="background:unset">
                                        <i class="fas fa-asterisk fa-xs"></i>
                                    </span>
                                @endif
                            </label>

                            <select class="form-control" data-toggle="select" id="role" name="role"
                                title="Simple select" data-text="{{ __('Add new option') }}" data-placeholder=""
                                data-select-value="{{ @$row['suggest_role'] }}"
                                {{ config('pages.form.validate.rules.role') ? 'required' : '' }}>
                                @foreach ($roles['data'] as $o)
                                    <option data-src="{{ $o['image'] }}" value="{{ $o['id'] }}">{{ $o['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn float-right btn-primary">
                        {{ __('Create auto') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
