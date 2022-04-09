<?php

/**
 * @see module_base
 */
class module_access extends module_base
{
  function __construct()
  {
    global $WEB_APP;
    $WEB_APP['title_edit'] = text('txt_edit_access');
    $WEB_APP['title_delete'] = text('txt_delete_accesses');
    $WEB_APP['title_add'] = text('txt_add_access');
  }

  function on_delete()
  {
    global $WEB_APP;
    global $adodb;
    if (!isset($_POST['selected_row'])) {
      $this->view();
      exit();
    }
    //        $access = new access();
    //        $grants = get_grants('grant_title');
    //        $modules = get_modules('module_name');
    if (is_confirm_delete_action()) {
      $result = delete_accesses($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_accesses') . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      $WEB_APP['title'] = $WEB_APP['title_delete'];
      $WEB_APP['items'] = get_accesses_from_array(array($WEB_APP['id']));
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['list_action'] = 'delete';
    }

    $WEB_APP['title'] = $WEB_APP['title_delete'];
    $WEB_APP['items'] = get_accesses_from_array($_POST['selected_row']);
    $WEB_APP['editform'] = FALSE;
    $WEB_APP['list_action'] = 'delete';

    $WEB_APP['columns'] = array(new column('id', 'id'), new column('grant_title', text('txt_grant')),
      new column('module_name', text('txt_module')), new column('action', text('txt_action')));

    $WEB_APP['escape'] = TRUE;

    $WEB_APP['submit_title'] = text('txt_delete');


    $WEB_APP['view']->display('list_action.tpl', text('txt_access'));
  }

  /**
   * Implementation of module_base::view().
   */
  function view()
  {
    global $WEB_APP;

    function compare_module_name($a, $b)
    {
      if ($a['module_name'] == $b['module_name']) {
        return 0;
      }
      return ($a['module_name'] < $b['module_name']) ? -1 : 1;
    }

    global $adodb;
    $access = new access();
    $grants = get_grants('grant_title', 'ASC');
    $modules = get_modules('module', 'ASC');
    uasort($modules, 'compare_module_name');
    $actions = array();

    if (is_confirm_delete_action()) {
      $result = delete_accesses($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_accesses') . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      $access = get_access($WEB_APP['id']);
      if (!isset($access->id)) {
        redirect($WEB_APP['errorstext']);
      }
      $WEB_APP['title'] = $WEB_APP['title_delete'];
      $WEB_APP['items'] = get_accesses_from_array(array($WEB_APP['id']));
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['action'] = 'delete';
    }
    if ($WEB_APP['action'] == 'view') {
      // After press Add button.
      if (count($_POST) > 0) {
        $correct_post = TRUE;

        $grant = isset($_POST['grant']) ? trim($_POST['grant']) : '';
        $module = isset($_POST['module']) ? trim($_POST['module']) : '';
        $action = isset($_POST['action']) ? trim($_POST['action']) : '';

        if ($grant == '') {
          $WEB_APP['errorstext'] .= text('txt_insert_grant') . "<br>";
          $correct_post = FALSE;
        }
        if ($module == '') {
          $WEB_APP['errorstext'] .= text('txt_insert_module') . "<br>";
          $correct_post = FALSE;
        }
        if ($action == '') {
          $WEB_APP['errorstext'] .= text('txt_insert_action') . "<br>";
          $correct_post = FALSE;
        }
        if ($correct_post && (get_access_id($grant, $module, $action) != 0)) {
          $WEB_APP['errorstext'] .= text('txt_access_already_exist_insert_another_access') . "<br>";
          $correct_post = FALSE;
        }
        if ($correct_post) {
          add_access($grant, $module, $action);
          $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
          redirect($WEB_APP['errorstext']);
        }
        if (isset($_POST['module'])) {
          $actions = get_module_actions_for_module_id($_POST['module']);
        }
      }

      if (is_delete_action()) {
        $WEB_APP['title'] = $WEB_APP['title_delete'];
        $WEB_APP['items'] = get_accesses_from_array($_POST['selected_row']);
        $WEB_APP['editform'] = FALSE;
        $WEB_APP['action'] = 'delete';
      } else {
        $WEB_APP['title'] = text('txt_access');
        // Get sections. On show sections.
        $WEB_APP['items_count'] = get_accesses_count(new access_filter());//db_count(DB_TABLE_ACCESS);
        // Pages count.
        $pages = get_pages_count($WEB_APP['items_count'],
          ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);

        if ($WEB_APP['page'] > $pages) {
          $WEB_APP['page'] = $pages;
        }
        $accesses = get_accesses($WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'], $WEB_APP['count'],
          new access_filter());
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        $WEB_APP['items'] = $accesses;

        $paginator = new paginator($WEB_APP['page'], $pages);
        $paginator->url = $WEB_APP['script_name'];
        $paginator->url_query_array = $WEB_APP['url_query_array'];
        $WEB_APP['paginator'] = $paginator;
      }
      $access = $this->get_post_access();
    }
    $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
    $WEB_APP['columns'] = array(new column('id', 'id'), new column('grant_title', text('txt_grant')),
      new column('module_name', text('txt_module')), new column('action', text('txt_action')));

    $WEB_APP['escape'] = TRUE;


    // Form fields.
    $fields = array();
    $fields[] =
      new field(TRUE, text('txt_grant'), "select", "grant", $access->grant, "", $grants, 'id', 'grant_title', null,
        FALSE, '', '', 'data-live-search="true"');
    $fields[] =
      new field(TRUE, text('txt_module'), "select", "module", $access->module, "", $modules, 'id', 'module_name',
        'return change_module()', FALSE, '', '', 'data-live-search="true"');
    $fields[] =
      new field(TRUE, text('txt_action'), "select", "action", $access->action, "", $actions, 'action', 'action');
    $WEB_APP['fields'] = $fields;
    $WEB_APP['view']->display('table.tpl', text('txt_access'));
  }

  function get_post_access()
  {
    $access = new access();

    $access->grant = '';

    if (isset($_POST['grant'])) {
      $grant = get_grant($_POST['grant']);
      if ($grant->title != NULL) {
        $access->grant = $grant->title;
      }
    }

    $access->module = '';
    if (isset($_POST['module'])) {
      $module = get_module($_POST['module']);
      if ($module->name != NULL) {
        $access->module = text($module->name);
      }
    }

    $access->action = '';
    if (isset($_POST['action'])) {
      $access->action = trim($_POST['action']);
    }

    if (isset($_POST['module']) && isset($_POST['action'])) {
      $id = get_module_action_id($_POST['module'], $_POST['action']);
      if ($id == 0) {
        $access->action = '';
      }
    }

    return $access;
  }

  function on_confirm_delete()
  {
    global $WEB_APP;
    global $adodb;

    $result = delete_accesses($_POST['selected_row']);
    if (!$result) {
      $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_accesses') . "<br>";
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

    $grants = get_grants('grant_title');
    $modules = get_modules('module_name');
    $WEB_APP['title'] = $WEB_APP['title_edit'];
    // After change press.
    $access = get_access($WEB_APP['id']);
    $module_id = get_access_module_id($access->id);
    $actions = ($module_id != 0) ? get_module_actions_for_module_id($module_id) : array();

    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    if (!isset($access->id)) {
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['id'] >= 0) && is_add_edit_form('grant')) {
      $correct_post = TRUE;

      $grant = isset($_POST['grant']) ? trim($_POST['grant']) : '';
      $module = isset($_POST['module']) ? trim($_POST['module']) : '';
      $action = isset($_POST['action']) ? trim($_POST['action']) : '';

      if ($grant == '') {
        $WEB_APP['errorstext'] .= text('txt_insert_grant') . "<br>";
        $correct_post = FALSE;
      }
      if ($module == '') {
        $WEB_APP['errorstext'] .= text('txt_insert_module') . "<br>";
        $correct_post = FALSE;
      }
      if ($action == '') {
        $WEB_APP['errorstext'] .= text('txt_insert_action') . "<br>";
        $correct_post = FALSE;
      }

      $access_id = get_access_id($grant, $module, $action);
      if (!(($access_id == 0) || ($access_id == $WEB_APP['id']))) {
        $WEB_APP['errorstext'] .= text('txt_access_already_exist_insert_another_access') . "<br>";
        $correct_post = FALSE;
      }

      if ($correct_post) {
        edit_access($WEB_APP['id'], $grant, $module, $action);
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      }
      $access = $this->get_post_access();
      redirect($WEB_APP['errorstext']);

      if (isset($_POST['module'])) {
        $actions = get_module_actions_for_module_id($_POST['module']);
      }
      $access = $this->get_post_access();
    }

    $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
    $WEB_APP['columns'] = array(new column('id', 'id'), new column('grant_title', text('txt_grant')),
      new column('module_name', text('txt_module')));

    $WEB_APP['escape'] = TRUE;


    // Form fields.
    $fields = array();
    $fields[] = new field(TRUE, text('txt_grant'), "select", "grant", $access->grant, "", $grants, 'id', 'grant_title');

    $fields[] =
      new field(TRUE, text('txt_module'), "select", "module", $access->module, "", $modules, 'id', 'module_name',
        'return change_module()', FALSE);

    $fields[] =
      new field(TRUE, text('txt_action'), "select", "action", $access->action, "", $actions, 'action', 'action');
    $WEB_APP['fields'] = $fields;

    $WEB_APP['view']->display('table.tpl', text('txt_access'));
  }

  /** @noinspection PhpUnused */
  function change_module()
  {
    if (isset($_GET['module_id']) && is_scalar($_GET['module_id'])) {
      $module_id = (int)$_GET['module_id'];
    } else {
      die();
    }

    $module = get_module($module_id);
    if (!isset($module->id)) {
      die();
    }

    $actions = get_module_actions_for_module_id($module->id);
    echo "<option selected=\"\" value=\"\"></option>\n";
    foreach ($actions as $action) {
      printf("<option value=\"%s\">%s</option>\n", $action['action'], htmlspecialchars($action['action']));
    }
    die();
  }

}

