<?php

/**
 * Specify action object. For actions combobox.
 */
class list_action
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
     * Constructor
     *
     * @param $name string action name
     * @param $title string action title
     */
    function __construct($name = '', $title = '')
    {
        $this->name = $name;
        $this->title = $title;
    }
}

// Build actions.
$WEB_APP['list_action_delete'] = new list_action('delete', text('txt_delete'));
$WEB_APP['list_action_finish'] = new list_action('finish', text('txt_finish'));
$WEB_APP['list_action_move']   = new list_action('move', text('txt_move'));
$WEB_APP['list_action_copy']   = new list_action('move', text('txt_copy'));