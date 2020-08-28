<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">

<head>
    <meta charset="UTF-8">
    <title>{{config('app.title')}}</title>

    <link rel="stylesheet" href="{{asset("/assets/vendor/@fortawesome/fontawesome-pro/css/pro.min.css")}}"
        type="text/css">
    <link rel="stylesheet" href="{{ asset("/assets/css/paper.css") }}" />
    <link rel="stylesheet" href="{{asset("/assets/vendor/sweetalert2/dist/sweetalert2.min.css")}}">

    <style>
        [id^="stage"] {
            margin: auto 2mm;
            @if ($response["settings"] && $response["settings"]["layout"]=="vertical") display: inline-block;
            margin: auto 0.3mm 0.1mm;
            width: 504px;
            height: 350px;
            @endif
        }

        [id^="stage"] .konvajs-content {
            margin: auto;
        }


        header {
            padding: 10px;
            color: white;
            background: var(--app-color, blueviolet);
        }
    </style>

</head>

<body>

    <div class="side-menu open pinned d-print-none">
        <div style="display: inline-block;height: 100%;width:100%;overflow-y: auto;padding: 20px;">

            <div style="margin-top: 10px">
                <form role="filter" class="needs-validation" method="GET" action="{{request()->url()}}" id="form-filter"
                    enctype="multipart/form-data">

                    <div style="margin: 10px 0">
                        <b> {{__('Sheet')}}</b>
                    </div>
                    <div style="display: inline-block;border: 1px solid #ccc;padding: 10px;width:100%">
                        <div>
                            <label style="display: inline-block;width:100%" for="size">{{__('Size')}}</label>
                            <select style="display: inline-block" class="form-control" data-toggle="select" id="size"
                                 data-allow-clear="true" 
                                data-placeholder="" data-select-value="{{request('size')}}" name="size">
                                <option {{request('size') == 'A3'?'selected':''}} value="A3">
                                    {{__('A3')}}
                                </option>
                                <option {{request('size') == 'A4'?'selected':''}} value="A4">
                                    {{__('A4')}}
                                </option>
                                <option {{request('size') == 'A5'?'selected':''}} value="A5">
                                    {{__('A5')}}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label style="display: inline-block;width:100%" for="layout">{{__('Layout')}}</label>
                            <select style="display: inline-block" class="form-control" data-toggle="select" id="layout"
                                 data-allow-clear="true" 
                                data-placeholder="" data-select-value="{{request('layout')}}" name="layout">
                                <option {{request('layout') == 'portrait'?'selected':''}} value="portrait">
                                    {{__('Portrait')}}
                                </option>
                                <option {{request('layout') == 'landscape'?'selected':''}} value="landscape">
                                    {{__('Landscape')}}
                                </option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary float-right">
                                {{ __("Set") }}
                            </button>
                        </div>
                    </div>

                </form>
            </div>
            <div style="display: inline-flex;width: 100%;margin-top: 20px;">
                <button style="width: 50%" data-toggle="table-to-excel" data-table-id="t1,t2,t3" data-name=""
                    class="btn btn-primary btn-save {{$response == false ? "d-none":""}}">
                    <i class="fas fa-save"></i>
                    {{__("Save")}}
                </button>
                <button style="width: 50%" onclick="print();"
                    class="btn btn-primary {{$response == false ? "d-none":""}}">
                    <i class="fas fa-print"></i>
                    {{__("Print")}} | (A4) {{__(request('layout'))}}
                </button>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="content-main">
            <div class="paper {{request('size','A4')}} {{request('layout')}}">
                @if ($response['data'])

                @else
                <section class="sheet nodata d-print-none">
                    <div class="nodata-text">{{__('No Data')}}</div>
                </section>
                @endif

            </div>
            @include("layouts.navFooter")
        </div>
    </div>
</body>
@section("script")
<script src="{{ asset("/assets/vendor/konva/konva.min.js")}}"></script>
<script src="{{asset("/assets/vendor/jquery/dist/jquery.min.js")}}"></script>
<script src="{{asset("/assets/vendor/sweetalert2/dist/sweetalert2.min.js")}}"></script>
@if (app()->getLocale() !== "en")
<script src="{{asset("/assets/vendor/sweetalert2/dist/i18n/".app()->getLocale().".js")}}"></script>
@endif
<script>
    var i18n = {
        'Ok': 'Ok',
        'Cancel': 'Cancel',
        'Yes': 'Yes',
        'Error': 'Error',
        "Saving data": "Saving data ...",
        "Updating data": "Updating data ...",
    };
    i18n = $.extend({}, i18n, sweetalert2_i18n);


    function b64toFile(dataURI) {
        const byteString = atob(dataURI.split(',')[1]);
        const mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
        const ab = new ArrayBuffer(byteString.length);
        const ia = new Uint8Array(ab);
        for (let i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }
        const blob = new Blob([ab], {
            'type': mimeString
        });
        blob['lastModifiedDate'] = (new Date()).toISOString();
        blob['name'] = 'file';
        switch (blob.type) {
            case 'image/jpeg':
                blob['name'] += '.jpg';
                break;
            case 'image/png':
                blob['name'] += '.png';
                break;
        }
        return blob;
    }
    var cardId = [];
    var response = {!!json_encode($response["data"]) !!};

    var j = 1;
    for (var i in response) {
        var id = response[i].realId;
        var sheet = $("<section></section>");
        sheet.attr({
            id: id,
            class: "sheet padding-10mm"
        });
        cardId.push(id);
        var container = $("<div></div>");
        container.attr({
            id: "stage-" + id,
        });

        if (j == 1) {
            $(".paper").append(sheet);
        } else if (j == 4) {
            j = 0;
        }
        $(".paper").find("section:last").append(container);
        response[i] = Konva.Node.create(response[i], "stage-" + id);
        response[i].find("Image").forEach(imageNode => {
            var nativeImage = new Image();
            nativeImage.onload = () => {
                imageNode.image(nativeImage);
                imageNode.getLayer().batchDraw();
            };
            nativeImage.src = imageNode.getAttr("source");
        });
        j++;
    }

    $(".btn-print").on("click", () => {
        window.print();
    });

    $(".btn-save").on("click", () => {
        var a = null;
        var formData = new FormData();
        formData.append("_token", "{{csrf_token()}}");
        for (var i in response) {
            formData.append("cards[" + i + "][id]", cardId[i]);
            formData.append("cards[" + i + "][image]", b64toFile(response[i].toDataURL({
                pixelRatio: 3
            })));
        }
        if (a) {
            a.abort();
        }

        a = $.ajax({
            url: location.href.replace("result", "save"),
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                swal({
                    title: i18n['Saving data'],
                    showCloseButton: true,
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    onOpen: () => {
                        swal.showLoading();
                    },
                    onClose: () => {
                        a.abort();
                    }
                });
            },
            success: function (response) {
                if (response.success) {
                    swal({
                        title: response.message,
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn",
                        confirmButtonText: i18n['Ok'],
                    });
                }
            }
        })

    });

</script>

</html>
