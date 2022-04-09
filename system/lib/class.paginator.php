<?php

/**
 * Specify paginator object.
 */
class paginator
{
    /**
     * int current page
     */
    var $current_page;

    /**
     * int last page
     */
    var $last_page;

    /**
     * string url
     */
    var $url;

    /**
     * strings array query.
     */
    var $url_query_array;

    /**
     * Constructor.
     *
     * @param $current_page int current page
     * @param $last_page int last page
     */
    function __construct($current_page = 0, $last_page = 0)
    {
        $this->current_page = $current_page;
        $this->last_page = $last_page;
    }

    /**
     * Get pages array.
     *
     * @return array
     */
    function get_pages_array()
    {
        if (($this->current_page == $this->last_page) &&
            ($this->last_page == 1))
        {
            return array();
        }

        if ($this->current_page > $this->last_page)
            $this->current_page = $this->last_page;

        $begin_range = $this->current_page-5;
        $end_range = $this->current_page+4;

        $array = array();

        if ($begin_range < 4)
        {
            $begin_range = 1;
        }
        else
        {
            $array[] = 1;
            $array[] = 2;
            $array[] = 0;
        }

        if ($end_range > $this->last_page-3)
            $end_range = $this->last_page;

        $array = array_merge($array,
                range($begin_range, $end_range));

        if ($end_range != $this->last_page)
        {
            $array[] = 0;
            $array[] = $this->last_page-1;
            $array[] = $this->last_page;
        }

        return $array;
    }

    /**
     * Get previous page.
     *
     * @return int previous page number
     */
    function get_previous_page()
    {
        if ($this->current_page < 1)
            return 1;

        return $this->current_page - 1;
    }

    /**
     * Get next page.
     *
     * @return int next page number
     */
    function get_next_page()
    {
        if ($this->current_page > $this->last_page - 1)
            return $this->last_page;

        return $this->current_page + 1;
    }

    /**
     * Get url page.
     *
     * @param $num int page number
     *
     * @return string page url.
     */
    function get_url_page($num)
    {
        $tmp_url = $this->url;
        foreach($this->url_query_array as $item)
        {
            $tmp_url .= "&amp;";
            $tmp_url .= $item;
        }

        if ($num == 1)
            return $tmp_url;


        $tmp_url .= "&amp;";
        $tmp_url .= "page=$num";

        return $tmp_url;
    }

}

