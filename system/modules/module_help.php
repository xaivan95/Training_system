<?php

/**
 * @see module_base
 */
class module_help extends module_base
{
    function view()
    {
        global $WEB_APP;
        header('Location: '.$WEB_APP['cfg_url'].CFG_HELP_DIR."index.html");
    }
}

