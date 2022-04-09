function set_auto_height() {
    $("#listLeft li").outerHeight(
        function (i, val) {
            return "auto";
        }
    );
    $("#listRight li").outerHeight(
        function (i, val) {
            return "auto";
        }
    )
}

function set_equal_height() {
    var max_height_left = [];
    var max_height_right = [];
    $("#listLeft li").each(
        function (i, element) {
            max_height_left.push($(element).outerHeight());
        }
    );
    $("#listRight li").each(
        function (i, element) {
            max_height_right.push($(element).outerHeight());
        }
    );

    $("#listLeft li").outerHeight(
        function (i, val) {
            if (max_height_left[i] > max_height_right[i]) {
                return max_height_left[i];
            } else {
                return max_height_right[i];
            }
        }
    );

    $("#listRight li").outerHeight(
        function (i, val) {
            if (max_height_left[i] > max_height_right[i]) {
                return max_height_left[i];
            } else {
                return max_height_right[i];
            }
        }
    )
}

$(function () {
    $("#listLeft").disableSelection();
    $("#listRight").disableSelection();
    set_auto_height();
    set_equal_height();
});

$("#listLeft").sortable({
    receive: function (e, ui) {
        if ((typeof $(this).data("max-count") !== 'undefined') && $(this).children.length > $(this).data("max-count")) {
            $(ui.sender).sortable('cancel');
            ui.sender.data('copied', false);
        } else {
            ui.sender.data('copied', true);
        }
    },
    update: function (event, ui) {
        //ui.item.unbind("click");
        //ui.item.one("click", function (event) {
        //    event.stopImmediatePropagation();
        //    $(this).click(myClick);
        //});
        var sequence = $('#listLeft').sortable('toArray');
        $("#sequence_left").val(sequence);
        $("#submit_button").prop('disabled', false);
        set_auto_height();
        set_equal_height();
    },
    start: function (event, ui) {
        ui.item.bind("click.prevent",
            function (event) {
                event.preventDefault();
            });
    },
    stop: function (event, ui) {
        setTimeout(function () {
            ui.item.unbind("click.prevent");
        }, 300);
    }

});

$("#listRight").sortable({
    receive: function (e, ui) {
        if ((typeof $(this).data("max-count") !== 'undefined') && $(this).children.length > $(this).data("max-count")) {
            $(ui.sender).sortable('cancel');
            ui.sender.data('copied', false);
        } else {
            ui.sender.data('copied', true);
        }
    },
    update: function (event, ui) {
        var sequence = $('#listRight').sortable('toArray');
        $("#sequence_right").val(sequence);
        $("#submit_button").prop('disabled', false);
        set_auto_height();
        set_equal_height();
    }
});

$("#basketLeft").sortable({
    connectWith: "#listLeft",
    helper: function (e, li) {
        this.copyHelper = li.clone().insertAfter(li);
        $(this).data('copied', false);
        return li.clone();
    },
    stop: function () {
        var copied = $(this).data('copied');
        if (!copied) {
            this.copyHelper.remove();
        }
        this.copyHelper = null;
    }
});

$("#basketRight").sortable({
    connectWith: "#listRight",
    helper: function (e, li) {
        this.copyHelper = li.clone().insertAfter(li);
        $(this).data('copied', false);
        return li.clone();
    },
    stop: function () {
        var copied = $(this).data('copied');
        if (!copied) {
            this.copyHelper.remove();
        }
        this.copyHelper = null;
    }
});

$(".clear_list_left").click(function () {
    $("#listLeft").children().remove();
    $("#sequence_left").val("");
});
$(".clear_list_right").click(function () {
    $("#listRight").children().remove();
    $("#sequence_right").val("");
});
$(window).on('resize', function () {
    set_auto_height();
    set_equal_height();
});