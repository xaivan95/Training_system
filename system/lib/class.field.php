<?php

/**
 * Specify form field object.
 */
class field
{
    /**
     * string field title.
     */
    var $title;

    /**
     * string field type.
     */
    var $type;

    /**
     * string field name.
     */
    var $name;

    /**
     * string field value.
     */
    var $value;

    /**
     * string field id.
     */
    var $id;

    /**
     * string array for combobox field.
     */
    var $array;

    /**
     * string parameter name from $array for combobox field.
     */
    var $option_value_field;

    /**
     * string parameter name from $array for combobox field.
     */
    var $option_text_field;

    /**
     * string java script function on chage parameter
     */
    var $on_change;

    /**
     * bool is require field
     */
    var $require;

    /**
     * bool is have description
     */
    var $show_description;

    /**
     * @var field additional class
     */
    var $add_class;

    /**
     * @var field data attribute
     */
    var $data_attribute;
    /**
     * @var string accept
     */
    var $accept;

    /**
     * Constructor.
     *
     * @param bool $require
     * @param $title string field title
     * @param $type string field type
     * @param $name string field name
     * @param $value string field value
     * @param $id string field id
     * @param $array array combobox items
     * @param $option_value_field string value parameter name
     * @param $option_text_field string text parameter name
     * @param $on_change string on_change event javascript code
     * @param $show_description bool is have description <div>
     * @param $accept string field accept
     * @param $add_class string additional field class
     * @param $data_attribute string data attribute
     */
    function __construct($require = FALSE, $title = '', $type = '',
            $name = '', $value = '', $id = '',
            $array = array(), $option_value_field = '',
            $option_text_field = '', $on_change = NULL,
            $show_description = FALSE, $accept ='', $add_class = '', $data_attribute='')
    {
        $this->require = $require;
        $this->title = $title;
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
        $this->array = $array;
        $this->option_value_field = $option_value_field;
        $this->option_text_field = $option_text_field;
        $this->on_change = $on_change;
        $this->show_description = $show_description;
        $this->accept = $accept;
        $this->id = $id;
        $this->add_class = $add_class;
        $this->data_attribute = $data_attribute;
    }
}

