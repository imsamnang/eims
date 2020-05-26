<div class="col-3">
    <a data-backdrop="static" data-keyboard="false" href="{{config("pages.form.action.detect")}}"
        class="btn btn-block btn-primary mt-3" data-toggle="modal" data-target="#modal">
        {{ Translator:: phrase("add.".config("pages.form.name")) }}
    </a>

    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" data-modal-show="modal-lg"
            role="document">
            <form role="{{config("pages.form.role")}}" class="needs-validation" novalidate="" method="POST"
                action="{{config("pages.form.action.detect")}}" id="form-{{config("pages.form.name")}}"
                enctype="multipart/form-data" style="height: 100%;display: contents;">

                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" class="h3 mr-2">
                            {{ Translator:: phrase("add.".config("pages.form.name")) }}
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
                                @include("Card.includes.form")
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <a type="hidden" name="scrollTo" href=""></a>
                        <button class="btn btn-primary ml-auto pull-right"
                            type="submit">{{ Translator:: phrase("save") }}</button>
                    </div>

                </div>
            </form>
        </div>

    </div>
</div>
