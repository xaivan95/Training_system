<?php

class title
{
    /**
     * string title name
     */
    var $name;

    /**
     * string title value
     */
    var $value;

    /**
     * Constructor.
     *
     * @param $name string title name
     * @param $value string title value
     */
    function __construct($name = "", $value = "")
    {
        $this->name = $name;
        $this->value = $value;
    }
}

