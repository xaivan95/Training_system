<?php

/**
 * @see module_base
 */
class module_sections_tests extends module_base
{
  function __construct()
  {
    global $WEB_APP;
    $WEB_APP['title_edit'] = text('txt_edit_section_test');
    $WEB_APP['title_delete'] = text('txt_delete_section_tests');
    $WEB_APP['title_add'] = text('txt_add_section_test');
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
      $result = delete_section_tests($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_section_tests') . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      $WEB_APP['title'] = $WEB_APP['title_delete'];
      $WEB_APP['items'] = get_section_tests_from_array(array($WEB_APP['id']));
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['list_action'] = 'delete';
    }

    $WEB_APP['title'] = $WEB_APP['title_delete'];
    $WEB_APP['items'] = get_section_tests_from_array($_POST['selected_row']);
    $WEB_APP['editform'] = FALSE;
    $WEB_APP['list_action'] = 'delete';

    $WEB_APP['columns'] = array(new column('id', 'id'), new column('test_name', text('txt_test')),
      new column('section_name', text('txt_section')), new column('test_is_hidden', text('txt_hidden')));

    $WEB_APP['escape'] = TRUE;

    $WEB_APP['submit_title'] = text('txt_delete');


    $WEB_APP['view']->display('list_action.tpl', text('txt_manage_sections_tests'));
  }

  function view()
  {
    global $WEB_APP;
    global $adodb;
    $tests = get_tests('test_name', 'ASC');
    $sections = get_sections('section_name');
    $section_test = new section_test();
    if (is_confirm_delete_action()) {
      $result = delete_section_tests($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_section_tests') . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }
    if (is_confirm_move_action()) {
      if ($_POST['section'] == 0) {
        $WEB_APP['errorstext'] .= text('txt_insert_section') . "<br>";
        $this->on_move();
        exit();
      }
      $result = move_tests($_POST['section'], $_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_unpossible_move_all_tests') . "<br>";
      }
      //$WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
      $this->view();
      exit();
    }

    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      $section_test = get_section_test(intval($WEB_APP['id']));
      if (!isset($section_test->id)) {
        redirect($WEB_APP['errorstext']);
      }

      $WEB_APP['title'] = $WEB_APP['title_delete'];
      $WEB_APP['items'] = get_section_tests_from_array(array($WEB_APP['id']));
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['action'] = 'delete';
    }

    if ($WEB_APP['action'] == 'view') {
      // After press Add button.
      if (is_add_form('test')) {
        $correct_post = TRUE;
        if ($_POST['test'] == '') {
          $WEB_APP['errorstext'] .= text('txt_insert_test') . "<br>";
          $correct_post = FALSE;
        }
        if (trim($_POST['section']) == "") {
          $WEB_APP['errorstext'] .= text('txt_insert_section') . "<br>";
          $correct_post = FALSE;
        }

        if ($correct_post) {
          add_section_tests($_POST['section'], $_POST['test'], isset($_POST['hidden']));
          $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
          redirect($WEB_APP['errorstext']);
        }
      }

      if (is_delete_action()) {
        $WEB_APP['title'] = $WEB_APP['title_delete'];
        $WEB_APP['items'] = get_section_tests_from_array($_POST['selected_row']);
        $WEB_APP['editform'] = FALSE;
        $WEB_APP['action'] = 'delete';
      } else {
        $WEB_APP['title'] = text('txt_manage_sections_tests');
        // Get sections. On show sections.
        $WEB_APP['items_count'] = get_section_tests_count(new section_test_filter());

        // Pages count.
        $pages = get_pages_count($WEB_APP['items_count'],
          ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
        if ($WEB_APP['page'] > $pages) {
          $WEB_APP['page'] = $pages;
        }
        $section_tests =
          get_section_tests($WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'], $WEB_APP['count'],
            new section_test_filter());
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        $WEB_APP['items'] = $section_tests;

        $paginator = new paginator($WEB_APP['page'], $pages);
        $paginator->url = $WEB_APP['script_name'];
        $paginator->url_query_array = $WEB_APP['url_query_array'];
        $WEB_APP['paginator'] = $paginator;
      }
      $section_test = $this->get_post_section_test();
    }

    $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
    $WEB_APP['list_actions'] = array($WEB_APP['list_action_delete'], $WEB_APP['list_action_move']);
    $WEB_APP['columns'] = array(new column('id', 'id'), new column('test_name', text('txt_test')),
      new column('test_author', text('txt_author')), new column('section_name', text('txt_section')),
      new column('test_is_hidden', text('txt_hidden')));

    $WEB_APP['escape'] = TRUE;


    // Form fields.
    $fields = array();
    $fields[] =
      new field(TRUE, text('txt_test'), "multiple_select", "test[]", $tests, "", $tests, 'id', 'test_name', null, FALSE,
        '', '', 'data-live-search="true"');
    $fields[] = new field(TRUE, text('txt_section'), "select", "section", $section_test->section, "", $sections, 'id',
      'section_name', null, FALSE, '', '', 'data-live-search="true"');
    $fields[] = new field(FALSE, text('txt_hidden'), "checkbox", "hidden", $section_test->hidden, "hidden");
    $WEB_APP['fields'] = $fields;

    $WEB_APP['view']->display('table.tpl', text('txt_manage_sections_tests'));
  }

  function on_move()
  {
    global $WEB_APP;

    if (!isset($_POST['selected_row'])) {
      $this->view();
      exit();
    }

    $sections = get_sections('section_name');
    $section_test = new section_test();

    $WEB_APP['title'] = text('txt_move');
    $WEB_APP['items'] = get_section_tests_from_array($_POST['selected_row']);
    $WEB_APP['editform'] = FALSE;
    $WEB_APP['list_action'] = 'move';

    $WEB_APP['columns'] = array(new column('id', 'id'), new column('test_name', text('txt_test')),
      new column('section_name', text('txt_section')), new column('test_is_hidden', text('txt_hidden')));

    $WEB_APP['escape'] = TRUE;

    // Form fields.
    $fields = array();

    $fields[] = new field(TRUE, text('txt_section'), "select", "section", $section_test->section, "", $sections, 'id',
      'section_name', null, FALSE, '', '', 'data-live-search="true"');


    $WEB_APP['fields'] = $fields;
    $WEB_APP['list_action'] = 'move';
    $WEB_APP['submit_title'] = text('txt_move');
    $WEB_APP['view']->display('list_action.tpl', text('txt_manage_sections_tests'));
  }

  function get_post_section_test()
  {
    $section_test = new section_test();

    $section_test->test = '';

    if (isset($_POST['test'])) {
      $test = get_test($_POST['test']);
      if ($test->name != NULL) {
        $section_test->test = $test->name;
      }
    }

    $section_test->section = '';

    if (isset($_POST['section'])) {
      $section = get_section($_POST['section']);
      if ($section->name != NULL) {
        $section_test->section = $section->name;
      }
    }

    $section_test->hidden = isset($_POST['hidden']);

    return $section_test;
  }

  /** @noinspection PhpUnused */

  function on_confirm_delete()
  {
    global $WEB_APP;
    global $adodb;

    $result = delete_section_tests($_POST['selected_row']);
    if (!$result) {
      $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_section_tests') . "<br>";
      $this->view();
      exit();
    }
    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    redirect($WEB_APP['errorstext']);
  }

  function on_confirm_move()
  {
    global $WEB_APP;

    if ($_POST['section'] == 0) {
      $WEB_APP['errorstext'] .= text('txt_insert_section') . "<br>";
      $this->on_move();
      exit();
    }
    $result = move_tests($_POST['section'], $_POST['selected_row']);
    if (!$result) {
      $WEB_APP['errorstext'] .= text('txt_unpossible_move_all_tests') . "<br>";
    }
    //$WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    redirect($WEB_APP['errorstext']);
    $this->view();
  }

  function edit()
  {
    global $WEB_APP;
    global $adodb;

    $tests = get_tests('test_name');
    $sections = get_sections('section_name');

    $WEB_APP['title'] = $WEB_APP['title_edit'];
    // After change press.
    $section_test = get_section_test(intval($WEB_APP['id']));
    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    if (!isset($section_test->id)) {
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['id'] >= 0) && is_add_edit_form('test')) {
      $correct_post = TRUE;
      if ($_POST['test'] == '') {
        $WEB_APP['errorstext'] .= text('txt_insert_test') . "<br>";
        $correct_post = FALSE;
      }
      if (trim($_POST['section']) == '') {
        $WEB_APP['errorstext'] .= text('txt_insert_section') . "<br>";
        $correct_post = FALSE;
      }

      $section_test_id = get_section_test_id($_POST['section'], $_POST['test']);
      if (!(($section_test_id == 0) || ($section_test_id == $WEB_APP['id']))) {
        $WEB_APP['errorstext'] .= text('txt_section_test_already_exist_insert_another_section_test') . "<br>";
        $correct_post = FALSE;
      }

      if ($correct_post) {
        edit_section_test(intval($WEB_APP['id']), $_POST['section'], $_POST['test'], isset($_POST['hidden']));
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      }
      $section_test = $this->get_post_section_test();
      redirect($WEB_APP['errorstext']);
    }

    $WEB_APP['escape'] = TRUE;

    // Form fields.
    $fields = array();
    $fields[] =
      new field(TRUE, text('txt_test'), "select", "test", $section_test->test, "", $tests, 'id', 'test_name', null,
        FALSE, '', '', 'data-live-search="true"');

    $fields[] = new field(TRUE, text('txt_section'), "select", "section", $section_test->section, "", $sections, 'id',
      'section_name', null, FALSE, '', '', 'data-live-search="true"');
    $fields[] = new field(FALSE, text('txt_hidden'), "checkbox", "hidden", $section_test->hidden, "hidden");
    $WEB_APP['fields'] = $fields;

    $WEB_APP['view']->display('table.tpl', text('txt_manage_sections_tests'));
  }

}

