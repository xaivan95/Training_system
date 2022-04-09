<?php

/**
 * Specify action object. For actions column.
 */
class action
{
    /**
     * string action name
     */
    var $name;

    /**
     * string action title
     */
    var $title;

    /**
     * string action image file name
     */
    var $image;

    /**
     * string action Glyphicon name
     */
    var $glyphicon;

    /**
     * Constructor
     *
     * @param string $name  action name
     * @param string $title  action title
     * @param string $image string action image
     * @param string $glyphicon
     */
    function __construct($name = '', $title = '', $image = '', $glyphicon = '')
    {
        $this->name = $name;
        $this->title = $title;
        $this->image = $image;
        $this->glyphicon = $glyphicon;
    }
}

// Build actions.

$WEB_APP['action_edit'] = new action('edit', text('txt_edit'), 'edit.gif', 'pencil');
$WEB_APP['action_delete'] = new action('delete', text('txt_delete'), 'del.gif', 'trash');
$WEB_APP['action_password'] =
    new action('password', text('txt_change_password'), 'new_password.png', 'asterisk');
$WEB_APP['action_translations'] =
    new action('translations', text('txt_translations'), 'view.gif', 'resize-horizontal');
$WEB_APP['action_view_book'] = new action('view_book', text('txt_action_view'), 'view.gif', 'book');
$WEB_APP['action_modules'] = new action('modules', text('txt_modules'), 'view.gif', 'th-list');
$WEB_APP['action_update'] = new action('update', text('txt_update'), 'update.png', 'refresh');
$WEB_APP['action_questions'] =
    new action('questions', text('txt_questions'), 'questions.png', 'question-sign');
$WEB_APP['action_view_result'] = new action('view_result', text('txt_view'), 'questions.png', 'search');
$WEB_APP['action_details'] = new action('details', text('txt_view'), 'questions.png', 'list-alt');
$WEB_APP['action_details_limited'] = new action('details_limited', text('txt_view'), 'questions.png', 'list-alt');
$WEB_APP['action_download'] = new action('download', text('txt_download'), 'csv.png', 'download');
$WEB_APP['action_print_report'] = new action('print_report', text('txt_print'), 'report.gif', 'print');
$WEB_APP['action_print_report_compact'] =
    new action('print_report_compact', text('txt_print_compact'), 'report.gif', 'list');
$WEB_APP['action_print_report_themes'] =
    new action('print_report_themes', text('txt_print_themes'), 'report.gif', 'th-list');
$WEB_APP['action_continue'] = new action('continue_testing', text('txt_continue'), 'update.png', 'play');
$WEB_APP['action_finish'] = new action('finish', text('txt_finish'), 'del.gif', 'check');
$WEB_APP['action_clear'] = new action('clear', text('txt_clear'), 'clear.gif', 'erase');
$WEB_APP['action_dublicate'] = new action('dublicate', text('txt_dublicate'), 'dublicate.gif', 'copy');