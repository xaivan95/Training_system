<?php

/**
 * Specify xml_item object. Use in xml.php.
 */
class xml_item
{
    /**
     * int xml item depth.
     */
    var $depth;

    /**
     * string xml item name.
     */
    var $name;

    /**
     * string xml item data.
     */
    var $data;

    /**
     * array xml item attributes.
     */
    var $attrs;

    /**
     * string xml item path. nodes path. (NODE1/CHILD_NODE1 ... etc.)
     */
    var $path;
}

