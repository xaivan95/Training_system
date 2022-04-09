<?php

/**
 * @see module_base
 */
class module_groups_sections extends module_base
{
  function __construct()
  {
    global $WEB_APP;
    $WEB_APP['title_edit'] = text('txt_edit_group_section');
    $WEB_APP['title_delete'] = text('txt_delete_group_sections');
    $WEB_APP['title_add'] = text('txt_add_group_section');
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
      $result = delete_group_sections($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_group_sections') . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      $WEB_APP['title'] = $WEB_APP['title_delete'];
      $WEB_APP['items'] = get_group_sections_from_array(array($WEB_APP['id']));
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['list_action'] = 'delete';
    }

    $WEB_APP['title'] = $WEB_APP['title_delete'];
    $WEB_APP['items'] = get_group_sections_from_array($_POST['selected_row']);
    $WEB_APP['editform'] = FALSE;
    $WEB_APP['list_action'] = 'delete';

    $WEB_APP['columns'] = array(new column('id', 'id'), new column('group_name', text('txt_group')),
      new column('section_name', text('txt_section')));

    $WEB_APP['escape'] = TRUE;

    $WEB_APP['submit_title'] = text('txt_delete');


    $WEB_APP['view']->display('list_action.tpl', text('txt_manage_groups_sections'));
  }

  function view()
  {
    global $WEB_APP;
    global $adodb;
    $groups = get_groups('group_name', 'ASC');
    $sections = get_sections('section_name', 'ASC');
    $group_section = new group_section();
    if (is_confirm_delete_action()) {
      $result = delete_group_sections($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_group_sections') . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      $group_section = get_group_section($WEB_APP['id']);
      if (!isset($group_section->id)) {
        redirect($WEB_APP['errorstext']);
      }

      $WEB_APP['title'] = $WEB_APP['title_delete'];
      $WEB_APP['items'] = get_group_sections_from_array(array($WEB_APP['id']));
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['action'] = 'delete';
    }

    if ($WEB_APP['action'] == 'view') {
      // After press Add button.
      if (is_add_form('group')) {
        $correct_post = TRUE;
        if ($_POST['group'] == '') {
          $WEB_APP['errorstext'] .= text('txt_insert_group') . "<br>";
          $correct_post = FALSE;
        }
        if (!isset($_POST['sections'])) {
          $WEB_APP['errorstext'] .= text('txt_insert_section') . "<br>";
          $correct_post = FALSE;
        }

        if ($correct_post) {
          $i = 0;
          foreach ($_POST['sections'] as $section_id) {
            if (get_group_section_id($_POST['group'], $section_id) != 0) {
              $WEB_APP['errorstext'] .= text('txt_group_section_already_exist_insert_another_group_section') .
                ' <strong>' . get_section($section_id)->name . '</strong>.<br>';
              $correct_post = FALSE;
            }
            ++$i;
          }
        }

        if ($correct_post) {
          add_group_section($_POST['group'], $_POST['sections'], $_POST['limited_from'], $_POST['limited_to']);
          $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
          redirect($WEB_APP['errorstext']);
        }
      }

      if (is_delete_action()) {
        $WEB_APP['title'] = $WEB_APP['title_delete'];
        $WEB_APP['items'] = get_group_sections_from_array($_POST['selected_row']);
        $WEB_APP['editform'] = FALSE;
        $WEB_APP['action'] = 'delete';
      } else {
        $WEB_APP['title'] = text('txt_manage_groups_sections');
        // Get sections. On show sections.
        $WEB_APP['items_count'] = get_group_sections_count(new group_section_filter());

        // Pages count.
        $pages = get_pages_count($WEB_APP['items_count'],
          ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
        if ($WEB_APP['page'] > $pages) {
          $WEB_APP['page'] = $pages;
        }
        $group_sectiones =
          get_group_sections($WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'], $WEB_APP['count'],
            new group_section_filter());
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        $WEB_APP['items'] = $group_sectiones;

        $paginator = new paginator($WEB_APP['page'], $pages);
        $paginator->url = $WEB_APP['script_name'];
        $paginator->url_query_array = $WEB_APP['url_query_array'];
        $WEB_APP['paginator'] = $paginator;
      }
      $group_section = $this->get_post_group_section();
    }

    $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
    $WEB_APP['columns'] = array(new column('id', 'id'), new column('group_name', text('txt_group')),
      new column('section_name', text('txt_section')), new column('limited_from', text('txt_from')),
      new column('limited_to', text('txt_to')));

    $WEB_APP['escape'] = TRUE;


    // Form fields.
    $fields = array();
    $fields[] = new field(FALSE, text('txt_from'), "date", "limited_from", $group_section->limited_from);
    $fields[] =
      new field(TRUE, text('txt_group'), "select", "group", $group_section->group, "", $groups, 'id', 'group_name',
        null, FALSE, '', '', 'data-live-search="true"');
    $fields[] = new field(FALSE, text('txt_to'), "date", "limited_to", $group_section->limited_to);
    $fields[] = new field(TRUE, text('txt_section'), "multiple_select", "sections[]", $groups, "", $sections, 'id',
      'section_name', null, FALSE, '', '', 'data-live-search="true"');

    $WEB_APP['fields'] = $fields;
    $WEB_APP['scripts'][] = 'moment-with-locales.min.js';
    $WEB_APP['scripts'][] = 'bootstrap-datetimepicker.min.js';
    $WEB_APP['view']->display('table.tpl', text('txt_manage_groups_sections'));
  }

  function get_post_group_section()
  {
    $group_section = new group_section();

    $group_section->group = '';

    if (isset($_POST['group'])) {
      $group = get_group($_POST['group']);
      if ($group->name != NULL) {
        $group_section->group = $group->name;
      }
    }

    $group_section->section = '';

    if (isset($_POST['section'])) {
      $section = get_section($_POST['section']);
      if ($section->name != NULL) {
        $group_section->section = $section->name;
      }
    }

    return $group_section;
  }

  function on_confirm_delete()
  {
    global $WEB_APP;
    global $adodb;

    $result = delete_group_sections($_POST['selected_row']);
    if (!$result) {
      $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_group_sections') . "<br>";
      $this->view();
      exit();
    }
    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    redirect($WEB_APP['errorstext']);
  }

  function edit()
  {
    global $WEB_APP;
    global $adodb;

    $groups = get_groups('group_name');
    $sections = get_sections('section_name');

    $WEB_APP['title'] = $WEB_APP['title_edit'];
    // After change press.
    $group_section = get_group_section($WEB_APP['id']);
    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    if (!isset($group_section->id)) {
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['id'] >= 0) && is_add_edit_form('group')) {
      $correct_post = TRUE;
      if ($_POST['group'] == '') {
        $WEB_APP['errorstext'] .= text('txt_insert_group') . "<br>";
        $correct_post = FALSE;
      }
      if (trim($_POST['section']) == '') {
        $WEB_APP['errorstext'] .= text('txt_insert_section') . "<br>";
        $correct_post = FALSE;
      }

      $group_section_id = get_group_section_id($_POST['group'], $_POST['section']);
      if (!(($group_section_id == 0) || ($group_section_id == $WEB_APP['id']))) {
        $WEB_APP['errorstext'] .= text('txt_group_section_already_exist_insert_another_group_section') . "<br>";
        $correct_post = FALSE;
      }

      if ($correct_post) {
        edit_group_section($WEB_APP['id'], $_POST['group'], $_POST['section'], $_POST['limited_from'],
          $_POST['limited_to']);
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      }
      $group_section = $this->get_post_group_section();
      redirect($WEB_APP['errorstext']);
    }

    $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
    $WEB_APP['columns'] = array(new column('id', 'id'), new column('group_name', text('txt_group')),
      new column('section_name', text('txt_section')));

    $WEB_APP['escape'] = TRUE;


    // Form fields.
    $fields = array();
    $fields[] = new field(FALSE, text('txt_from'), "date", "limited_from", $group_section->limited_from);
    $fields[] =
      new field(TRUE, text('txt_group'), "select", "group", $group_section->group, "", $groups, 'id', 'group_name');
    $fields[] = new field(FALSE, text('txt_to'), "date", "limited_to", $group_section->limited_to);
    $fields[] = new field(TRUE, text('txt_section'), "select", "section", $group_section->section, "", $sections, 'id',
      'section_name');
    $WEB_APP['fields'] = $fields;

    $WEB_APP['scripts'][] = 'moment-with-locales.min.js';
    $WEB_APP['scripts'][] = 'bootstrap-datetimepicker.min.js';
    $WEB_APP['view']->display('table.tpl', text('txt_manage_groups_sections'));
  }

}

