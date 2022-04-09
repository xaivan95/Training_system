<?php

/**
 * @see module_base
 */
class module_translations extends module_base
{
    var $language_id;
    var $russian_translations;

    function __construct()
    {
        global $WEB_APP;
        $WEB_APP['title_add'] = text('txt_add_translation');
        $WEB_APP['title_edit'] = text('txt_edit_translation');
        $WEB_APP['title_delete'] = text('txt_delete_translations');
        $this->russian_translations = get_translations('name', 'ASC', 1, 0, 1);
        // Language filter
        $t_languages_array = array('all');
        $languages = get_unhidden_languages();
        foreach($languages as $language)
        {
            $t_languages_array[] = $language['short_name'];
        }

        if (isset($_GET['language']))
        {
            $p_language = $_GET['language'];
        }
        else
        {
            if (isset($_SESSION['language']))
            {
                $p_language = $_SESSION['language'];
            }
            else
            {
                $p_language = 'all';
            }
        }
        if (in_array($p_language, $t_languages_array))
        {
            $t_language = $p_language;
        }
        else
        {
            $t_language = 'all';
        }

        $_SESSION['language'] = $t_language;

        if ($t_language == 'all')
        {
            $this->language_id = 0; // All languages
            $WEB_APP['language'] = '';
        }
        else
        {
            $this->language_id = get_language_id_by_short_name($t_language);
            $lng = get_language($this->language_id);
            $WEB_APP['language'] = $lng->short_name;
        }
    }

    function get_post_translation()
    {
        $translation = new translation();

        if ($this->language_id == 0)
        {
            $language = get_language(isset($_POST['language']) ? trim($_POST['language']) : '');
            $translation->language = (isset($language->name) ? $language->name : '');
        }
        else
        {
            $translation->language = (isset($_POST['language'])) ? trim($_POST['language']) : '';
        }

        if ($this->language_id < 2)
        {
            $translation->name = (isset($_POST['name'])) ? trim($_POST['name']) : '';
        }
        else
        {
            $tmp = get_translation((isset($_POST['name'])) ? trim($_POST['name']) : '');
            $translation->name = (isset($tmp->name) ? $tmp->name : '');
        }
        $translation->text = (isset($_POST['text'])) ? trim($_POST['text']) : '';

        return $translation;
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
        $languages = get_unhidden_languages();
        $WEB_APP['languages'] = $languages;

        if (is_confirm_delete_action())
        {
            $result = delete_translations($_POST['selected_row']);
            if (!$result)
            {
                $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_translations')."<br>";
            }
            $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['action'] == 'delete') &&  (!is_delete_action()))
        {
            $WEB_APP['title'] = $WEB_APP['title_delete'];
            $WEB_APP['items'] = get_translations_from_array(array($WEB_APP['id']));
            $WEB_APP['editform'] = FALSE;
            $WEB_APP['list_action'] = 'delete';
        }

        $WEB_APP['title'] = $WEB_APP['title_delete'];
        $WEB_APP['items'] = get_translations_from_array($_POST['selected_row']);
        $WEB_APP['editform'] = FALSE;
        $WEB_APP['list_action'] = 'delete';

        $WEB_APP['columns'] = array(new column('id', 'id'),
                    new column('language', text('txt_language')),
                    new column('name', text('txt_name')),
                    new column('text', text('txt_translation')));

        $WEB_APP['escape'] = TRUE;

        $WEB_APP['submit_title'] = text('txt_delete');


        $WEB_APP['view']->display('list_action.tpl', text('txt_translations'));
    }

    function on_confirm_delete()
    {
        global $WEB_APP;
        global $adodb;

        $result = delete_translations($_POST['selected_row']);
        if (!$result)
        {
            $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_translations')."<br>";
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
        $translation = new translation();
        $languages = get_unhidden_languages();
        $WEB_APP['languages'] = $languages;

        if (is_confirm_delete_action())
        {
            $result = delete_translations($_POST['selected_row']);
            if (!$result)
            {
                $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_translations')."<br>";
            }
            $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['action'] == 'delete') &&  (!is_delete_action()))
        {
            $translation = get_translation($WEB_APP['id']);
            if (!isset($translation->id))
            {
                redirect($WEB_APP['errorstext']);
            }

            $WEB_APP['title'] = $WEB_APP['title_delete'];
            $WEB_APP['items'] = get_translations_from_array(array($WEB_APP['id']));
            $WEB_APP['editform'] = FALSE;
            $WEB_APP['action'] = 'delete';
        }

        if ($WEB_APP['action'] == 'view')
        {
            // After press Add button.
            if (is_add_form('name'))
            {
                $correct_post = TRUE;

                if ($this->language_id == 0)
                {
                    if (trim($_POST['language']) == '')
                    {
                        $WEB_APP['errorstext'] .= text('txt_insert_language')."<br>";
                        $correct_post = FALSE;
                    }
                }
                if ($this->language_id < 2)
                {
                    if (trim($_POST['name']) == "")
                    {
                        $WEB_APP['errorstext'] .= text('txt_insert_name')."<br>";
                        $correct_post = FALSE;
                    }
                }
                else
                {
                    if (trim($_POST['name']) == 0)
                    {
                        $WEB_APP['errorstext'] .= text('txt_insert_name')."<br>";
                        $correct_post = FALSE;
                    }
                }

                if (trim($_POST['text']) == '')
                {
                    $WEB_APP['errorstext'] .= text('txt_insert_translation')."<br>";
                    $correct_post = FALSE;
                }

                if ($this->language_id == 0)
                {
                    $post_language = $_POST['language'];
                }
                else
                {
                    $post_language = $this->language_id;
                }

                if ($this->language_id < 2)
                {
                    $post_name = $_POST['name'];
                }
                else
                {
                    // $_POST['name'] равно translation.id
                    $post_translation = get_translation($_POST['name']);
                    $post_name = $post_translation->name;
                }


                if ($correct_post && (get_translation_id($post_language, $post_name) != 0))
                {
                    $WEB_APP['errorstext'] .= text('txt_translation_already_exist_insert_another_translation_name')."<br>";
                    $translation->name = $_POST['name'];
                    $correct_post = FALSE;
                }

                if ($correct_post)
                {
                    add_translation($post_language, $post_name, $_POST['text']);
                    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
                    redirect($WEB_APP['errorstext']);
                }
            }

            if (is_delete_action())
            {
                $WEB_APP['title'] = $WEB_APP['title_delete'];
                $WEB_APP['items'] = get_translations_from_array($_POST['selected_row']);
                $WEB_APP['editform'] = FALSE;
                $WEB_APP['action'] = 'delete';
            }
            else
            {
                $WEB_APP['title'] = text('txt_translations');
                // Get translations. On show translations.
                $WEB_APP['items_count'] = get_translations_count($this->language_id, new translation_filter());



                // Pages count.
                $pages = get_pages_count($WEB_APP['items_count'], ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);

                if ($WEB_APP['page'] > $pages)
                {
                    $WEB_APP['page'] = $pages;
                }
                $translations = get_translations($WEB_APP['sort_field'],
                            $WEB_APP['sort_order'],
                            $WEB_APP['page'],
                            $WEB_APP['count'],
                            $this->language_id,
                            new translation_filter()
                            );
                $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
                $WEB_APP['items'] = $translations;

                $paginator = new paginator($WEB_APP['page'], $pages);
                $paginator->url = $WEB_APP['script_name'];
                $paginator->url_query_array  = $WEB_APP['url_query_array'];
                $WEB_APP['paginator'] = $paginator;
            }
            $translation = $this->get_post_translation();
        }

        $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
        $WEB_APP['columns'] = array(new column('id', 'id'),
                    new column('language', text('txt_language')),
                    new column('name', text('txt_name')),
                    new column('text', text('txt_translation')));

        $WEB_APP['escape'] = TRUE;




        // Form fields.
        $fields = array();
        if ($this->language_id == 0)
        {
            $fields[] = new field(  TRUE, text('txt_language'), "select", "language",
                        $translation->language, '', $languages, 'id', 'name');
        }

        if ($this->language_id  < 2)
        {
            $fields[] = new field(  TRUE, text('txt_name'), "text", "name",
                        $translation->name, "");
        }
        else
        {
            $fields[] = new field(  TRUE, text('txt_name'), "select", "name",
                        $translation->name, "", $this->russian_translations, 'id', 'name');
        }
        $fields[] = new field(  TRUE, text('txt_translation'), "text", "text",
                    $translation->text, "");

        $WEB_APP['fields'] = $fields;
        //$WEB_APP['show_empty_value'] = TRUE;
        $WEB_APP['language_paginator'] = TRUE;
        $WEB_APP['view']->display('table.tpl', text('txt_translations'));
    }

    function edit()
    {
        global $WEB_APP;
        global $adodb;
        $languages = get_unhidden_languages();
        $WEB_APP['title'] = $WEB_APP['title_edit'];
        // After change press.
        $translation = get_translation($WEB_APP['id']);
        $tmp_language = get_language($translation->language);
        $translation->language = $tmp_language->name;
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        if (!isset($translation->id))
        {
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['id'] >= 0) && is_add_edit_form('name'))
        {
            $correct_post = TRUE;

            if ($this->language_id == 0)
            {
                if (trim($_POST['language']) == '')
                {
                    $WEB_APP['errorstext'] .= text('txt_insert_language')."<br>";
                    $correct_post = FALSE;
                }
            }
            if ($this->language_id < 2)
            {
                if (trim($_POST['name']) == "")
                {
                    $WEB_APP['errorstext'] .= text('txt_insert_name')."<br>";
                    $correct_post = FALSE;
                }
            }
            else
            {
                if (trim($_POST['name']) == 0)
                {
                    $WEB_APP['errorstext'] .= text('txt_insert_name')."<br>";
                    $correct_post = FALSE;
                }
            }

            if (trim($_POST['text']) == '')
            {
                $WEB_APP['errorstext'] .= text('txt_insert_translation')."<br>";
                $correct_post = FALSE;
            }

            if ($this->language_id == 0)
            {
                $post_language = $_POST['language'];
            }
            else
            {
                $post_language = $this->language_id;
            }

            if ($this->language_id < 2)
            {
                $post_name = $_POST['name'];
            }
            else
            {
                // $_POST['name'] равно translation.id
                $post_translation = get_translation($_POST['name']);
                $post_name = $post_translation->name;
            }

            $translation_id = get_translation_id($post_language, $post_name);
            if (!(  ($translation_id == 0) ||
                ($translation_id == $WEB_APP['id'])))
            {
                $WEB_APP['errorstext'] .= text('txt_translation_already_exist_insert_another_translation_name')."<br>";
                $correct_post = FALSE;
            }

            if ($correct_post)
            {
                edit_translation($WEB_APP['id'], $post_language, $post_name, $_POST['text']);
                $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            }
            $translation = $this->get_post_translation();
            redirect($WEB_APP['errorstext']);
        }

        $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
        $WEB_APP['columns'] = array(new column('id', 'id'),
                    new column('language', text('txt_language')),
                    new column('name', text('txt_name')),
                    new column('text', text('txt_translation')));

        $WEB_APP['escape'] = TRUE;




        // Form fields.
        $fields = array();
        if ($this->language_id == 0)
        {
            $fields[] = new field(  TRUE, text('txt_language'), "select", "language",
                        $translation->language, '', $languages, 'id', 'name');
        }

        if ($this->language_id  < 2)
        {
            $fields[] = new field(  TRUE, text('txt_name'), "textarea", "name",
                        $translation->name, "");
        }
        else
        {
            $fields[] = new field(  TRUE, text('txt_name'), "select", "name",
                        $translation->name, "", $this->russian_translations, 'id', 'name');
        }
        $fields[] = new field(  TRUE, text('txt_translation'), "textarea", "text",
                    $translation->text, "");

        $WEB_APP['fields'] = $fields;
        $WEB_APP['language_paginator'] = TRUE;
        $WEB_APP['view']->display('table.tpl', text('txt_translations'));
    }

}

