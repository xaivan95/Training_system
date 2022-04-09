<?php

/**
 * Get pages count
 *
 * @param $items int items on the page
 * @param $count int items count
 *
 * @return int pages count
 */
function get_pages_count($items, $count)
{
    return (($count == 0) ? 1 : max(1, ceil($items/$count)));
}

