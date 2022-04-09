<?php

/**
 * @see module_base
 */
class module_about extends module_base
{
    function view()
    {
        global $WEB_APP;

        $information_items = array(); 
        $information_items["Version"] = CFG_VERSION;
		$information_items["VersionDate"] = date("j F Y", strtotime(CFG_VERSION_DATE));  
        $WEB_APP['information_items'] = $information_items;
        $WEB_APP['view']->display('about.tpl', text('txt_about'));
    }
}

