<?php

/**
 * Class for view smarty templates.
 * @see view_base
 */
class view_smarty extends view_base
{
    /**
     * smarty object
     */
    protected $smarty;

    /**
     * __construct
     *
     */
    function __construct()
    {
        global $WEB_APP;
        $WEB_APP['sql_time'] = query::get_all_time();
        $WEB_APP['sql_count'] = query::get_count();
        $WEB_APP['requests'] = query::get_requests();
        $this->smarty = new Smarty();
        $this->smarty->compile_check = TRUE;
        $this->smarty->debugging = FALSE;
        $this->smarty->caching = FALSE;
        $this->smarty->setTemplateDir(CFG_TEMPLATES_DIR);
        $this->smarty->setCompileDir(CFG_COMPILE_DIR);
        $this->smarty->assign('text', $WEB_APP['text']);
        //smarty3 $WEB_APP['view_version'] = $this->smarty->_version;
    }

    /**
     * Implementation of view_base::display().
     *
     * @param string $template
     * @param string $title
     * @param bool $only  TRUE - show only template, FALSE - show main.tpl
     * @see main.tpl
     */
    function display($template, $title, $only = FALSE)
    {
        global $WEB_APP;
        $WEB_APP['sql_time'] = query::get_all_time();
        $WEB_APP['sql_count'] = query::get_count();
        $WEB_APP['requests'] = query::get_requests();
        $row_actions_access = FALSE;
        if (!isset($WEB_APP['actions']))
        {
            $WEB_APP['actions'] = array();
        }
        if (isset($WEB_APP['row_actions']))
        {
            foreach($WEB_APP['row_actions'] as $action)
            {
                $row_actions_access = $row_actions_access || in_array($action->name, $WEB_APP['actions']);
            }
        }
        $WEB_APP['row_actions_access'] = $row_actions_access;

        $list_actions_access = FALSE;

        if (isset($WEB_APP['list_actions']))
        {
            foreach($WEB_APP['list_actions'] as $action)
            {
                $list_actions_access = $list_actions_access || in_array($action->name, $WEB_APP['actions']);
            }
        }
        $WEB_APP['list_actions_access'] = $list_actions_access;

        if (isset($WEB_APP['columns']))
        {
            $WEB_APP['columns_count'] =
                count($WEB_APP['columns']);
        }

        if (isset($_SESSION['user_login']) &&
            ($_SESSION['user_login'] != 'anonymous') &&
            isset($WEB_APP['settings']['show_login_info']) &&
            ($WEB_APP['settings']['show_login_info'] == 1)
           )
        {
            $WEB_APP['user_info_title'] = $_SESSION['user_info'].' - ';			
        }
        else
        {
            $WEB_APP['user_info_title'] = '';
        }
        $WEB_APP['user_info'] = $_SESSION['user_info'];
		if (isset($_SESSION['user_login']))
			$WEB_APP['user_info_login'] = $_SESSION['user_login'];
        $WEB_APP['timer'] = timer_stop();
        $this->smarty->assign('WEB_APP', $WEB_APP);
        $this->smarty->assign("title", $title);
        $this->smarty->assign("main_module", $template);

        if ($only)
        {
            $this->smarty->display($template);
        }
        else
        {
            $this->smarty->display("main.tpl");
        }
    }
}

