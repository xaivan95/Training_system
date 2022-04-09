<?php

class module_403 extends module_base
{
    /**
     * Implementation of module_base::view().
     */
    function view()
    {
        global $WEB_APP;

        $WEB_APP['title'] = text('txt_403_forbidden');
        $WEB_APP['view']->display('403.tpl', text('txt_403_forbidden'));
    }
}

