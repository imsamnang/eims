var Mailbox = {
    Modals: {
        Compose: function () {
            this.init = function () {
                    this.builder();
                },
                this.builder = function (element) {
                    var e = element ? element : $('[data-toggle="mailbox-modal"]');
                    e.length && e.each(function () {
                        $(this).click(function (event) {
                            event.preventDefault();
                            var target = $(this).data("target");
                            $(target).find("form").get(0).reset();
                            $(target).modal();
                            // if ($(target).length) {
                            //     $(target).modal();
                            //     var ajaxModal = new AjaxFormModal();
                            //     ajaxModal.set({
                            //         element: $(this),
                            //         modalContainer: $(target),
                            //         url: $(this).attr("href"),
                            //         method: "GET",
                            //         onCompleted: (xhr, type, modalBody) => {
                            //             if (type == "success") {
                            //                 modalBody.show();
                            //                 var formCompose = new Mailbox.Forms.Compose();
                            //                 formCompose.builder($(target).find("form"));
                            //             }
                            //         }
                            //     });
                            //     ajaxModal.load();
                            // }

                        })
                    });
                }

        }

    },
    Forms: {
        Compose: function () {
            this.init = function () {
                    this.builder();
                },
                this.builder = function (element) {
                    var e = element ? element : $('form[data-toggle="mailbox-compose"]');

                    e.length && e.unbind().each(function () {
                        var form = $(this);

                        // tinymce.init({
                        //     selector:'#message',
                        //     toolbar: 'bold italic underline strikethrough | fontselect fontsizeselect formatselect | forecolor backcolor | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | link image media',
                        // });

                        Quill.prototype.getHtml = function () {
                            return this.container.firstChild.innerHTML;
                        };
                        var name = form.find('#message').data("name");
                        if (name) {

                            var quill = new Quill(form.find('#message').get(0), {
                                modules: {
                                    imageResize: {
                                        modules: [ 'Resize', 'DisplaySize', 'Toolbar' ]
                                    },
                                    videoResize: {
                                        modules: [ 'Resize', 'DisplaySize', 'Toolbar' ]
                                    },
                                    toolbar: {
                                        container: form.find("#toolbar-container").get(0),
                                        handlers: {
                                            image: imageHandler
                                        }
                                    }
                                },
                                placeholder: form.find('#message').data("placeholder"),
                                theme: "snow"
                            });

                            function imageHandler() {
                                var range = this.quill.getSelection();
                                var value = prompt('What is the image URL');
                                if (value) {
                                    this.quill.insertEmbed(range.index, 'image', value, Quill.sources.USER);
                                }
                            }

                            quill.on('text-change', function () {
                                if (form.find('[name="' + name + '"]').length) {
                                    form.find('[name="' + name + '"]').val(quill.getHtml());

                                } else {
                                    var textarea = $("<textarea name='" + name + "'>").val(quill.getHtml()).addClass("d-none");
                                    form.find('#message').after(textarea);
                                }
                            });
                        }




                        if (form.data("validation")) {
                            var load = $('<span class="loading ml-2"><img src="' + location.origin + '/assets/img/icons/LOOn0JtHNzb.gif"></span>');
                            form.attr({
                                hasValidate: true
                            }).validation({
                                request_field: $(this).data("validation"),
                                onBeforeSend: (xhr, loading) => {
                                    $(this).find('button[type="submit"]').append(load);
                                },
                                onSuccess: (response) => {
                                    load.remove();
                                    if (response.success) {}
                                }
                            });
                        }


                    });
                }
        },
        Reply: function () {
            this.init = function () {
                    this.builder();
                },
                this.builder = function (element) {
                    var e = element ? element : $('form[data-toggle="mailbox-reply"]');

                    e.length && e.unbind().each(function () {
                        var form = $(this);

                        // tinymce.init({
                        //     selector:'#message',
                        //     toolbar: 'bold italic underline strikethrough | fontselect fontsizeselect formatselect | forecolor backcolor | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | link image media',
                        // });

                        Quill.prototype.getHtml = function () {
                            return this.container.firstChild.innerHTML;
                        };
                        var name = form.find('#message').data("name");
                        if (name) {

                            var quill = new Quill(form.find('#message').get(0), {
                                modules: {
                                    imageResize: {
                                        modules: [ 'Resize', 'DisplaySize', 'Toolbar' ]
                                    },
                                    videoResize: {
                                        modules: [ 'Resize', 'DisplaySize', 'Toolbar' ]
                                    },
                                    toolbar: {
                                        container: form.find("#toolbar-container").get(0),
                                        handlers: {
                                            image: imageHandler
                                        }
                                    }
                                },
                                placeholder: form.find('#message').data("placeholder"),
                                theme: "snow"
                            });

                            function imageHandler() {
                                var range = this.quill.getSelection();
                                var value = prompt('What is the image URL');
                                if (value) {
                                    this.quill.insertEmbed(range.index, 'image', value, Quill.sources.USER);
                                }
                            }

                            quill.on('text-change', function () {
                                if (form.find('[name="' + name + '"]').length) {
                                    form.find('[name="' + name + '"]').val(quill.getHtml());

                                } else {
                                    var textarea = $("<textarea name='" + name + "'>").val(quill.getHtml()).addClass("d-none");
                                    form.find('#message').after(textarea);
                                }
                            });
                        }




                        if (form.data("validation")) {
                            var load = $('<span class="loading ml-2"><img src="' + location.origin + '/assets/img/icons/LOOn0JtHNzb.gif"></span>');
                            form.attr({
                                hasValidate: true
                            }).validation({
                                request_field: $(this).data("validation"),
                                onBeforeSend: (xhr, loading) => {
                                    $(this).find('button[type="submit"]').append(load);
                                },
                                onSuccess: (response) => {
                                    load.remove();
                                    if (response.success) {}
                                }
                            });
                        }


                    });
                }
        }
    },
    Nav :  function(){
        this.init = function(){
            
        },
        this.builder = function(){

        }

    }

};

$(document).ready(function () {
    var formCompose = new Mailbox.Forms.Compose();
    formCompose.init();
    var formReply = new Mailbox.Forms.Reply();
    formReply.init();

    var modalCompose = new Mailbox.Modals.Compose();
    modalCompose.init();



    $("[datetime]").timeago();
});
