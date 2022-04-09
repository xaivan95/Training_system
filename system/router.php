<?php 
 //Начало паттерна MVK
class router
{
    //устанавливаем путь к модулям
    function set_module_path($path)
    {
        global $WEB_APP;
        $WEB_APP['path'] = CFG_PATH . '/' . $path;
        require_once $WEB_APP['path'] . '/module_base.php'; // подключаем базовый модуль
    }

    /**
     * Проверьте параметры получения, модуль, действие и аргументы строки url.
     *
     * @param string $module module name
     * @param string $file module file
     * @param string $action action name
     * @param string $args another arguments of url string
     */
    function get_module(&$module, &$file, &$action, &$args)
    {
        if (isset($_GET['module'])) {
            $module = $_GET['module'];
            if (!is_string($module)) {
                $module = '404';
            }
        } else {
            $module = 'index';
        }
        if ($module == 'base') {
            $module = 'index';
        }

        global $WEB_APP;
        $WEB_APP['default_order'] = DEFAULT_ORDER;

        if (isset($WEB_APP['help'][$module])) {
            $help_item = $WEB_APP['help'][$module];
        } else {
            $help_item = '';
        }

        $WEB_APP['help_url'] = CFG_URL . CFG_HELP_DIR . $help_item . ".html";
        $file = $WEB_APP['path'] . '/module_' . $module . '.php';
        if (!file_exists($file)) {
            $file = $WEB_APP['path'] . '/module_404.php';
            $module = '404';
        }
        $WEB_APP['module'] = $module;
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            if (!is_string($action)) {
                $action = 'view';
            }
        } else {
            $action = 'view';
        }

        $WEB_APP['action'] = $action;
        if ($WEB_APP['action'] == 'view') {
            $WEB_APP['submit_title'] = $WEB_APP['text']['txt_add'];

        }
        if ($WEB_APP['action'] == 'edit') {
            $WEB_APP['submit_title'] =
                $WEB_APP['text']['txt_change'];

        }
        ##############################################################
        if (isset($_GET['page'])) {
            $t_page = max((int)$_GET['page'], 1);
        } else {
            $t_page = 1;
        }

        if ($t_page < 1) {
            $t_page = 1;
        }

        $WEB_APP['page'] = $t_page;

        // $r_sort_field- bool = передаётся параметр sort?
        if ($r_sort_field = isset($_GET['sort'])) {
            $t_sort_field = $_GET['sort'];
           // $_SESSION['sort_field'] = $t_sort_field;
        } else {
           // if (isset($_SESSION['sort_field'])) $t_sort_field = $_SESSION['sort_field'];
           // else
                $t_sort_field = '';
        }

        $WEB_APP['sort_field'] = $t_sort_field;

        // $r_sort_order- bool = передаётся параметр order?
        $t_sort_order_array = array('ASC', 'DESC');
        if ($r_sort_order = isset($_GET['order'])) {
            $p_sort_order = $_GET['order'];
            if (in_array($p_sort_order, $t_sort_order_array)) {
                $t_sort_order = $p_sort_order;
            } else {
                $t_sort_order = DEFAULT_ORDER;
            }
            $_SESSION['sort_order'] = $t_sort_order;
        } else {
            if (isset($_SESSION['sort_order']) && in_array($_SESSION['sort_order'], $t_sort_order_array))
                $t_sort_order = $_SESSION['sort_order'];
            else
                $t_sort_order = DEFAULT_ORDER;
        }

        $WEB_APP['sort_order'] = $t_sort_order;

        // $r_field_field- bool = передаётся параметр field?
        if ($r_field_field = isset($_GET['field'])) {
            $t_field_field = is_string($_GET['field']) ? $_GET['field'] : '';
        } else {
            $t_field_field = '';
        }

        $WEB_APP['field_field'] = $t_field_field;

        // $r_text_field- bool = передаётся параметр text?
        if ($r_text_field = isset($_GET['text'])) {
            $t_text_field = is_string($_GET['text']) ? $_GET['text'] : '';
        } else {
            $t_text_field = '';
        }

        $WEB_APP['text_field'] = $t_text_field;

        // $r_id_field- bool = передаётся параметр id?
        if ($r_id_field = isset($_GET['id'])) {
            $t_id_field = $_GET['id'];
        } else {
            $t_id_field = '';
        }
        // Items per page.
        $t_count_array = array(0, 250, 100, 50, 25, 10, 5);
        if (!in_array($WEB_APP['settings']['items_per_page'], $t_count_array)) {
            $WEB_APP['settings']['items_per_page'] = 5;
            setting_set('items_per_page',
                $WEB_APP['settings']['items_per_page']);
        }

        if ($WEB_APP['settings']['show_items_per_page']) {
            if (isset($_GET['count'])) {
                $p_count = (int)$_GET['count'];
                if ($_GET['count'] ==
                    $WEB_APP['text']['txt_all']
                )
                    $p_count = $WEB_APP['text']['txt_all'];
            } else
                $p_count = (isset($_SESSION['count']) ?
                    $_SESSION['count'] :
                    $WEB_APP['settings']['items_per_page']);

            if (in_array($p_count, $t_count_array)) {
                $t_count = $p_count;
            } else
                $t_count = $WEB_APP['settings']['items_per_page'];
        } else {
            $t_count = $WEB_APP['settings']['items_per_page'];
        }

        $parse_url_array = parse_url(CFG_URL);
        $WEB_APP['script_name'] =
            CFG_URL . '?module=' . $WEB_APP['module'];


        if (isset($_GET['id'])) {
            $WEB_APP['id'] = $_GET['id'];
            $action_url =
                $WEB_APP['script_name'] .
                '&action=' . $action .
                '&id=' . $WEB_APP['id'];
        } else {
            $WEB_APP['id'] = -1;
            $action_url = '';
        }

        $url_query_array = array();
        $url_query_array[] = "action=$action";
        if ($r_sort_field)
            $url_query_array[] = "sort=$t_sort_field";
        if ($r_sort_order)
            $url_query_array[] = "order=$t_sort_order";
        if ($r_field_field)
            $url_query_array[] = "field=$t_field_field";
        if ($r_text_field)
            $url_query_array[] = "text=$t_text_field";
        if ($r_id_field)
            $url_query_array[] = "id=$t_id_field";

        $WEB_APP['url_query_array'] = $url_query_array;
        $_SESSION['count'] = $t_count;
        $WEB_APP['count'] = $t_count;
        $WEB_APP['count_array'] = $t_count_array;
        $WEB_APP['action_url'] = $action_url;
        $WEB_APP['request_uri'] = $_SERVER['REQUEST_URI'];
        $WEB_APP['field'] = $WEB_APP['field_field'];

        $args = $_POST;
    }

    //загрузка модулей
    function delegate()
    {
        global $WEB_APP;
        $this->get_module($module, $file, $action, $args);
        $WEB_APP['module'] = $module;

        $login_file = $WEB_APP['path'] . '/module_login.php';
        require_once $login_file;
        $login = new module_login();
        $methods = get_class_methods($login);
        $action_exist = in_array($action, $methods);
        if (!$action_exist) {
            $login->view();
        } else {
            $login->$action();
        }
        if ($login_file == $file) {
            return;
        }
        require_once $file;
        $class_name = 'module_' . $module;
        $class = new $class_name();

        $methods = get_class_methods($class);
        $action_exist = in_array($action, $methods);
        if (!$action_exist) {
            $WEB_APP['action'] = 'view';
            $action = 'view';
        }

        if (isset($args['list_action'])) {
            $actions = get_user_actions($_SESSION['user_login'], $_SESSION['user_password'], $module);
            if (in_array($args['list_action'], $actions)) {
                $list_action = 'on_' . $args['list_action'];
                $class->$list_action();
            } else {
                $class->view();
            }
        } else {
            $class->$action();
        }
    }
}

