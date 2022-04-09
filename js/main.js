/**
 * @return boolean
 */
function MarkAllRows(container_id) {
    var rows = document.getElementById(container_id).getElementsByTagName('tr');
    var unique_id;
    var checkbox;
    var checked = document.getElementById('all_items').checked;

    for (var i = 0; i < rows.length; i++) {
        checkbox = rows[i].getElementsByTagName('input')[0];

        if (checkbox && checkbox.type === 'checkbox') {
            unique_id = checkbox.name + checkbox.value;
            checkbox.checked = checked;
            rows[i].className = rows[i].className.replace(' marked', '');
        }
    }

    return true;
}


// noinspection JSUnusedGlobalSymbols
function change_course() {
    let course = $('#course').val();
    $.post("?module=view_books&action=change_course&course_id=" + course, function (data) {
        $('#book_description_item').html('');
        $('#book').html(data);
        $('#book').selectpicker('refresh');
    });
}

// noinspection JSUnusedGlobalSymbols
function change_group() {
    let group = $('#list_group').val();
    $.post("?module=login&action=change_group&group_id=" + group, function (data) {
        $('#list_user_login').html(data);
        $('#list_user_login').selectpicker('refresh');
    });
}

// noinspection JSUnusedGlobalSymbols
function change_module() {
    let module = $('#module').val();
    $.post("?module=access&action=change_module&module_id=" + module, function (data) {
        $('#action').html(data);
        $('#action').selectpicker('refresh');
    });
}

// noinspection JSUnusedGlobalSymbols
function change_section() {
    let section = $('#section_id').val();
    $.post("?module=testing&action=change_section&section_id=" + section, function (data) {
        $('#test_id_description_item').html('');
        $('#test_id').html(data);
    });
}

// noinspection JSUnusedGlobalSymbols
function change_section_group_report() {
    let section = $('#section_id').val();
    $.post("?module=report_groups&action=change_section&section_id=" + section, function (data) {
        $('select[name="test[]"]').html(data);
    });
}

// noinspection JSUnusedGlobalSymbols
function change_section_book_group_report() {
    let course = $('#course_id').val();
    $.post("?module=report_book_groups&action=change_course&course_id=" + course, function (data) {
        $('select[name="book[]"]').html(data);
    });
}

// noinspection JSUnusedGlobalSymbols
function get_book_description() {
    let book = $('#book').val();
    $.post("?module=view_books&action=get_book_description&book_id=" + book, function (data) {
        $('#book_description_item').html(data);
    });
}

// noinspection JSUnusedGlobalSymbols
function get_test_description() {
    let test = $('#test_id').val();
    $.post("?module=testing&action=get_test_description&test_id=" + test, function (data) {
        $('#test_id_description_item').html(data);
    });
}


