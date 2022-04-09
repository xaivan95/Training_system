<?php

/**
 * @see module_base
 */
class module_sections extends module_base
{
    function __construct()
    {
        global $WEB_APP;
        $WEB_APP['title_edit'] = text('txt_edit_section');
        $WEB_APP['title_delete'] = text('txt_delete_sections');
        $WEB_APP['title_add'] = text('txt_add_section');
    }

    function get_post_section()
    {
        $section = new section();

        $section->name = (isset($_POST['name'])) ? trim($_POST['name']) : '';
        $section->hidden = isset($_POST['hidden']);

        return $section;
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
            $result = delete_sections($_POST['selected_row']);
            if (!$result)
            {
                $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_sections')."<br>";
            }
            $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['action'] == 'delete') &&  (!is_delete_action()))
        {
            $WEB_APP['title'] = $WEB_APP['title_delete'];
            $WEB_APP['items'] = get_sections_from_array(array($WEB_APP['id']));
            $WEB_APP['editform'] = FALSE;
            $WEB_APP['list_action'] = 'delete';
        }

        $WEB_APP['title'] = $WEB_APP['title_delete'];
        $WEB_APP['items'] = get_sections_from_array($_POST['selected_row']);
        $WEB_APP['editform'] = FALSE;
        $WEB_APP['list_action'] = 'delete';

        $WEB_APP['columns'] = array(new column('id', 'id'),
                        new column('section_name', text('txt_name')),
                        new column('section_hidden', text('txt_hidden')));

        $WEB_APP['escape'] = TRUE;

        $WEB_APP['submit_title'] = text('txt_delete');


        $WEB_APP['view']->display('list_action.tpl', '');
    }

    function on_confirm_delete()
    {
        global $WEB_APP;
        global $adodb;

        $result = delete_sections($_POST['selected_row']);
        if (!$result)
        {
            $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_sections')."<br>";
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
        $section = new section();
        if (is_confirm_delete_action())
        {
            $result = delete_sections($_POST['selected_row']);
            if (!$result)
            {
                $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_sections')."<br>";
            }
            $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['action'] == 'delete') &&  (!is_delete_action()))
        {
            $section = get_section($WEB_APP['id']);
            if (!isset($section->id))
            {
                redirect($WEB_APP['errorstext']);
            }

            $WEB_APP['title'] = $WEB_APP['title_delete'];
            $WEB_APP['items'] = get_sections_from_array(array($WEB_APP['id']));
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


                if ($correct_post && (get_section_id($_POST['name']) != 0))
                {
                    $WEB_APP['errorstext'] .= text('txt_section_already_exist_insert_another_section_name')."<br>";
                    $correct_post = FALSE;
                }

                if ($correct_post)
                {
                    add_section($_POST['name'], isset($_POST['hidden']) ? 1 : 0);
                    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
                    redirect($WEB_APP['errorstext']);
                }
            }

            if (is_delete_action())
            {
                $WEB_APP['title'] = $WEB_APP['title_delete'];
                $WEB_APP['items'] = get_sections_from_array($_POST['selected_row']);
                $WEB_APP['editform'] = FALSE;
                $WEB_APP['action'] = 'delete';
            }
            else
            {
                $WEB_APP['title'] = text('txt_sections');
                // Get sections. On show sections.
                $WEB_APP['items_count'] = get_sections_count(new section_filter());//db_count(DB_TABLE_GRANT);

                // Pages count.
                $pages = get_pages_count($WEB_APP['items_count'], ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);

                if ($WEB_APP['page'] > $pages)
                {
                    $WEB_APP['page'] = $pages;
                }
                $sections = get_sections($WEB_APP['sort_field'],
                            $WEB_APP['sort_order'],
                            $WEB_APP['page'],
                            $WEB_APP['count'],
                            new section_filter()
                            );
                $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
                $WEB_APP['items'] = $sections;

                $paginator = new paginator($WEB_APP['page'], $pages);
                $paginator->url = $WEB_APP['script_name'];
                $paginator->url_query_array  = $WEB_APP['url_query_array'];
                $WEB_APP['paginator'] = $paginator;
            }
            $section = $this->get_post_section();
        }

        $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
        $WEB_APP['columns'] = array(new column('id', 'id'),
                        new column('section_name', text('txt_name')),
                        new column('section_hidden', text('txt_hidden')));

        $WEB_APP['escape'] = TRUE;




        // Form fields.
        $fields = array();
        $fields[] = new field(  TRUE, text('txt_name'), "text", "name",
                    $section->name, "");
        $fields[] = new field(  FALSE, text('txt_hidden'), "checkbox", "hidden",
                        $section->hidden == 1, "hidden");



        $WEB_APP['fields'] = $fields;
        $WEB_APP['view']->display('table.tpl', text('txt_sections'));
    }

    function edit()
    {
        global $WEB_APP;
        global $adodb;

        $WEB_APP['title'] = text('txt_edit_section');
        // After change press.
        $section = get_section($WEB_APP['id']);
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        if (!isset($section->id))
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

            $section_id = get_section_id($_POST['name']);
            if (!(  ($section_id == 0) ||
                ($section_id == $WEB_APP['id'])))
            {
                $WEB_APP['errorstext'] .= text('txt_section_already_exist_insert_another_section_name')."<br>";
                $correct_post = FALSE;
            }

            if ($correct_post)
            {
                edit_section($WEB_APP['id'], $_POST['name'], isset($_POST['hidden']) ? 1 : 0);
                $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            }
            $section = $this->get_post_section();
            redirect($WEB_APP['errorstext']);
        }

        $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
        $WEB_APP['columns'] = array(new column('id', 'id'),
                        new column('section_name', text('txt_name')),
                        new column('section_hidden', text('txt_hidden')));

        $WEB_APP['escape'] = TRUE;




        // Form fields.
        $fields = array();
        $fields[] = new field(  TRUE, text('txt_name'), "text", "name",
                    $section->name, "");
        $fields[] = new field(  FALSE, text('txt_hidden'), "checkbox", "hidden",
                        $section->hidden == 1, "hidden");



        $WEB_APP['fields'] = $fields;
        $WEB_APP['view']->display('table.tpl', text('txt_sections'));
    }

}

