<?php

/* @param $a
 * @param $b
 * @return int
 * @version $Id: module_modules.php,v 1.3 2008-02-13 20:30:22 oleg Exp $
 */

function cmp_modules($a, $b)
{
    return strcmp($a['name'], $b['name']);
}

/**
 * @see module_base
 */
class module_modules extends module_base
{
    function __construct()
    {
        global $WEB_APP;
        $WEB_APP['title_edit'] = text('txt_edit_module');
        $WEB_APP['title_delete'] = text('txt_delete_modules');
        $WEB_APP['title_add'] = text('txt_add_module');
    }



    function get_modules()
    {
        global $WEB_APP;


        $modules = array();
        $path = $WEB_APP['path'];
        $pattern = '/module_([0-9a-zA-Z_]*)\.php/';
        if ($handle = opendir($path))
        {
            while (($file_name = readdir($handle)) !== FALSE)
            {
                if ((!is_dir($path.'/'.$file_name)) && ($file_name != 'module_base.php'))
                {
                    // Check if file_name like module_xxxxx.php
                    if (preg_match($pattern, $file_name, $matches) !== FALSE)
                    {
                        $modules[$matches[1]] = array('name' => $matches[1]);
                    }
                }
            }
            closedir($handle);
        }

        usort($modules, 'cmp_modules');

        return $modules;
    }

    // $_POST module values.
    function get_post_module()
    {
        $module = new module();
        $module->module = (isset($_POST['module'])) ? trim($_POST['module']) : '';
        $module->name = (isset($_POST['name'])) ? trim($_POST['name']) : '';
        $module->image = (isset($_POST['image'])) ? trim($_POST['image']) : '';
        $module->hidden = isset($_POST['hidden']);

        return $module;
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
            $result = delete_modules($_POST['selected_row']);
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
            $WEB_APP['items'] = get_modules_from_array(array($WEB_APP['id']));
            $WEB_APP['editform'] = FALSE;
            $WEB_APP['list_action'] = 'delete';

        }

        $WEB_APP['title'] = $WEB_APP['title_delete'];
        $WEB_APP['items'] = get_modules_from_array($_POST['selected_row']);
        $WEB_APP['editform'] = FALSE;
        $WEB_APP['list_action'] = 'delete';

        $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
        $WEB_APP['columns'] = array(new column('id', 'id'),
                        new column('module', text('txt_module')),
                        new column('module_name', text('txt_name')),
                        new column('image', text('txt_image')),
                        new column('hidden', text('txt_hidden')));

        $WEB_APP['escape'] = TRUE;

        $WEB_APP['submit_title'] = text('txt_delete');


        $WEB_APP['view']->display('list_action.tpl', text('txt_modules'));
    }

    function on_confirm_delete()
    {
        global $WEB_APP;
        global $adodb;

        $result = delete_modules($_POST['selected_row']);
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
        $module = new module();
        $available_modules = $this->get_modules();
        $translations = get_translations('name', 'ASC', 1, 0, 1);
        if (is_confirm_delete_action())
        {
            $result = delete_modules($_POST['selected_row']);
            if (!$result)
            {
                $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_modules')."<br>";
            }
            $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['action'] == 'delete') &&  (!is_delete_action()))
        {
            $module = get_module($WEB_APP['id']);
            if (!isset($module->id))
            {
                redirect($WEB_APP['errorstext']);
            }

            $WEB_APP['title'] = $WEB_APP['title_delete'];
            $WEB_APP['items'] = get_modules_from_array(array($WEB_APP['id']));
            $WEB_APP['editform'] = FALSE;
            $WEB_APP['action'] = 'delete';

        }

        if ($WEB_APP['action'] == 'view')
        {
            // After press Add button.
            if (is_add_form('name'))
            {
                $correct_post = TRUE;

                if (trim($_POST['module']) == '')
                {
                    $WEB_APP['errorstext'] .= text('txt_insert_module')."<br>";
                    $correct_post = FALSE;
                }

                if (trim($_POST['name']) == "")
                {
                    $WEB_APP['errorstext'] .= text('txt_insert_name')."<br>";
                    $correct_post = FALSE;
                }

                if ((get_module_id_by_module($_POST['module']) != 0))
                {
                    $WEB_APP['errorstext'] .= text('txt_module_already_exist_insert_another_module')."<br>";

                    $correct_post = FALSE;
                }

                if ((get_module_id_by_name($_POST['name']) != 0))
                {
                    $WEB_APP['errorstext'] .= text('txt_module_name_already_exist_insert_another_module_name')."<br>";

                    $correct_post = FALSE;
                }



                if ($correct_post)
                {
                    add_module($_POST['module'], $_POST['name'], $_POST['image'], isset($_POST['hidden']) ? 1 : 0);
                    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
                    redirect($WEB_APP['errorstext']);
                }
            }

            if (is_delete_action())
            {
                $WEB_APP['title'] = $WEB_APP['title_delete'];
                $WEB_APP['items'] = get_modules_from_array($_POST['selected_row']);
                $WEB_APP['editform'] = FALSE;
                $WEB_APP['action'] = 'delete';
            }
            else
            {
                $WEB_APP['title'] = text('txt_modules');
                // Get sections. On show sections.
                $WEB_APP['items_count'] = get_modules_count(new module_filter());

                // Pages count.
                $pages = get_pages_count($WEB_APP['items_count'], ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
                if ($WEB_APP['page'] > $pages)
                {
                    $WEB_APP['page'] = $pages;
                }
                $modules = get_modules($WEB_APP['sort_field'],
                            $WEB_APP['sort_order'],
                            $WEB_APP['page'],
                            $WEB_APP['count'],
                            new module_filter()
                            );
                $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
                $WEB_APP['items'] = $modules;

                $paginator = new paginator($WEB_APP['page'], $pages);
                $paginator->url = $WEB_APP['script_name'];
                $paginator->url_query_array  = $WEB_APP['url_query_array'];
                $WEB_APP['paginator'] = $paginator;
            }
            $module = $this->get_post_module();
        }

        $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
        $WEB_APP['columns'] = array(new column('id', 'id'),
                        new column('module', text('txt_module')),
                        new column('module_name', text('txt_name')),
                        new column('image', text('txt_image')),
                        new column('hidden', text('txt_hidden')));

        $WEB_APP['escape'] = TRUE;




        // Form fields.
        $fields = array();
        $fields[] = new field(  TRUE, text('txt_module'), "select", "module",
                    $module->module, "", $available_modules, 'name', 'name', null, FALSE, '', '', 'data-live-search="true"');
        $fields[] = new field(  TRUE, text('txt_name'), "select", "name",
                    $module->name, "", $translations, 'name', 'name', null, FALSE, '', '', 'data-live-search="true"');
        $fields[] = new field(  FALSE, text('txt_image'), "text", "image",
                    $module->image, "");
        $fields[] = new field(  FALSE, text('txt_hidden'), "checkbox", "hidden",
                        $module->hidden == 1, "hidden");

        $WEB_APP['fields'] = $fields;

        $WEB_APP['view']->display('table.tpl', text('txt_modules'));
    }

    function edit()
    {
        global $WEB_APP;
        global $adodb;
        $available_modules = $this->get_modules();
        $translations = get_translations('name', 'ASC', 1, 0, 1);
        $WEB_APP['title'] = $WEB_APP['title_edit'];
        // After change press.
        $module = get_module($WEB_APP['id']);
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        if (!isset($module->id))
        {
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['id'] >= 0) && is_add_edit_form('name'))
        {
            $correct_post = TRUE;



            if (trim($_POST['module']) == '')
            {
                $WEB_APP['errorstext'] .= text('txt_insert_module')."<br>";
                $correct_post = FALSE;
            }

            if (trim($_POST['name']) == "")
            {
                $WEB_APP['errorstext'] .= text('txt_insert_name')."<br>";
                $correct_post = FALSE;
            }



            $module_id = get_module_id_by_name($_POST['name']);
            if (!(  ($module_id == 0) ||
                ($module_id == $WEB_APP['id'])))
            {
                $WEB_APP['errorstext'] .= text('txt_module_name_already_exist_insert_another_module_name')."<br>";
                $correct_post = FALSE;
            }

            $module_id = get_module_id_by_module($_POST['module']);
            if (!(  ($module_id == 0) ||
                ($module_id == $WEB_APP['id'])))
            {
                $WEB_APP['errorstext'] .= text('txt_module_already_exist_insert_another_module')."<br>";
                $correct_post = FALSE;
            }

            if ($correct_post)
            {
                edit_module($WEB_APP['id'], $_POST['module'], $_POST['name'], $_POST['image'], isset($_POST['hidden']) ? 1 : 0);
                $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            }
            $module = $this->get_post_module();
            redirect($WEB_APP['errorstext']);
        }

        $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
        $WEB_APP['columns'] = array(new column('id', 'id'),
                        new column('module', text('txt_module')),
                        new column('module_name', text('txt_name')),
                        new column('image', text('txt_image')),
                        new column('hidden', text('txt_hidden')));

        $WEB_APP['escape'] = TRUE;




        // Form fields.
        $fields = array();
        $fields[] = new field(  TRUE, text('txt_module'), "select", "module",
                    $module->module, "", $available_modules, 'name', 'name');
        $fields[] = new field(  TRUE, text('txt_name'), "select", "name",
                    $module->name, "", $translations, 'name', 'name');
        $fields[] = new field(  FALSE, text('txt_image'), "text", "image",
                    $module->image, "");
        $fields[] = new field(  FALSE, text('txt_hidden'), "checkbox", "hidden",
                        $module->hidden == 1, "hidden");

        $WEB_APP['fields'] = $fields;

        $WEB_APP['view']->display('table.tpl', text('txt_modules'));
    }

}

