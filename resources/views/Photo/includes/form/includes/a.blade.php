<div class="col-xl-6 col-sm-12 col-xs-12">
    <div class="card">
        <div class="card-body">
            @if (config("pages.form.data"))
            <div class="list-image">
                <div class="file" id="file-{{$key}}">
                    <div class="browse">
                    <input title="" type="file" data-toggle="upload-crop" data-target="div#photo-crop-{{$key}}"
                            accept="image/jpeg,.jpg,image/png,.png,.jpeg">
                    </div>

                </div>
            </div>
            @if (config("pages.form.data.listImage"))
            <div class="list-group list-group-flush" id="list-group-image">
                @foreach (config("pages.form.data.".$key.".listImage") as $row)
                <a href="#" class="list-group-item list-group-item-action">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <img data-src="{{$row["photo"]}}" class="rounded img-fluid" width="60px">
                        </div>
                        <div class="col ml--2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-0 text-sm">

                                    </h4>
                                </div>
                            </div>
                            <div class="float-right">
                                @if (config("pages.form.data.".$key.".id") == $row["id"])
                                <button type="button" data-toggle="select-crop" data-image="{{$row["photo"]}}"
                                    class="btn btn-sm btn-primary disabled"
                                    data-text-select="{{ __("Select") }}"
                                    data-text-selected="{{ __("Selected") }}"
                                    data-target="div#photo-crop-{{$key}}">{{ __("Selected") }}
                                    <li class="fas fa-check"></li></button>
                                @else
                                <button type="button" data-toggle="select-crop" data-image="{{$row["photo"]}}"
                                    class="btn btn-sm btn-primary" data-text-select="{{ __("Select") }}"
                                    data-text-selected="{{ __("Selected") }}"
                                    data-target="div#photo-crop-{{$key}}">{{ __("Select") }}
                                </button>
                                @endif

                            </div>

                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @endif
            @endif
        </div>
    </div>
</div>
