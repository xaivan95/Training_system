<?php

/**
 * @see module_base
 */
class module_logout extends module_base
{
    function view()
    {
        global $WEB_APP;
        session_destroy();
        header('Location: '.$WEB_APP['cfg_url']);
        exit();
    }
}

