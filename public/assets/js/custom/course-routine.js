var CourseRoutine = function (element) {
    this.element = element;
    this.table = null;
    this.sel = [];
    this.last = null;
};

CourseRoutine.prototype = {
    build: function () {
        this.element.length &&
            this.element.each((i, el) => {
                this.table = new Table(el);
                this.td($(el).find("td.cell"));
                this.contextMenu();
            });
    },
    td: function (td) {
        td.on("mousedown", event => {
            if (event.which == 1) {
                var isCtrl = event.ctrlKey || event.metaKey;
                if (isCtrl) {
                    event.preventDefault();
                    if( $(event.target).hasClass("selected")){
                        $(event.target).removeClass("selected");
                    }else{
                        $(event.target).addClass("selected");
                    }

                    return false;
                } else {
                    this.element.find("td.cell").removeClass("selected");
                    $(event.target).addClass("selected");
                }
            }
        });
    },
    contextMenu: function () {
        this.element.find("tbody").contextMenu({
            selector: "td.cell",
            build: ($triggerElement, event) => {
                var items = {
                    add: {
                        name: "បញ្ចូល",
                        icon: "edit",
                        callback: () => {
                            if ($(".tsc-template").length) {
                                if ($(".tsc-template").find(".select2-hidden-accessible").length) {
                                    $(".tsc-template")
                                        .find(".select2-hidden-accessible")
                                        .select2("destroy");
                                }
                                $triggerElement.append($(".tsc-template").clone().html());
                                $triggerElement.find("select");
                                Select2($triggerElement.find("select"));
                            }
                        }
                    },
                    select: {
                        name: "ជ្រើសរើស",
                        icon: function () {
                            return "context-menu-icon fa-check-square";
                        },
                        callback: () => {
                            $triggerElement.addClass("selected");
                        }
                    },
                    unselect: {
                        name: "ដកការជ្រើសរើស",
                        icon: function () {
                            return "context-menu-icon fa-times-square";
                        },
                        callback: () => {
                            $triggerElement.removeClass("selected");
                        }
                    },
                    merge: {
                        name: "បញ្ចូលក្រឡារួមគ្នា",
                        icon: "fa-square-full",
                        callback: () => {
                            var selected = [];
                            $(this.table.element)
                                .find("td.cell.selected")
                                .each(function () {
                                    if ($.inArray($.trim($(this).attr("class")),selected) == -1) {
                                        selected.push(
                                            $.trim($(this).attr("class"))
                                        );
                                    }
                                });
                            $.each(selected, (i, c) => {
                                var cells = this.table
                                    .element.querySelectorAll('[class^="' + c + '"]');
                                this.table.merge(cells,function (colspan,rowspan,kept,removed) {});
                            });
                        }
                    },
                    unmerge: {
                        name: "មិនបញ្ចូលចូលក្រឡារួមគ្នា",
                        icon: "fa-th-large",
                        callback: () => {
                            var selected = [];
                            $(this.table.element)
                                .find("td.cell.selected")
                                .each(function () {
                                    if ($.inArray($.trim($(this).attr("class")),selected) == -1) {
                                        selected.push(
                                            $.trim($(this).attr("class"))
                                        );
                                    }
                                });
                            $.each(selected, (i, c) => {
                                var cells = this.table.element.querySelectorAll('[class^="' + c + '"]');
                                var i = 0;
                                this.table.split(cells, td => {
                                    $(td).addClass("cell");
                                    this.td($(td));
                                });
                            });
                        }
                    },
                    sep1: "---------",
                    empty: {
                        name: "លុបការបញ្ចូលនេះចេញ",
                        icon: "fa-eraser",
                        callback: () => {
                            this.element
                                .find("td.cell.selected")
                                .find("div")
                                .remove();
                        }
                    },
                    sep1: "---------",
                    add_row: {
                        name: "បន្ថែមជួរដេក",
                        icon: "fa-grip-lines",
                        callback: () => {
                            var clone = $triggerElement.parent("tr").clone();
                            clone.find("td.cell").removeClass("context-menu-active");
                            clone.find("td.cell").removeClass("selected");
                            clone.find("td.cell").html("");
                            $triggerElement.parent("tr").after(clone);
                            this.td(clone.find("td.cell"));
                            this.contextMenu();
                        }
                    },
                    delete: {
                        name: "លុបជួរដេក",
                        icon: "delete",
                        callback: () => {
                            if ($triggerElement.parents("tbody").find("tr").length == 1) {
                                //Swal.fire("Can't delete!");
                            } else {
                                $triggerElement.parent("tr").remove();
                            }
                        }
                    }
                };


                if ($triggerElement.hasClass("selected")) {
                    delete items.select;
                    if (this.element.find("td.cell.selected").length == 1) {
                        delete items.merge;
                        if ($triggerElement.attr("rowspan") != 'undefined' || $triggerElement.attr("colspan") != 'undefined') {
                            if($triggerElement.attr("rowspan") > 1 || $triggerElement.attr("colspan") > 1){
                                //delete items.unmerge;
                            }else{
                                delete items.unmerge;
                            }
                        }
                    } else {
                        delete items.unmerge;
                    }
                    if (
                        this.element.find("td.cell.selected").find("div").length
                    ) {
                        delete items.add;
                    } else {
                        delete items.empty;
                    }
                } else {
                    delete items.unselect;
                    delete items.merge;
                    delete items.unmerge;
                    delete items.add;
                    delete items.empty;
                }
                if ($triggerElement.parents("tbody").find("tr").length == 1){
                    delete items.delete;
                }

                return {
                    items: items
                };
            }
        });
    },

    json: function () {
        var matrix = this.table.matrix();
        var data = [];
        $.each(matrix, (i, cells) => {
            if (i != 0) {
                if (!data[i])
                    data[i] = {
                        time: null,
                        days: []
                    };
                $.each(cells, (j, td) => {
                    if (j == 0) {
                        data[i].time = {
                            start: $(td.cell).find('[name="start_time[]"]').val(),
                            end: $(td.cell).find('[name="end_time[]"]').val()
                        };
                    } else {
                        var cell = td.cell ? td.cell : td.refCell.cell;
                        data[i].days.push({
                            id: $(matrix[0][j].cell).data("value"),
                            teachers: $(cell).find('[name="teachers[]"]').val() || null,
                            subjects: $(cell).find('[name="study_subjects[]"]').val() || null,
                            class: $(cell).find('[name="study_class[]"]').val() || null
                        });
                    }
                });
            }
        });
        return data;
    },
    formData: function () {
        var formData = new FormData();
        $.each(this.json(), (i, sc) => {
            if (sc) {
                formData.append("start_time[" + i + "]", sc.time.start);
                formData.append("end_time[" + i + "]", sc.time.end);
                $.each(sc.days, (j, d) => {
                    formData.append("days[" + i + "][" + d.id + "]", d.id);
                    formData.append("teachers[" + i + "][" + d.id + "]", d.teachers);
                    formData.append("study_subjects[" + i + "][" + d.id + "]", d.subjects);
                    formData.append("study_class[" + i + "][" + d.id + "]", d.class);
                });
            }
        });

        return formData;
    }
};
