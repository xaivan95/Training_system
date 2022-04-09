<?php

/**
 * @see module_base
 */
class module_groups_courses extends module_base
{
  function __construct()
  {
    global $WEB_APP;
    $WEB_APP['title_edit'] = text('txt_edit_group_course');
    $WEB_APP['title_delete'] = text('txt_delete_group_courses');
    $WEB_APP['title_add'] = text('txt_add_group_course');
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
      $result = delete_group_courses($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_group_courses') . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      $WEB_APP['title'] = $WEB_APP['title_delete'];
      $WEB_APP['items'] = get_group_courses_from_array(array($WEB_APP['id']));
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['list_action'] = 'delete';
    }

    $WEB_APP['title'] = $WEB_APP['title_delete'];
    $WEB_APP['items'] = get_group_courses_from_array($_POST['selected_row']);
    $WEB_APP['editform'] = FALSE;
    $WEB_APP['list_action'] = 'delete';

    $WEB_APP['columns'] = array(new column('id', 'id'), new column('group_name', text('txt_group')),
      new column('title', text('txt_course')));

    $WEB_APP['escape'] = TRUE;

    $WEB_APP['submit_title'] = text('txt_delete');


    $WEB_APP['view']->display('list_action.tpl', text('txt_manage_groups_courses'));
  }

  function view()
  {
    global $WEB_APP;
    global $adodb;
    $groups = get_groups('group_name', 'ASC');
    $courses = get_courses('title', 'ASC');
    $group_course = new group_course();
    if (is_confirm_delete_action()) {
      $result = delete_group_courses($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_group_courses') . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      $group_course = get_group_course($WEB_APP['id']);
      if (!isset($group_course->id)) {
        redirect($WEB_APP['errorstext']);
      }

      $WEB_APP['title'] = $WEB_APP['title_delete'];
      $WEB_APP['items'] = get_group_courses_from_array(array($WEB_APP['id']));
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
        if (!isset($_POST['courses'])) {
          $WEB_APP['errorstext'] .= text('txt_insert_course') . "<br>";
          $correct_post = FALSE;
        }


        if ($correct_post) {
          $i = 0;
          foreach ($_POST['courses'] as $course_id) {
            if (get_group_course_id($_POST['group'], $course_id) != 0) {
              $WEB_APP['errorstext'] .= text('txt_group_course_already_exist_insert_another_group_course') .
                ' <strong>' . get_course($course_id)->title . '</strong>.<br>';
              $correct_post = FALSE;
            }
            ++$i;
          }
        }

        if ($correct_post) {
          add_group_course($_POST['group'], $_POST['courses'], $_POST['limited_from'], $_POST['limited_to']);
          $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
          redirect($WEB_APP['errorstext']);
        }
      }

      if (is_delete_action()) {
        $WEB_APP['title'] = $WEB_APP['title_delete'];
        $WEB_APP['items'] = get_group_courses_from_array($_POST['selected_row']);
        $WEB_APP['editform'] = FALSE;
        $WEB_APP['action'] = 'delete';
      } else {
        $WEB_APP['title'] = text('txt_manage_groups_courses');
        // Get sections. On show sections.
        $WEB_APP['items_count'] = get_group_courses_count(new group_course_filter());

        // Pages count.
        $pages = get_pages_count($WEB_APP['items_count'],
          ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
        if ($WEB_APP['page'] > $pages) {
          $WEB_APP['page'] = $pages;
        }
        $group_coursees =
          get_group_courses($WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'], $WEB_APP['count'],
            new group_course_filter());
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        $WEB_APP['items'] = $group_coursees;

        $paginator = new paginator($WEB_APP['page'], $pages);
        $paginator->url = $WEB_APP['script_name'];
        $paginator->url_query_array = $WEB_APP['url_query_array'];
        $WEB_APP['paginator'] = $paginator;
      }
      $group_course = $this->get_post_group_course();
    }

    $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
    $WEB_APP['columns'] = array(new column('id', 'id'), new column('group_name', text('txt_group')),
      new column('title', text('txt_course')), new column('limited_from', text('txt_from')),
      new column('limited_to', text('txt_to')));

    $WEB_APP['escape'] = TRUE;


    // Form fields.
    $fields = array();
    $fields[] = new field(FALSE, text('txt_from'), "date", "limited_from", $group_course->limited_from);
    $fields[] =
      new field(TRUE, text('txt_group'), "select", "group", $group_course->group, "", $groups, 'id', 'group_name', null,
        FALSE, '', '', 'data-live-search="true"');
    $fields[] = new field(FALSE, text('txt_to'), "date", "limited_to", $group_course->limited_to);
    $fields[] =
      new field(TRUE, text('txt_course'), "multiple_select", "courses[]", $courses, "", $courses, 'id', 'title', null,
        FALSE, '', '', 'data-live-search="true"');
    $WEB_APP['fields'] = $fields;
    $WEB_APP['scripts'][] = 'moment-with-locales.min.js';
    $WEB_APP['scripts'][] = 'bootstrap-datetimepicker.min.js';
    $WEB_APP['view']->display('table.tpl', text('txt_manage_groups_courses'));
  }

  function get_post_group_course()
  {
    $group_course = new group_course();

    $group_course->group = '';

    if (isset($_POST['group'])) {
      $group = get_group($_POST['group']);
      if ($group->name != NULL) {
        $group_course->group = $group->name;
      }
    }

    $group_course->course = '';

    if (isset($_POST['course'])) {
      $course = get_course($_POST['course']);
      if ($course->title != NULL) {
        $group_course->course = $course->title;
      }
    }

    return $group_course;
  }

  function on_confirm_delete()
  {
    global $WEB_APP;
    global $adodb;

    $result = delete_group_courses($_POST['selected_row']);
    if (!$result) {
      $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_group_courses') . "<br>";
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
    $courses = get_courses('title');

    $WEB_APP['title'] = $WEB_APP['title_edit'];
    // After change press.
    $group_course = get_group_course($WEB_APP['id']);
    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    if (!isset($group_course->id)) {
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['id'] >= 0) && is_add_edit_form('group')) {
      $correct_post = TRUE;
      if ($_POST['group'] == '') {
        $WEB_APP['errorstext'] .= text('txt_insert_group') . "<br>";
        $correct_post = FALSE;
      }
      if (trim($_POST['course']) == '') {
        $WEB_APP['errorstext'] .= text('txt_insert_course') . "<br>";
        $correct_post = FALSE;
      }

      $group_course_id = get_group_course_id($_POST['group'], $_POST['course']);
      if (!(($group_course_id == 0) || ($group_course_id == $WEB_APP['id']))) {
        $WEB_APP['errorstext'] .= text('txt_group_course_already_exist_insert_another_group_course') . "<br>";
        $correct_post = FALSE;
      }

      if ($correct_post) {
        edit_group_course($WEB_APP['id'], $_POST['group'], $_POST['course'], $_POST['limited_from'],
          $_POST['limited_to']);
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      }
      $group_course = $this->get_post_group_course();
      redirect($WEB_APP['errorstext']);
    }

    $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
    $WEB_APP['columns'] = array(new column('id', 'id'), new column('group_name', text('txt_group')),
      new column('title', text('txt_course')));

    $WEB_APP['escape'] = TRUE;


    // Form fields.
    $fields = array();
    $fields[] = new field(FALSE, text('txt_from'), "date", "limited_from", $group_course->limited_from);
    $fields[] =
      new field(TRUE, text('txt_group'), "select", "group", $group_course->group, "", $groups, 'id', 'group_name', null,
        FALSE, '', '', 'data-live-search="true"');
    $fields[] = new field(FALSE, text('txt_to'), "date", "limited_to", $group_course->limited_to);
    $fields[] =
      new field(TRUE, text('txt_course'), "select", "course", $group_course->course, "", $courses, 'id', 'title', null,
        FALSE, '', '', 'data-live-search="true"');
    $WEB_APP['fields'] = $fields;
    $WEB_APP['scripts'][] = 'moment-with-locales.min.js';
    $WEB_APP['scripts'][] = 'bootstrap-datetimepicker.min.js';
    $WEB_APP['view']->display('table.tpl', text('txt_manage_groups_courses'));
  }

}

