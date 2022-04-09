<?php

/**
 * @see module_base
 */
class module_languages extends module_base
{
  function __construct()
  {
    global $WEB_APP;
    $WEB_APP['title_add'] = $WEB_APP['text']['txt_add_language'];
    $WEB_APP['title_edit'] = $WEB_APP['text']['txt_edit_language'];
    $WEB_APP['title_delete'] = $WEB_APP['text']['txt_delete_languages'];
  }

  // $_POST section values.

  function on_delete()
  {
    global $WEB_APP;
    global $adodb;
    if (!isset($_POST['selected_row'])) {
      $this->view();
      exit();
    }
    if (is_confirm_delete_action()) {
      $result = delete_languages($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= $WEB_APP['text']['txt_unpossible_delete_all_languages'] . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      $WEB_APP['title'] = $WEB_APP['title_delete'];
      $WEB_APP['items'] = get_languages_from_array(array($WEB_APP['id']));
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['list_action'] = 'delete';

    }

    $WEB_APP['title'] = $WEB_APP['title_delete'];
    $WEB_APP['items'] = get_languages_from_array($_POST['selected_row']);
    $WEB_APP['editform'] = FALSE;
    $WEB_APP['list_action'] = 'delete';

    $WEB_APP['columns'] = array(new column('id', 'id'), new column('name', $WEB_APP['text']['txt_name']),
      new column('short_name', $WEB_APP['text']['txt_short_name']));

    $WEB_APP['escape'] = TRUE;

    $WEB_APP['submit_title'] = text('txt_delete');


    $WEB_APP['view']->display('list_action.tpl', text('txt_languages'));
  }

  function view()
  {
    global $WEB_APP;
    global $adodb;
    $language = new language();
    if (is_confirm_delete_action()) {
      $result = delete_languages($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= $WEB_APP['text']['txt_unpossible_delete_all_languages'] . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      $language = get_language($WEB_APP['id']);
      if (!isset($language->id)) {
        redirect($WEB_APP['errorstext']);
      }

      $WEB_APP['title'] = $WEB_APP['title_delete'];
      $WEB_APP['items'] = get_languages_from_array(array($WEB_APP['id']));
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['action'] = 'delete';

    }
    if ($WEB_APP['action'] == 'view') {
      // After press Add button.
      if (is_add_form('name')) {
        $correct_post = TRUE;
        if ($_FILES["file"]["size"] > 0) {
          include($_FILES["file"]["tmp_name"]);
        } else {

          if (trim($_POST['name']) == '') {
            $WEB_APP['errorstext'] .= $WEB_APP['text']['txt_insert_name'] . "<br>";
            $correct_post = FALSE;
          }

          if (trim($_POST['short_name']) == '') {
            $WEB_APP['errorstext'] .= $WEB_APP['text']['txt_insert_short_name'] . "<br>";
            $correct_post = FALSE;
          }

          if ($correct_post && (get_language_id($_POST['name']) != 0)) {
            $WEB_APP['errorstext'] .= $WEB_APP['text']['txt_language_name_already_exist_insert_another_language_name'] .
              "<br>";
            $language->name = $_POST['name'];
            $correct_post = FALSE;
          }

          if ($correct_post && (get_language_id_by_short_name($_POST['short_name']) != 0)) {
            $WEB_APP['errorstext'] .= $WEB_APP['text']['txt_language_short_name_already_exist_insert_another_language_short_name'] .
              "<br>";
            $language->name = $_POST['name'];
            $correct_post = FALSE;
          }

          if ($correct_post) {
            add_language($_POST['name'], $_POST['short_name']);
            $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            redirect($WEB_APP['errorstext']);
          }
        }
      }

      if (is_delete_action()) {
        $WEB_APP['title'] = $WEB_APP['title_delete'];
        $WEB_APP['items'] = get_languages_from_array($_POST['selected_row']);
        $WEB_APP['editform'] = FALSE;
        $WEB_APP['action'] = 'delete';
      } else {
        $WEB_APP['title'] = $WEB_APP['text']['txt_languages'];
        // Get sections. On show sections.
        $WEB_APP['items_count'] = get_languages_count(new language_filter());


        // Pages count.
        $pages = get_pages_count($WEB_APP['items_count'],
          ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
        if ($WEB_APP['page'] > $pages) {
          $WEB_APP['page'] = $pages;
        }
        $languages = get_languages($WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'], $WEB_APP['count'],
          new language_filter());
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        $WEB_APP['items'] = $languages;

        $paginator = new paginator($WEB_APP['page'], $pages);
        $paginator->url = $WEB_APP['script_name'];
        $paginator->url_query_array = $WEB_APP['url_query_array'];
        $WEB_APP['paginator'] = $paginator;
      }
      $language = $this->get_post_language();
    }
    $WEB_APP['row_actions'] =
      array($WEB_APP['action_translations'], $WEB_APP['action_edit'], $WEB_APP['action_dublicate'],
        $WEB_APP['action_download'], $WEB_APP['action_delete']);
    $WEB_APP['columns'] = array(new column('id', 'id'), new column('name', $WEB_APP['text']['txt_name']),
      new column('short_name', $WEB_APP['text']['txt_short_name']), new column("hidden", text('txt_hidden')));

    $WEB_APP['escape'] = TRUE;

    // Form fields.
    $fields = array();
    $fields[] = new field(FALSE, $WEB_APP['text']['txt_name'], "text", "name", $language->name, "");
    $fields[] = new field(FALSE, $WEB_APP['text']['txt_short_name'], "text", "short_name", $language->short_name, "");
    $fields[] = new field(FALSE, text('txt_hidden'), "checkbox", "hidden", $language->hidden == 1, "hidden");
    $fields[] =
      new field(FALSE, text('txt_imported_file'), "file", "file", "", "", "", "", "", NULL, FALSE, 'text/php');
    $WEB_APP['form_enctype'] = TRUE;
    $WEB_APP['fields'] = $fields;

    $WEB_APP['view']->display('table.tpl', text('txt_languages'));
  }

  function get_post_language()
  {
    $language = new language();
    $language->name = (isset($_POST['name'])) ? trim($_POST['name']) : '';
    $language->short_name = (isset($_POST['short_name'])) ? trim($_POST['short_name']) : '';

    return $language;
  }

  function on_confirm_delete()
  {
    global $WEB_APP;
    global $adodb;

    $result = delete_languages($_POST['selected_row']);
    if (!$result) {
      $WEB_APP['errorstext'] .= $WEB_APP['text']['txt_unpossible_delete_all_languages'] . "<br>";
      $this->view();
      exit();
    }
    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    redirect($WEB_APP['errorstext']);
  }

  function delete()
  {
    $this->view();
  }

  function edit()
  {
    global $WEB_APP;
    global $adodb;
    $WEB_APP['title'] = $WEB_APP['title_edit'];
    // After change press.
    $language = get_language($WEB_APP['id']);
    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    if (!isset($language->id)) {
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['id'] >= 0) && is_add_edit_form('name')) {
      $correct_post = TRUE;

      if (trim($_POST['name']) == '') {
        $WEB_APP['errorstext'] .= $WEB_APP['text']['txt_insert_name'] . "<br>";
        $correct_post = FALSE;
      }


      $language_id = get_language_id($_POST['name']);
      if (!(($language_id == 0) || ($language_id == $WEB_APP['id']))) {
        $WEB_APP['errorstext'] .= $WEB_APP['text']['txt_language_name_already_exist_insert_another_language_name'] .
          "<br>";
        $correct_post = FALSE;
      }

      if (trim($_POST['short_name']) == '') {
        $WEB_APP['errorstext'] .= $WEB_APP['text']['txt_insert_short_name'] . "<br>";
        $correct_post = FALSE;
      }

      $language_id = get_language_id_by_short_name($_POST['short_name']);

      if (!(($language_id == 0) || ($language_id == $WEB_APP['id']))) {
        $WEB_APP['errorstext'] .= $WEB_APP['text']['txt_language_short_name_already_exist_insert_another_language_short_name'] .
          "<br>";
        $language->name = $_POST['name'];
        $correct_post = FALSE;
      }

      if ($correct_post) {
        edit_language($WEB_APP['id'], $_POST['name'], $_POST['short_name'], $_POST['hidden']);
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      }
      $language = $this->get_post_language();
      redirect($WEB_APP['errorstext']);
    }

    $WEB_APP['row_actions'] =
      array($WEB_APP['action_translations'], $WEB_APP['action_edit'], $WEB_APP['action_delete']);
    $WEB_APP['columns'] = array(new column('id', 'id'), new column('name', $WEB_APP['text']['txt_name']),
      new column('short_name', $WEB_APP['text']['txt_short_name']));

    $WEB_APP['escape'] = TRUE;

    // Form fields.
    $fields = array();
    $fields[] = new field(TRUE, $WEB_APP['text']['txt_name'], "text", "name", $language->name, "");
    $fields[] = new field(TRUE, $WEB_APP['text']['txt_short_name'], "text", "short_name", $language->short_name, "");
    $fields[] = new field(FALSE, text('txt_hidden'), "checkbox", "hidden", $language->hidden == 1, "hidden");

    $WEB_APP['fields'] = $fields;

    $WEB_APP['view']->display('table.tpl', $WEB_APP['text']['txt_languages']);
  }

  function translations()
  {
    $id = (isset($_GET['id'])) ? $_GET['id'] : 1;
    $language = get_language($id);
    if (!isset($language->short_name)) {
      $lng = 'all';
    } else {
      $lng = $language->short_name;
    }
    header('Location: ?module=translations&language=' . $lng);
    exit();
  }

  function dublicate()
  {
    global $WEB_APP;
    copy_language($WEB_APP['id']);
    header('Location: ?module=languages');
    exit();
  }

  function download()
  {
    global $WEB_APP;
    export_language($WEB_APP['id']);
    header('Location: ?module=languages');
    exit();
  }
}

