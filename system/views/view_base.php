<?php

/**
 * Abstract view class for MVC pattern.
 */
abstract class view_base
{
    /**
     * View template.
     *
     * @param $template string template name
     * @param $title string page title
     */
    abstract function display($template, $title);
}

