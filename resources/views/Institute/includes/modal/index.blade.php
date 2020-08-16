<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="edit_view_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title mr-3" class="h3 mr-2">
                    {{ __(config("pages.form.role")) }}
                </h6>
                <a href="{{config("pages.form.action.detect")}}" target="_blank" class="full-link"><i
                        class="fas fa-external-link"></i> </a>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body p-0">
                <div class="card m-0">
                    <div class="card-body">
                        @if (request()->ajax())
                        @if (config('pages.parameters.param1') == 'edit')
                        @include(config("pages.parent").".includes.form.index")
                        @elseif (config('pages.parameters.param1') == 'account')
                        @include(config("pages.parent").".includes.account.index")
                        @else
                        @include(config("pages.parent").".includes.view.index")
                        @endif
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button data-dismiss="modal" class="float-left btn btn-secondary">
                    {{__('Close')}}
                </button>
                @if (config('pages.parameters.param1') == 'edit')
                @if (count($listData) == 1)
                <button id="btn-submit" class="btn btn-primary float-right">{{__('Update')}}</button>

                @endif

                @endif
            </div>
        </div>
    </div>
</div>

@if (config('pages.parameters.param1') == 'list')
<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="add_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title mr-3" class="h3 mr-2">
                    {{ __(config("pages.form.role")) }}
                </h6>
                <a href="{{config("pages.form.action.detect")}}" target="_blank" class="full-link"><i
                        class="fas fa-external-link"></i> </a>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="card m-0">
                    <div class="card-body p-0">
                        @include(config("pages.parent").".includes.form.index")
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button data-dismiss="modal" class="float-left btn btn-secondary">
                    {{__('Close')}}
                </button>
                <button id="btn-submit" class="float-right btn btn-primary">
                    {{__('Save')}}
                </button>
            </div>
        </div>
    </div>
</div>
@endif
