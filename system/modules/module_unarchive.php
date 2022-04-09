<?php

class module_unarchive extends module_base
{
  function view()
  {
    global $WEB_APP;
    $date_from = date('Y-m-d');
    $date_to = date('Y-m-d');
    $groups_to_archive = '';
    if (isset($_POST['date_from'])) $date_from = $_POST['date_from'];
    if (isset($_POST['date_to'])) $date_to = $_POST['date_to'];
    $groups = get_groups('group_name', 'ASC');
    $_SESSION['archive_group_array'] = $_POST['group'];
    $select_groups = array();
    if (isset($_SESSION['archive_group_array'])) {
      foreach ($_SESSION['archive_group_array'] as $group_id) {
        $group = get_group($group_id);
        $select_groups[] = $group->name;
        $groups_to_archive = $groups_to_archive . $group->id . ',';
      }
      $groups_to_archive = rtrim($groups_to_archive, ',');
    }

    if (isset($_POST["submit_button"])) {
      if (isset($_POST['date_from']) and $_POST['date_to'] and $_POST['group']) {
        $result = unarchive_results($groups_to_archive, $_POST['date_from'], $_POST['date_to']);
        $WEB_APP['infotext'] = text('txt_archived') . " $result[0]/$result[1].";
      }
    }

    $fields = array();
    $fields[] = new field(TRUE, text('txt_from'), "date", "date_from", $date_from, "date_from");
    $fields[] = new field(TRUE, text('txt_to'), "date", "date_to", $date_to, "");
    $fields[] =
      new field(TRUE, text('txt_groups'), 'multiple_select', 'group[]', $select_groups, '', $groups, 'id', 'group_name',
        null, FALSE, '', '', 'data-live-search="true"');

    $WEB_APP['fields'] = $fields;
    $WEB_APP['title'] = text('txt_unarchive_results');
    $WEB_APP['submit_title'] = text('txt_unarchive');
    $WEB_APP['form_title'] = text('txt_unarchive_results');
    $WEB_APP['scripts'][] = 'moment-with-locales.min.js';
    $WEB_APP['scripts'][] = 'bootstrap-datetimepicker.min.js';
    $WEB_APP['view']->display('form_page.tpl', text('txt_unarchive_results'));
  }
}