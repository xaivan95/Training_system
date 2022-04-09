<?php

/**
 * @see module_base
 */
class module_groups extends module_base
{
    function __construct()
    {
        global $WEB_APP;
        $WEB_APP['title_edit'] = text('txt_edit_group');
        $WEB_APP['title_delete'] = text('txt_delete_groups');
        $WEB_APP['title_add'] = text('txt_add_group');
    }

    function get_post_group()
    {
        $group = new group();

        $group->name = (isset($_POST['name'])) ? trim($_POST['name']) : '';
        $group->description = (isset($_POST['description'])) ? trim($_POST['description']) : '';
        $group->login_available = (isset($_POST['hidden']) ? 1 : 0);
        $group->registration_available = (isset($_POST['registration_available']) ? 1 : 0);

        return $group;
    }

    function on_delete()
    {
        global $WEB_APP;
        global $adodb;
        if (!isset($_POST['selected_row'])) {
            $this->view();
            exit();
        }
        if (is_confirm_delete_action()) {
            $result = delete_groups($_POST['selected_row']);
            if (!$result) {
                $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_groups') . "<br>";
            }
            $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
            $WEB_APP['title'] = $WEB_APP['title_delete'];
            $WEB_APP['items'] = get_groups_from_array(array($WEB_APP['id']));
            $WEB_APP['editform'] = FALSE;
            $WEB_APP['list_action'] = 'delete';

        }

        $WEB_APP['title'] = $WEB_APP['title_delete'];
        $WEB_APP['items'] = get_groups_from_array($_POST['selected_row']);
        $WEB_APP['editform'] = FALSE;
        $WEB_APP['list_action'] = 'delete';

        $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
        $WEB_APP['list_actions'] = array($WEB_APP['list_action_delete']);
        $WEB_APP['columns'] = array(new column('id', 'id'),
            new column('group_name', text('txt_name')),
            new column('group_description', text('txt_description')),
            new column('group_login_available', text('txt_login_available')));

        $WEB_APP['escape'] = TRUE;

        $WEB_APP['submit_title'] = text('txt_delete');


        $WEB_APP['view']->display('list_action.tpl', text('txt_groups'));
    }

    function on_confirm_delete()
    {
        global $WEB_APP;
        global $adodb;

        $result = delete_groups($_POST['selected_row']);
        if (!$result) {
            $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_groups') . "<br>";
            $this->view();
            exit();
        }
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        redirect($WEB_APP['errorstext']);
    }

    function view()
    {
        global $WEB_APP;
        global $adodb;
        $group = new group();
        if (is_confirm_delete_action()) {
            $result = delete_groups($_POST['selected_row']);
            if (!$result) {
                $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_groups') . "<br>";
            }
            $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
            $group = get_group($WEB_APP['id']);
            if (!isset($group->id)) {
                redirect($WEB_APP['errorstext']);
            }

            $WEB_APP['title'] = $WEB_APP['title_delete'];
            $WEB_APP['items'] = get_groups_from_array(array($WEB_APP['id']));
            $WEB_APP['editform'] = FALSE;
            $WEB_APP['action'] = 'delete';

        }
        if ($WEB_APP['action'] == 'view') {
            // After press Add button.
            if (is_add_form('name')) {
                $correct_post = TRUE;

                if (trim($_POST['name']) == "") {
                    $WEB_APP['errorstext'] .= text('txt_insert_name') . "<br>";
                    $correct_post = FALSE;
                }


                if ($correct_post && (get_group_id($_POST['name']) != 0)) {
                    $WEB_APP['errorstext'] .= text('txt_group_already_exist_insert_another_group_name') . "<br>";
                    $group->name = $_POST['name'];
                    $correct_post = FALSE;
                }

                if ($correct_post) {
                    add_group($_POST['name'], $_POST['description'], isset($_POST['hidden']) ? 1 :
                        0, isset($_POST['registration_available']) ? 1 : 0);
                    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
                    redirect($WEB_APP['errorstext']);
                }
            }

            if (is_delete_action()) {
                $WEB_APP['title'] = $WEB_APP['title_delete'];
                $WEB_APP['items'] = get_groups_from_array($_POST['selected_row']);
                $WEB_APP['editform'] = FALSE;
                $WEB_APP['action'] = 'delete';
            } else {
                $WEB_APP['title'] = text('txt_groups');
                // Get sections. On show sections.
                $WEB_APP['items_count'] = get_groups_count(new group_filter());//db_count(DB_TABLE_GROUP);
                // Pages count.
                $pages = get_pages_count($WEB_APP['items_count'], ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] :
                    $WEB_APP['count']);

                if ($WEB_APP['page'] > $pages) {
                    $WEB_APP['page'] = $pages;
                }
                $groups = get_groups($WEB_APP['sort_field'],
                    $WEB_APP['sort_order'],
                    $WEB_APP['page'],
                    $WEB_APP['count'],
                    new group_filter()
                );
                $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
                $WEB_APP['items'] = $groups;

                $paginator = new paginator($WEB_APP['page'], $pages);
                $paginator->url = $WEB_APP['script_name'];
                $paginator->url_query_array = $WEB_APP['url_query_array'];
                $WEB_APP['paginator'] = $paginator;
            }
            $group = $this->get_post_group();
        }
        $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
        $WEB_APP['columns'] = array(new column('id', 'id'),
            new column('group_name', text('txt_name')),
            new column('group_description', text('txt_description')),
            new column('group_login_available', text('txt_login_available')),
            new column('group_registration_available', text('txt_registration_available')));

        $WEB_APP['escape'] = TRUE;


        // Form fields.
        $fields = array();
        $fields[] = new field(TRUE, text('txt_name'), "text", "name", $group->name, "");
        $fields[] = new field(FALSE, text('txt_description'), "text", "description", $group->description, "");
        $fields[] = new field(FALSE, text('txt_login_available'), "checkbox", "hidden", $group->login_available == 1, "hidden");
        $fields[] =
            new field(FALSE, text('txt_registration_available'), "checkbox", "registration_available",
                $group->registration_available == 1, "registration_available");


        $WEB_APP['fields'] = $fields;

        $WEB_APP['view']->display('table.tpl', text('txt_groups'));
    }

    function edit()
    {
        global $WEB_APP;
        global $adodb;
        $WEB_APP['title'] = $WEB_APP['title_edit'];
        // After change press.
        $group = get_group($WEB_APP['id']);
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        if (!isset($group->id)) {
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['id'] >= 0) && is_add_edit_form('name')) {
            $correct_post = TRUE;

            if (trim($_POST['name']) == "") {
                $WEB_APP['errorstext'] .= text('txt_insert_name') . "<br>";
                $correct_post = FALSE;
            }


            $group_id = get_group_id($_POST['name']);
            if (!(($group_id == 0) ||
                ($group_id == $WEB_APP['id']))
            ) {
                $WEB_APP['errorstext'] .= text('txt_group_already_exist_insert_another_group_name') . "<br>";
                $correct_post = FALSE;
            }

            if ($correct_post) {
                edit_group($WEB_APP['id'], $_POST['name'], $_POST['description'], isset($_POST['hidden']) ? 1 :
                    0, isset($_POST['registration_available']) ? 1 : 0);
                $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            }
            $group = $this->get_post_group();
            redirect($WEB_APP['errorstext']);
        }
        // Form fields.
        $fields = array();
        $fields[] = new field(TRUE, text('txt_name'), "text", "name", $group->name, "");
        $fields[] = new field(FALSE, text('txt_description'), "text", "description", $group->description, "");
        $fields[] = new field(FALSE, text('txt_hidden'), "checkbox", "hidden", $group->login_available == 1, "hidden");
        $fields[] = new field(FALSE, text('txt_registration_available'), "checkbox", "registration_available",
            $group->registration_available == 1, "registration_available");


        $WEB_APP['fields'] = $fields;

        $WEB_APP['view']->display('table.tpl', text('txt_groups'));
    }
}

