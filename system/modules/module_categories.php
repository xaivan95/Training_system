<?php

/**
 * @see module_base
 */
class module_categories extends module_base
{

    function __construct()
    {
        global $WEB_APP;
        $WEB_APP['title_edit'] = text('txt_edit_category');
        $WEB_APP['title_delete'] = text('txt_delete_categories');
        $WEB_APP['title_add'] = text('txt_add_category');
    }

    // $_POST category values.
    function get_post_category()
    {
        $category = new category();

        $category->name = (isset($_POST['name'])) ? trim($_POST['name']) : '';
        $category->position = (isset($_POST['position'])) ? (($_POST['position'] == '') ? '' : $_POST['position']) : '';
        $category->hidden = (isset($_POST['hidden'])) ? 1 : 0;

        return $category;
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
            $result = delete_categories($_POST['selected_row']);
            if (!$result)
            {
                $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_categories')."<br>";
            }
            $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['action'] == 'delete') &&  (!is_delete_action()))
        {
            $WEB_APP['title'] = $WEB_APP['title_delete'];
            $WEB_APP['items'] = get_categories_from_array(array($WEB_APP['id']));
            $WEB_APP['editform'] = FALSE;
            $WEB_APP['list_action'] = 'delete';

        }

        $WEB_APP['title'] = $WEB_APP['title_delete'];
        $WEB_APP['items'] = get_categories_from_array($_POST['selected_row']);
        $WEB_APP['editform'] = FALSE;
        $WEB_APP['list_action'] = 'delete';

        $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
        $WEB_APP['columns'] = array(new column('id', 'id'),
                        new column('category_name', text('txt_name')),
                        new column('position', text('txt_position')),
                        new column('hidden', text('txt_hidden')) );

        $WEB_APP['escape'] = TRUE;

        $WEB_APP['submit_title'] = text('txt_delete');


        $WEB_APP['view']->display('list_action.tpl', text('txt_categories'));
    }

    function on_confirm_delete()
    {
        global $WEB_APP;
        global $adodb;

        $result = delete_categories($_POST['selected_row']);
        if (!$result)
        {
            $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_categories')."<br>";
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
        $category = new category();
        $translations = get_translations('name', 'ASC', 1, 0, $WEB_APP['settings']['language_id']);
        if (is_confirm_delete_action())
        {
            $result = delete_categories($_POST['selected_row']);
            if (!$result)
            {
                $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_categories')."<br>";
            }
            $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['action'] == 'delete') &&  (!is_delete_action()))
        {
            $category = get_category($WEB_APP['id']);
            if (!isset($category->id))
            {
                redirect($WEB_APP['errorstext']);
            }

            $WEB_APP['title'] = $WEB_APP['title_delete'];
            $WEB_APP['items'] = get_categories_from_array(array($WEB_APP['id']));
            $WEB_APP['editform'] = FALSE;
            $WEB_APP['action'] = 'delete';

        }
        if ($WEB_APP['action'] == 'view')
        {
            // After press Add button.
            if (is_add_form('name'))
            {
                $correct_post = TRUE;

                if (trim($_POST['name']) == "")
                {
                    $WEB_APP['errorstext'] .= text('txt_insert_name')."<br>";
                    $correct_post = FALSE;
                }


                if ($correct_post && (get_category_id($_POST['name']) != 0))
                {
                    $WEB_APP['errorstext'] .= text('txt_category_already_exist_insert_another_category_name')."<br>";
                    $category->name = $_POST['name'];
                    $correct_post = FALSE;
                }

                if ($correct_post)
                {
                    add_category($_POST['name'], $_POST['position'], isset($_POST['hidden']) ? 1: 0);
                    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
                    redirect($WEB_APP['errorstext']);
                }
            }

            if (is_delete_action())
            {
                $WEB_APP['title'] = $WEB_APP['title_delete'];
                $WEB_APP['items'] = get_categories_from_array($_POST['selected_row']);
                $WEB_APP['editform'] = FALSE;
                $WEB_APP['action'] = 'delete';
            }
            else
            {
                $WEB_APP['title'] = text('txt_categories');
                // Get sections. On show sections.
                $WEB_APP['items_count'] = get_categories_count(new category_filter());//db_count(DB_TABLE_CATEGORY);


                // Pages count.
                $pages = get_pages_count($WEB_APP['items_count'], ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);

                if ($WEB_APP['page'] > $pages)
                {
                    $WEB_APP['page'] = $pages;
                }
                $categories = get_categories($WEB_APP['sort_field'],
                                $WEB_APP['sort_order'],
                                $WEB_APP['page'],
                                $WEB_APP['count'],
                            new category_filter()
                            );
                $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
                $WEB_APP['items'] = $categories;

                $paginator = new paginator($WEB_APP['page'], $pages);
                $paginator->url = $WEB_APP['script_name'];
                $paginator->url_query_array  = $WEB_APP['url_query_array'];
                $WEB_APP['paginator'] = $paginator;
            }
            $category = $this->get_post_category();
        }

        $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
        $WEB_APP['columns'] = array(new column('id', 'id'),
                        new column('category_name', text('txt_name')),
                        new column('position', text('txt_position')),
                        new column('hidden', text('txt_hidden')) );

        $WEB_APP['escape'] = TRUE;




        // Form fields.
        $fields = array();
        $fields[] = new field(  TRUE, text('txt_name'), "select", "name", $category->name, "", $translations,
            'name', 'name', null, FALSE, '', '', 'data-live-search="true"');
        $fields[] = new field(  FALSE, text('txt_position'), "number", "position", $category->position, "");
        $fields[] = new field(  FALSE, text('txt_hidden'), "checkbox", "hidden", $category->hidden, "hidden");
        $WEB_APP['fields'] = $fields;
        $WEB_APP['view']->display('table.tpl', text('txt_categories'));
    }

    function edit()
    {
        global $WEB_APP;
        global $adodb;
        $translations = get_translations('name', 'ASC', 1, 0, 1);
        $WEB_APP['title'] = $WEB_APP['title_edit'];
        // After change press.
        $category = get_category($WEB_APP['id']);
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        if (!isset($category->id))
        {
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['id'] >= 0) && is_add_edit_form('name'))
        {
            $correct_post = TRUE;


            if (trim($_POST['name']) == '')
            {
                $WEB_APP['errorstext'] .= text('txt_insert_name')."<br>";
                $correct_post = FALSE;
            }

            $category_id = get_category_id($_POST['name']);
            if (!(  ($category_id == 0) ||
                ($category_id == $WEB_APP['id'])))
            {
                $WEB_APP['errorstext'] .= text('txt_category_already_exist_insert_another_category_name')."<br>";
                $correct_post = FALSE;
            }

            if ($correct_post)
            {
                edit_category($WEB_APP['id'], $_POST['name'], $_POST['position'], isset($_POST['hidden']) ? 1 : 0);
                $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            }
            $category = $this->get_post_category();
            redirect($WEB_APP['errorstext']);
        }



        $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
        $WEB_APP['columns'] = array(new column('id', 'id'),
                        new column('type', text('txt_type')),
                        new column('category_name', text('txt_name')),
                        new column('position', text('txt_position')),
                        new column('hidden', text('txt_hidden')) );

        $WEB_APP['escape'] = TRUE;




        // Form fields.
        $fields = array();
        $fields[] = new field(TRUE, text('txt_name'), "select", "name", $category->name, "", $translations, 'name', 'name', null, FALSE, '', '', 'data-live-search="true"');
        $fields[] = new field(FALSE, text('txt_position'), "number", "position", $category->position, "");
        $fields[] = new field(FALSE, text('txt_hidden'), "checkbox", "hidden", $category->hidden, "hidden");
        $WEB_APP['fields'] = $fields;
        $WEB_APP['view']->display('table.tpl', text('txt_categories'));
    }
}

