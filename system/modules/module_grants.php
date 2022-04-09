<?php

/**
 * @see module_base
 */
class module_grants extends module_base
{
    function __construct()
    {
        global $WEB_APP;
        $WEB_APP['title_edit'] = text('txt_edit_grant');
        $WEB_APP['title_delete'] = text('txt_delete_grants');
        $WEB_APP['title_add'] = text('txt_add_grant');
    }

    function get_post_grant()
    {
        $grant = new grant();

        $grant->title = (isset($_POST['title'])) ? trim($_POST['title']) : '';
        $grant->hidden = isset($_POST['hidden']);

        return $grant;
    }

    function on_delete()
    {
        global $WEB_APP;
        global $adodb;
        if (!isset($_POST['selected_row']))
        {
            $this->view();
            exit();
        }

        if (is_confirm_delete_action())
        {
            $result = delete_grants($_POST['selected_row']);
            if (!$result)
            {
                $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_grants')."<br>";
            }
            $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['action'] == 'delete') &&  (!is_delete_action()))
        {
            $WEB_APP['title'] = $WEB_APP['title_delete'];
            $WEB_APP['items'] = get_grants_from_array(array($WEB_APP['id']));
            $WEB_APP['editform'] = FALSE;
            $WEB_APP['list_action'] = 'delete';
        }

        $WEB_APP['title'] = $WEB_APP['title_delete'];
        $WEB_APP['items'] = get_grants_from_array($_POST['selected_row']);
        $WEB_APP['editform'] = FALSE;
        $WEB_APP['list_action'] = 'delete';

        $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
        $WEB_APP['columns'] = array(new column('id', 'id'),
                        new column('grant_title', text('txt_name')),
                        new column('grant_hidden', text('txt_hidden')));

        $WEB_APP['escape'] = TRUE;

        $WEB_APP['submit_title'] = text('txt_delete');


        $WEB_APP['view']->display('list_action.tpl', text('txt_grants'));
    }

    function on_confirm_delete()
    {
        global $WEB_APP;
        global $adodb;

        $result = delete_grants($_POST['selected_row']);
        if (!$result)
        {
            $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_grants')."<br>";
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
        $grant = new grant();

        if (is_confirm_delete_action())
        {
            $result = delete_grants($_POST['selected_row']);
            if (!$result)
            {
                $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_grants')."<br>";
            }
            $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['action'] == 'delete') &&  (!is_delete_action()))
        {
            $grant = get_grant($WEB_APP['id']);
            if (!isset($grant->id))
            {
                redirect($WEB_APP['errorstext']);
            }

            $WEB_APP['title'] = $WEB_APP['title_delete'];
            $WEB_APP['items'] = get_grants_from_array(array($WEB_APP['id']));
            $WEB_APP['editform'] = FALSE;
            $WEB_APP['action'] = 'delete';
        }
        if ($WEB_APP['action'] == 'view')
        {
            // After press Add button.
            if (is_add_form('title'))
            {
                $correct_post = TRUE;

                if (trim($_POST['title']) == "")
                {
                    $WEB_APP['errorstext'] .= text('txt_insert_name')."<br>";
                    $correct_post = FALSE;
                }


                if ($correct_post && (get_grant_id($_POST['title']) != 0))
                {
                    $WEB_APP['errorstext'] .= text('txt_grant_already_exist_insert_another_grant_name')."<br>";

                    $correct_post = FALSE;
                }

                if ($correct_post)
                {
                    add_grant($_POST['title'], isset($_POST['hidden']) ? 1 : 0);
                    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
                    redirect($WEB_APP['errorstext']);
                }
            }

            if (is_delete_action())
            {
                $WEB_APP['title'] = $WEB_APP['title_delete'];
                $WEB_APP['items'] = get_grants_from_array($_POST['selected_row']);
                $WEB_APP['editform'] = FALSE;
                $WEB_APP['action'] = 'delete';
            }
            else
            {
                $WEB_APP['title'] = text('txt_grants');
                // Get sections. On show sections.
                $WEB_APP['items_count'] = get_grants_count(new grant_filter());//db_count(DB_TABLE_GRANT);
                // Pages count.
                $pages = get_pages_count($WEB_APP['items_count'], ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);

                if ($WEB_APP['page'] > $pages)
                {
                    $WEB_APP['page'] = $pages;
                }
                $grants = get_grants($WEB_APP['sort_field'],
                            $WEB_APP['sort_order'],
                            $WEB_APP['page'],
                            $WEB_APP['count'],
                            new grant_filter()
                            );
                $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
                $WEB_APP['items'] = $grants;

                $paginator = new paginator($WEB_APP['page'], $pages);
                $paginator->url = $WEB_APP['script_name'];
                $paginator->url_query_array  = $WEB_APP['url_query_array'];
                $WEB_APP['paginator'] = $paginator;
            }
            $grant = $this->get_post_grant();
        }
        $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
        $WEB_APP['columns'] = array(new column('id', 'id'),
                        new column('grant_title', text('txt_name')),
                        new column('grant_hidden', text('txt_hidden')));

        $WEB_APP['escape'] = TRUE;




        // Form fields.
        $fields = array();
        $fields[] = new field(  TRUE, text('txt_name'), "text", "title",
                    $grant->title, "");
        $fields[] = new field(  FALSE, text('txt_hidden'), "checkbox", "hidden",
                        $grant->hidden == 1, "hidden");

        $WEB_APP['fields'] = $fields;

        $WEB_APP['view']->display('table.tpl', text('txt_grants'));
    }

    function edit()
    {
        global $WEB_APP;
        global $adodb;

        $WEB_APP['title'] = $WEB_APP['title_edit'];
        // After change press.
        $grant = get_grant($WEB_APP['id']);
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        if (!isset($grant->id))
        {
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['id'] >= 0) && is_add_edit_form('title'))
        {
            $correct_post = TRUE;



            if (trim($_POST['title']) == '')
            {
                $WEB_APP['errorstext'] .= text('txt_insert_name')."<br>";
                $correct_post = FALSE;
            }

            $grant_id = get_grant_id($_POST['title']);
            if (!(  ($grant_id == 0) ||
                ($grant_id == $WEB_APP['id'])))
            {
                $WEB_APP['errorstext'] .= text('txt_grant_already_exist_insert_another_grant_name')."<br>";
                $correct_post = FALSE;
            }

            if ($correct_post)
            {
                edit_grant($WEB_APP['id'], $_POST['title'], isset($_POST['hidden']) ? 1 : 0);
                $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            }
            $grant = $this->get_post_grant();
            redirect($WEB_APP['errorstext']);
        }

        $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
        $WEB_APP['columns'] = array(new column('id', 'id'),
                        new column('grant_title', text('txt_name')),
                        new column('grant_hidden', text('txt_hidden')));

        $WEB_APP['escape'] = TRUE;




        // Form fields.
        $fields = array();
        $fields[] = new field(  TRUE, text('txt_name'), "text", "title",
                    $grant->title, "");
        $fields[] = new field(  FALSE, text('txt_hidden'), "checkbox", "hidden",
                        $grant->hidden == 1, "hidden");

        $WEB_APP['fields'] = $fields;

        $WEB_APP['view']->display('table.tpl', text('txt_grants'));
    }
}

