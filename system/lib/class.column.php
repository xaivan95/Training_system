<?php

/**
 * Specify column object.
 */
class column
{
    /**
     * string column title.
     */
    var $title;

    /**
     * string column name
     */
    var $name;

    /**
     * Constructor.
     *
     * @param string $title column
     * @param string $name column
     */
    function __construct($title = '', $name = '')
    {
        $this->title = $title;
        $this->name = $name;
    }
}

