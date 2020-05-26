<div class="row">
    <div class="col-6 offset-3">
        <div class="card-wrapper">
            <form role="{{config("pages.form.role")}}" class="needs-validation" novalidate="" method="POST"
                action="{{config("pages.form.action.detect")}}" id="form-{{config("pages.form.name")}}"
                enctype="multipart/form-data" data-validate="{{json_encode(config('pages.form.validate'))}}">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h3 mb-0">
                            {{ Translator:: phrase(config("pages.form.role").".frame.card") }}
                        </h5>
                    </div>

                    <div class="card-body">
                        @include("Card.includes.form")
                    </div>
                    <div class="card-footer">

                        <a type="hidden" name="scrollTo" href=""></a>

                        <button class="btn btn-primary ml-auto pull-right" type="submit">
                            @if (config("pages.form.role") == "add")
                            {{ Translator:: phrase("save") }}
                            @elseif(config("pages.form.role") == "edit")
                            {{ Translator:: phrase("update") }}
                            @elseif(config("pages.form.role") == "view")
                            {{ Translator:: phrase("goto.edit") }}
                            @endif

                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
