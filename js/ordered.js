$("#orderedList").sortable({
    update: function(event, ui) {
        var sequence = $('#orderedList').sortable('toArray');
        $("#sequence").val(sequence);
        $("#submit_button").prop('disabled', false);
    }
});