/**
 * Created by Ravil on 27.05.2015.
 */


/*************************************
 *  M a t c h e d   q u e s t i o n s
 ************************************/
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


/**************************************
 *   O r d e r e d   q u e s t i o n s
 *************************************/

$("#orderedList").sortable({
    update: function (event, ui) {
        var sequence = $('#orderedList').sortable('toArray');
        $("#sequence").val(sequence);
        $("#submit_button").prop('disabled', false);
    }
});

/***************
 * C o m m o n
 ***************/


$(function () {
    var msg_wrong = $("#wrongAnswerMessage");
    var msg_right = $("#rightAnswerMessage");
    msg_wrong.modal();
    msg_right.modal();
    msg_wrong.on('hidden.bs.modal', function () {
        set_focus_on_open_answer();
        set_focus_on_inline();
    });
    msg_right.on('hidden.bs.modal', function () {
        set_focus_on_open_answer();
        set_focus_on_inline();
    });
    set_focus_on_inline();
    $("#submit_button").everyTime(5000, function (i) {
        $.post("?module=testing&action=time");
    });
    $(document).bind('keydown', 'return', function () {
        document.test_form.submit_button.click();
    });
    set_focus_on_open_answer();
});

function fill_fields(fields) {
    $("div#fill_fields input:text, div#fill_fields select").each(function (index) {
        let field = $(this);
        let real_field = fields[index];
        if (fields[index].search("<wrong>") !== -1) {
            real_field = real_field.replace(/<wrong>/g, "");
            real_field = real_field.replace(/<\/wrong>/g, "");
            field.css("border-color", "red");
        }
        field.val(real_field);
    });
}

function fill_fields_message(fields) {
    $("div#fill_fields_message input:text, div#fill_fields_message select").each(function (index) {
        $(this).val(fields[index]);
    });
}

function fill_fields_with_marks_message(fields) {
    $("div#fill_fields_message input:text, div#fill_fields_message select").each(function (index) {
        let field = $(this);
        let real_field = fields[index];
        if (fields[index].search("<wrong>") !== -1) {
            real_field = real_field.replace(/<wrong>/g, "");
            real_field = real_field.replace(/<\/wrong>/g, "");
            field.css("border-color", "red");
        }
        field.val(real_field);
    });
}

function set_focus_on_inline() {
    const InputField = document.getElementById('inline[0]');
    if ((InputField != null) && (InputField.placeholder === '')) InputField.focus();
}

function set_focus_on_open_answer() {
    const InputField = document.getElementById('open_answers[0]');
    if ((InputField != null) && (InputField.placeholder === '')) {
        InputField.focus();
        $(document).unbind('keydown', 'return', function () {
            document.test_form.submit_button.click();
        });
    }
}

function back_question() {
    $.post("?module=testing&action=back_question", function (data) {
        if (data === "1")
            location.replace(location.href);
    });
}

function skip_question() {
    $.post("?module=testing&action=skip_question", function (data) {
        if (data === "1")
            location.replace(location.href);
    });
}

function break_testing() {
    $.post("?module=testing&action=break_testing", function (data) {
        if (data === "1")
            location.replace(location.href);
    });
}

function NextVisible() {
    document.test_form.submit_button.disabled = false;
}

function ShowTimer(title, unrestrictedly) {
    let time_left = document.getElementById('time_left');
    if (time_left !== null) {
        let questionTimerInterval = setInterval(questionTimer, 1000);

        function questionTimer() {
            let d = new Date(time_left.dataset.time);
            let local_time = d.toLocaleTimeString();
            if (local_time === "Invalid Date")
                time_left.innerHTML = title + ": <strong>" + unrestrictedly + "</strong>";
            else if (local_time !== "00:00:00") {
                d.setTime(d.getTime() - 1);
                time_left.dataset.time = d.toUTCString();
            } else clearInterval(questionTimerInterval);
            time_left.innerHTML = title + ": <strong>" + local_time + "</strong>";
        }
    }
}

$("textarea").keyup(function () {
    let value = $(this).val();
    $("#area-count").text(value.length + "/" + value.trim().split(/\s+/).length);
});

$("input").keyup(function () {
    $("#input-count").text($(this).val().length + "/" + $(this).val().trim().split(/\s+/).length);
});
