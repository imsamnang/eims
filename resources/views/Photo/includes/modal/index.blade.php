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
                        @include(config("pages.parent").".includes.form.index")
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button data-dismiss="modal" class="float-left btn btn-secondary">
                    {{__('Close')}}
                </button>
                @if (count($listData) == 1)
                <button id="btn-submit" class="btn btn-primary float-right">{{__('Update')}}</button>
                @endif
            </div>
        </div>
    </div>
</div>
