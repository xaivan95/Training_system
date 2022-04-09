<?php

/**
 * Determine whether an email is correct.
 *
 * @param $str string email
 *
 * @return bool TRUE if email is correct ; FALSE otherwise.
 */
function is_email($str) 
{
    return preg_match("`^\w+(?:[.-]\w+)* @ [^.]+ (\.[^@^.]+)+$`x", $str);
}

