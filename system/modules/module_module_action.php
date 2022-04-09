<?php

/**
 * @see module_base
 */
class module_module_action extends module_base
{
    function __construct()
    {
        global $WEB_APP;
        $WEB_APP['title_edit'] = text('txt_edit_module_action');
        $WEB_APP['title_delete'] = text('txt_delete_module_actions');
        $WEB_APP['title_add'] = text('txt_add_module_action');
    }

    function get_post_module_action()
    {
        $module_action = new module_action();

        $module_action->module = '';

        if (isset($_POST['module'])) {
            $module = get_module($_POST['module']);
            if ($module->name != NULL) {
                $module_action->module = text($module->name);
            }
        }

        $module_action->action = (isset($_POST['action'])) ? trim($_POST['action']) : '';

        return $module_action;
    }

    function on_delete()
    {
        global $WEB_APP;
        global $adodb;
        if (!isset($_POST['selected_row'])) {
            $this->view();
            exit();
        }
        //        $modules = get_modules('module_name');
        //        $module_action = new module_action();
        if (is_confirm_delete_action()) {
            $result = delete_module_actions($_POST['selected_row']);
            if (!$result) {
                $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_module_actions') . "<br>";
            }
            $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
            $WEB_APP['title'] = $WEB_APP['title_delete'];
            $WEB_APP['items'] = get_module_actions_from_array(array($WEB_APP['id']));
            $WEB_APP['editform'] = FALSE;
            $WEB_APP['list_action'] = 'delete';
        }

        $WEB_APP['title'] = $WEB_APP['title_delete'];
        $WEB_APP['items'] = get_module_actions_from_array($_POST['selected_row']);
        $WEB_APP['editform'] = FALSE;
        $WEB_APP['list_action'] = 'delete';

        $WEB_APP['columns'] = array(new column('id', 'id'),
            new column('module_name', text('txt_module')),
            new column('action', text('txt_action')));

        $WEB_APP['escape'] = TRUE;

        $WEB_APP['submit_title'] = text('txt_delete');


        $WEB_APP['view']->display('list_action.tpl', text('txt_manage_modules_actions'));
    }

    function on_confirm_delete()
    {
        global $WEB_APP;
        global $adodb;

        $result = delete_module_actions($_POST['selected_row']);
        if (!$result) {
            $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_module_actions') . "<br>";
            $this->view();
            exit();
        }
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        redirect($WEB_APP['errorstext']);
    }

    function view()
    {
        function compare_module_name($a, $b)
        {
            if ($a['module_name'] == $b['module_name']) {
                return 0;
            }
            return ($a['module_name'] < $b['module_name']) ? -1 : 1;
        }

        global $WEB_APP;
        global $adodb;
        $modules = get_modules('module_name');
        uasort($modules, 'compare_module_name');
        $module_action = new module_action();
        if (is_confirm_delete_action()) {
            $result = delete_module_actions($_POST['selected_row']);
            if (!$result) {
                $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_module_actions') . "<br>";
            }
            $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
            $module_action = get_module_action($WEB_APP['id']);
            if (!isset($module_action->id)) {
                redirect($WEB_APP['errorstext']);
            }

            $WEB_APP['title'] = $WEB_APP['title_delete'];
            $WEB_APP['items'] = get_module_actions_from_array(array($WEB_APP['id']));
            $WEB_APP['editform'] = FALSE;
            $WEB_APP['action'] = 'delete';
        }

        if ($WEB_APP['action'] == 'view') {
            // After press Add button.
            if (is_add_form('module')) {
                $correct_post = TRUE;
                if ($_POST['module'] == '') {
                    $WEB_APP['errorstext'] .= text('txt_insert_module') . "<br>";
                    $correct_post = FALSE;
                }
                if (trim($_POST['action']) == "") {
                    $WEB_APP['errorstext'] .= text('txt_insert_action') . "<br>";
                    $correct_post = FALSE;
                }

                if ($correct_post && (get_module_action_id($_POST['module'], $_POST['action']) != 0)) {
                    $WEB_APP['errorstext'] .= text('txt_module_action_already_exist_insert_another_module_action') . "<br>";
                    $correct_post = FALSE;
                }

                if ($correct_post) {
                    add_module_action($_POST['module'], $_POST['action']);
                    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
                    redirect($WEB_APP['errorstext']);
                }
            }

            if (is_delete_action()) {
                $WEB_APP['title'] = $WEB_APP['title_delete'];
                $WEB_APP['items'] = get_module_actions_from_array($_POST['selected_row']);
                $WEB_APP['editform'] = FALSE;
                $WEB_APP['action'] = 'delete';
            } else {
                $WEB_APP['title'] = text('txt_manage_modules_actions');
                // Get sections. On show sections.
                $WEB_APP['items_count'] = get_module_actions_count(new module_action_filter());

                // Pages count.
                $pages = get_pages_count($WEB_APP['items_count'], ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] :
                    $WEB_APP['count']);
                if ($WEB_APP['page'] > $pages) {
                    $WEB_APP['page'] = $pages;
                }
                $module_actiones = get_module_actions($WEB_APP['sort_field'],
                    $WEB_APP['sort_order'],
                    $WEB_APP['page'],
                    $WEB_APP['count'],
                    new module_action_filter()
                );
                $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
                $WEB_APP['items'] = $module_actiones;

                $paginator = new paginator($WEB_APP['page'], $pages);
                $paginator->url = $WEB_APP['script_name'];
                $paginator->url_query_array = $WEB_APP['url_query_array'];
                $WEB_APP['paginator'] = $paginator;
            }
            $module_action = $this->get_post_module_action();
        }

        $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
        $WEB_APP['columns'] = array(new column('id', 'id'),
            new column('module_name', text('txt_module')),
            new column('action', text('txt_action')));

        $WEB_APP['escape'] = TRUE;


        // Form fields.
        $fields = array();

        $fields[] = new field(TRUE, text('txt_module'), "select", "module",
            $module_action->module, "", $modules, 'id', 'module_name', null, FALSE, '', '', 'data-live-search="true"');

        $fields[] = new field(TRUE, text('txt_action'), "text", "action",
            $module_action->action, "");
        $WEB_APP['fields'] = $fields;

        $WEB_APP['view']->display('table.tpl', text('txt_manage_modules_actions'));
    }

    function edit()
    {
        global $WEB_APP;
        global $adodb;

        $modules = get_modules('module_name');

        $WEB_APP['title'] = $WEB_APP['title_edit'];
        // After change press.
        $module_action = get_module_action($WEB_APP['id']);
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        if (!isset($module_action->id)) {
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['id'] >= 0) && is_add_edit_form('module')) {
            $correct_post = TRUE;
            if ($_POST['module'] == '') {
                $WEB_APP['errorstext'] .= text('txt_insert_module') . "<br>";
                $correct_post = FALSE;
            }
            if (trim($_POST['action']) == '') {
                $WEB_APP['errorstext'] .= text('txt_insert_action') . "<br>";
                $correct_post = FALSE;
            }

            $module_action_id = get_module_action_id($_POST['module'], $_POST['action']);
            if (!(($module_action_id == 0) ||
                ($module_action_id == $WEB_APP['id']))
            ) {
                $WEB_APP['errorstext'] .= text('txt_module_action_already_exist_insert_another_module_action') . "<br>";
                $correct_post = FALSE;
            }

            if ($correct_post) {
                edit_module_action($WEB_APP['id'], $_POST['module'], $_POST['action']);
                $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            }
            $module_action = $this->get_post_module_action();
            redirect($WEB_APP['errorstext']);
        }

        $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
        $WEB_APP['columns'] = array(new column('id', 'id'),
            new column('module_name', text('txt_module')),
            new column('title', text('txt_action')));

        $WEB_APP['escape'] = TRUE;


        // Form fields.
        $fields = array();
        $fields[] = new field(TRUE, text('txt_module'), "select", "module",
            $module_action->module, "", $modules, 'id', 'module_name', null, FALSE, '', '', 'data-live-search="true"');

        $fields[] = new field(TRUE, text('txt_action'), "text", "action",
            $module_action->action, '');
        $WEB_APP['fields'] = $fields;

        $WEB_APP['view']->display('table.tpl', text('txt_manage_modules_actions'));
    }

}

