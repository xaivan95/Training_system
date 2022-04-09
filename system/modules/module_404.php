<?php

/**
 * @see module_base
 */
class module_404 extends module_base
{
    /**
     * Implementation of module_base::view().
     */
    function view()
    {
        global $WEB_APP;

        $WEB_APP['title'] = text('txt_404_not_found');
        $WEB_APP['view']->display('404.tpl', text('txt_404_not_found'));
    }

}