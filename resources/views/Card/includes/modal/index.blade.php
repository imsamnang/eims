<div class="col">
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="add_modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable {{ config("pages.parameters.param1") == "make" ? "modal-xl" : (count($listData) > 1 ? "modal-xl" : "modal-lg")}}"
            role="document">
            <form role="{{config("pages.form.role")}}" class="needs-validation" method="POST"
                action="{{config("pages.form.action.detect")}}" id="form-card-make" enctype="multipart/form-data"
                data-validate="{{json_encode(config('pages.form.validate'))}}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" class="h3 mr-2">
                            {{ __(config("pages.form.role").' Card') }}
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
                                @if (config("pages.parameters.param1") == "make")
                                @include(config("pages.parent").".includes.make.index")
                                @else
                                @if (request()->ajax())
                                @include(config("pages.parent").".includes.modal.includes.a")
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer {{config("pages.form.role") == "view"? "invisible": ""}}">
                        <div class="col">
                            <div class="row">
                                <div class="{{count($listData) > 1 ? "col-md-8":"col-md-12"}}">
                                    <a href="" name="scrollTo"></a>
                                    @if (config("pages.parameters.param1") == "make")
                                    <button class="btn btn-primary ml-auto float-right" type="submit">
                                        {{ __("Update") }}
                                    </button>
                                    @else
                                    <button class="btn btn-primary ml-auto float-right" type="submit">
                                        @if (config("pages.form.role") == "add")
                                        {{ __("Save") }}
                                        @elseif(config("pages.form.role") == "edit")
                                        {{ __("Update") }}
                                        @elseif(config("pages.form.role") == "view")
                                        {{ __("Goto Edit") }}
                                        @endif
                                        @endif

                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
