<?php

/**
 * @see module_base
 */
class module_category_module extends module_base
{

    function __construct()
    {
        global $WEB_APP;
        $WEB_APP['title_edit'] = text('txt_edit_category_module');
        $WEB_APP['title_delete'] = text('txt_delete_category_modules');
        $WEB_APP['title_add'] = text('txt_add_category_module');
    }

    function get_post_category_module()
    {
        $category_module = new category_module();

        $category_module->category = '';

        if (isset($_POST['category']))
        {
            $category = get_category($_POST['category']);
            if (isset($category->name))
            {
                $category_module->category = text($category->name);
            }
        }
        if (isset($_POST['module']))
        {
            $module = get_module($_POST['module']);
            if (isset($module->name))
            {
                $category_module->module = text($module->name);
            }
        }
        $category_module->position = (isset($_POST['position'])) ? (($_POST['position'] == '') ? '' : $_POST['position']) : '';

        $category_module->hidden = (isset($_POST['hidden'])) ? 1 : 0;

        return $category_module;
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
//        $category_module = new category_module();
//        $categories = get_categories('category_name');
//        $modules = get_modules('module_name');

        if (is_confirm_delete_action())
        {
            $result = delete_category_modules($_POST['selected_row']);
            if (!$result)
            {
                $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_modules')."<br>";
            }
            $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['action'] == 'delete') &&  (!is_delete_action()))
        {
            $WEB_APP['title'] = $WEB_APP['title_delete'];
            $WEB_APP['items'] = get_category_modules_from_array(array($WEB_APP['id']));
            $WEB_APP['editform'] = FALSE;
            $WEB_APP['list_action'] = 'delete';

        }

        $WEB_APP['title'] = $WEB_APP['title_delete'];
        $WEB_APP['items'] = get_category_modules_from_array($_POST['selected_row']);
        $WEB_APP['editform'] = FALSE;
        $WEB_APP['list_action'] = 'delete';

        $WEB_APP['columns'] = array(new column('id', 'id'),
                        new column('category_name', text('txt_category')),
                        new column('module_name', text('txt_module')),
                        new column('position', $WEB_APP['text']['txt_position']),
                        new column('hidden', text('txt_hidden'))
                        );

        $WEB_APP['escape'] = TRUE;

        $WEB_APP['submit_title'] = text('txt_delete');


        $WEB_APP['view']->display('list_action.tpl', text('txt_category_modules'));
    }

    function on_confirm_delete()
    {
        global $WEB_APP;
        global $adodb;

        $result = delete_category_modules($_POST['selected_row']);
        if (!$result)
        {
            $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_modules')."<br>";
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
        $category_module = new category_module();
        $categories = get_categories('category_name', 'ASC');
        $modules = get_modules('module_name', 'ASC');

        if (is_confirm_delete_action())
        {
            $result = delete_category_modules($_POST['selected_row']);
            if (!$result)
            {
                $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_modules')."<br>";
            }
            $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['action'] == 'delete') &&  (!is_delete_action()))
        {
            $category_module = get_category_module($WEB_APP['id']);
            if (!isset($category_module->id))
            {
                redirect($WEB_APP['errorstext']);
            }

            $WEB_APP['title'] = $WEB_APP['title_delete'];
            $WEB_APP['items'] = get_category_modules_from_array(array($WEB_APP['id']));
            $WEB_APP['editform'] = FALSE;
            $WEB_APP['action'] = 'delete';
        }

        if ($WEB_APP['action'] == 'view')
        {
            // After press Add button.
            if (is_add_form('category'))
            {
                $correct_post = TRUE;

                if (trim($_POST['category']) == '')
                {
                    $WEB_APP['errorstext'] .= text('txt_insert_category')."<br>";
                    $correct_post = FALSE;
                }

                if (trim($_POST['module']) == '')
                {
                    $WEB_APP['errorstext'] .= text('txt_insert_module')."<br>";
                    $correct_post = FALSE;
                }

                if ((get_category_module_id($_POST['category'], $_POST['module']) != 0))
                {
                    $WEB_APP['errorstext'] .= text('txt_category_module_already_exist_insert_another_category_module')."<br>";

                    $correct_post = FALSE;
                }

                if ($correct_post)
                {
                    add_category_module($_POST['category'], $_POST['module'], $_POST['position'], isset($_POST['hidden']) ? 1 : 0);
                    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
                    redirect($WEB_APP['errorstext']);
                }
            }

            if (is_delete_action())
            {
                $WEB_APP['title'] = $WEB_APP['title_delete'];
                $WEB_APP['items'] = get_category_modules_from_array($_POST['selected_row']);
                $WEB_APP['editform'] = FALSE;
                $WEB_APP['action'] = 'delete';
            }
            else
            {
                $WEB_APP['title'] = text('txt_category_modules');
                // Get sections. On show sections.
                $WEB_APP['items_count'] = get_category_modules_count(new category_module_filter());

                // Pages count.
                $pages = get_pages_count($WEB_APP['items_count'], ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
                if ($WEB_APP['page'] > $pages)
                {
                    $WEB_APP['page'] = $pages;
                }
                $category_modules = get_category_modules($WEB_APP['sort_field'],
                            $WEB_APP['sort_order'],
                            $WEB_APP['page'],
                            $WEB_APP['count'],
                            new category_module_filter()
                            );
                $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
                $WEB_APP['items'] = $category_modules;

                $paginator = new paginator($WEB_APP['page'], $pages);
                $paginator->url = $WEB_APP['script_name'];
                $paginator->url_query_array  = $WEB_APP['url_query_array'];
                $WEB_APP['paginator'] = $paginator;
            }
            $category_module = $this->get_post_category_module();
        }

        $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
        $WEB_APP['columns'] = array(new column('id', 'id'),
                        new column('category_name', text('txt_category')),
                        new column('module_name', text('txt_module')),
                        new column('position', $WEB_APP['text']['txt_position']),
                        new column('hidden', text('txt_hidden'))
                        );

        $WEB_APP['escape'] = TRUE;




        // Form fields.
        $fields = array();

        $fields[] = new field(  TRUE, text('txt_category'), "select", "category",
                    $category_module->category, "", $categories, 'id', 'category_name');
        $fields[] = new field(  TRUE, text('txt_module'), "select", "module",
                    $category_module->module, "", $modules, 'id', 'module_name', null, FALSE, '', '', 'data-live-search="true"');
        $fields[] = new field(  FALSE, text('txt_position'), "number", "position",
                    $category_module->position, "");
        $fields[] = new field(  FALSE, $WEB_APP['text']['txt_hidden'], "checkbox", "hidden",
                    $category_module->hidden, "hidden");

        $WEB_APP['fields'] = $fields;
        $WEB_APP['view']->display('table.tpl', text('txt_category_modules'));
    }

    function edit()
    {
        global $WEB_APP;
        global $adodb;
        $categories = get_categories('category_name');
        $modules = get_modules('module_name');
        $WEB_APP['title'] = $WEB_APP['title_edit'];
        // After change press.
        $category_module = get_category_module($WEB_APP['id']);
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        if (!isset($category_module->id))
        {
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['id'] >= 0) && is_add_edit_form('category'))
        {
            $correct_post = TRUE;


            if (trim($_POST['category']) == '')
            {
                $WEB_APP['errorstext'] .= text('txt_insert_category')."<br>";
                $correct_post = FALSE;
            }

            if (trim($_POST['module']) == '')
            {
                $WEB_APP['errorstext'] .= text('txt_insert_module')."<br>";
                $correct_post = FALSE;
            }
            $category_module_id = get_category_module_id($_POST['category'], $_POST['module']);
            if (!(  ($category_module_id == 0) ||
                ($category_module_id == $WEB_APP['id'])))
            {
                $WEB_APP['errorstext'] .= text('txt_category_module_already_exist_insert_another_category_module')."<br>";
                $correct_post = FALSE;
            }

            if ($correct_post)
            {
                edit_category_module($WEB_APP['id'], $_POST['category'], $_POST['module'], $_POST['position'], isset($_POST['hidden']) ? 1 : 0);
                $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            }
            $category_module = $this->get_post_category_module();
            redirect($WEB_APP['errorstext']);
        }

        $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
        $WEB_APP['columns'] = array(new column('id', 'id'),
                        new column('category_name', text('txt_category')),
                        new column('module_name', text('txt_module')),
                        new column('position', text('txt_position')),
                        new column('hidden', text('txt_hidden'))
                        );

        $WEB_APP['escape'] = TRUE;




        // Form fields.
        $fields = array();
        $fields[] = new field(  TRUE, text('txt_category'), "select", "category",
                    $category_module->category, "", $categories, 'id', 'category_name');
        $fields[] = new field(  TRUE, text('txt_module'), "select", "module",
                    $category_module->module, "", $modules, 'id', 'module_name', null, FALSE, '', '', 'data-live-search="true"');
        $fields[] = new field(  FALSE, text('txt_position'), "number", "position",
                    $category_module->position, "");
        $fields[] = new field(  FALSE, $WEB_APP['text']['txt_hidden'], "checkbox", "hidden",
                    $category_module->hidden, "hidden");

        $WEB_APP['fields'] = $fields;
        $WEB_APP['view']->display('table.tpl', text('txt_category_modules'));
    }
}

