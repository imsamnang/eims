<div class="col">
    <div class="modal fade" id="mailbox-compose" tabindex="-1" role="dialog" aria-labelledby="add_modal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <form role="{{config("pages.form.role")}}" class="needs-validation" method="POST"
                action="{{str_replace("/add","compose",config("pages.form.action.add"))}}" id="form-mailbox" data-toggle="mailbox-compose"
                enctype="multipart/form-data" data-validation="{{json_encode(config('pages.form.validate'))}}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" class="h3 mr-2">
                            {{ Translator:: phrase("compose") }}
                        </h6>
                        <a href="{{str_replace("/add","compose",config("pages.form.action.add"))}}" target="_blank" class="full-link"><i
                                class="fas fa-external-link"></i> </a>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body p-0">
                        <div class="card m-0">
                            <div class="card-body">
                                {{-- @if (request()->ajax()) --}}
                                @include(config("pages.parent").".includes.modal.includes.a")
                                {{-- @endif --}}

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer {{config("pages.form.role") == "view"? "invisible": ""}}">
                        <div class="col">
                            <div class="row">
                                <div class="{{count($listData) > 1 ? "col-md-8":"col-md-12"}}">
                                    <a href="" name="scrollTo"></a>
                                    <button class="btn btn-primary ml-auto float-right" type="submit">

                                        {{ Translator:: phrase("send_message") }}

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
