<?php

/**ieldƒ
 * @see module_base
 */
class module_report_book_groups extends module_base
{
  function view()
  {
    global $WEB_APP;

    $user_id = get_user_id($_SESSION['user_login']);
    $groups = get_groups('group_name', 'ASC', 1, 0, NULL, $user_id);
    $select_groups = array();
    $select_books = array();
    $WEB_APP['show_form'] = TRUE;
    $WEB_APP['show_table'] = FALSE;

    if (!isset($_GET['action'])) {
      $_GET['action'] = '';
    }

    $need_show_report =
      isset($_GET['sort']) || isset($_GET['page']) || isset($_GET['count']) || isset($_POST['submit_button']);

    if (isset($_POST['submit_button'])) {
      $_SESSION['report_books']['last_result'] = isset($_POST['last_result']);
      $_SESSION['report_books']['hide_header'] = isset($_POST['hide_header']);
      $_SESSION['report_groups']['view_date_from'] = $_POST['view_date_from'];
      $_SESSION['report_groups']['view_date_to'] = $_POST['view_date_to'];

      // Columns
      $_SESSION['report_books']['group_column'] = isset($_POST['group_column']);
      $_SESSION['report_books']['user_name_column'] = isset($_POST['user_name_column']);
      $_SESSION['report_books']['view_date_column'] = isset($_POST['view_date_column']);
      $_SESSION['report_books']['book_column'] = isset($_POST['book_column']);
      $_SESSION['report_books']['chapter_column'] = isset($_POST['chapter_column']);
    }

    if ($need_show_report) {
      if (isset($_POST['group'])) $_SESSION['report_books']['group_array'] = $_POST['group'];
      foreach ($_SESSION['report_books']['group_array'] as $group_id) {
        $group = get_group($group_id);
        $select_groups[] = $group->name;
      }
      if (isset($_POST['book'])) $_SESSION['report_books']['book_array'] = $_POST['book'];
      foreach ($_SESSION['report_books']['book_array'] as $book_id) {
        $book = get_book($book_id);
        $select_books[] = $book->title;
      }

      $WEB_APP['items_count'] =
        get_book_group_report(TRUE, $_SESSION['report_books']['group_array'], $_SESSION['report_books']['book_array'],
          $_SESSION['report_books']['view_date_from'], $_SESSION['report_books']['view_date_to'],
          $WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'], $WEB_APP['count']);

      $pages = get_pages_count($WEB_APP['items_count'],
        ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
      if ($WEB_APP['page'] > $pages) {
        $WEB_APP['page'] = $pages;
      }

      $user_views =
        get_book_group_report(FALSE, $_SESSION['report_books']['group_array'], $_SESSION['report_books']['book_array'],
          $_SESSION['report_books']['view_date_from'], $_SESSION['report_books']['view_date_to'],
          $WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'], $WEB_APP['count']);

      $WEB_APP['show_form'] = !$_SESSION['report_books']['hide_header'];
      $WEB_APP['show_table'] = TRUE;

      $columns = array();
      if ($_SESSION['report_books']['number_column']) {
        $columns[] = new column('id', 'id');
      }
      if ($_SESSION['report_books']['group_column']) {
        $columns[] = new column('group_name', text('txt_group'));
      }
      $columns[] = new column('user_name', text('txt_user_name'));
      if ($_SESSION['report_books']['view_date_column']) {
        $columns[] = new column('view_date', text('txt_date'));
      }
      if ($_SESSION['report_books']['book_column']) {
        $columns[] = new column('book_title', text('txt_book'));
      }
      if ($_SESSION['report_books']['chapter_column']) {
        $columns[] = new column('chap_title', text('txt_chapter'));
      }

      $paginator = new paginator($WEB_APP['page'], $pages);
      $paginator->url = $WEB_APP['script_name'];
      $paginator->url_query_array = $WEB_APP['url_query_array'];
      $WEB_APP['paginator'] = $paginator;
      $WEB_APP['rows'] = $user_views;
      $WEB_APP['rows_count'] = count($user_views);
      $WEB_APP['columns_count'] = count($columns);
      $WEB_APP['columns'] = $columns;
    }

    if (defined('GROUP_REPORT_LIMITED_SECTIONS') && GROUP_REPORT_LIMITED_SECTIONS == TRUE) {
      $courses = get_unhidden_courses_by_login($_SESSION['user_login']);
    } else {
      $courses = get_courses('title', 'ASC');
    }
    $books = array();
    $course = '';

    if (isset($_SESSION['report_books']['course_id']) && is_scalar($_SESSION['report_books']['course_id'])) {
      $tmp = get_course($_SESSION['report_books']['course_id']);
      if (isset($tmp->id)) {
        $course = $tmp->title;
        $books = get_all_books_by_course_id($tmp->id);
      } else {
        if (!defined('GROUP_REPORT_LIMITED_SECTIONS') || GROUP_REPORT_LIMITED_SECTIONS == FALSE) {
          $books = get_books('book_title');
        }
      }
    }

    // Form fields.
    $fields = array();

    $fields[] =
      new field(TRUE, text('txt_groups'), 'multiple_select', 'group[]', $select_groups, '', $groups, 'id', 'group_name',
        null, FALSE, '', '', 'data-live-search="true"');
    $fields[] = new field(TRUE, text('txt_course'), 'select', 'course_id', $course, '', $courses, 'id', 'title',
      'return change_section_book_group_report()', FALSE, '', '', 'data-live-search="true"');
    $fields[] =
      new field(TRUE, text('txt_books'), 'multiple_select', 'book[]', $select_books, '', $books, 'id', 'book_title',
        null, FALSE, '', '', 'data-live-search="true"');


    $fields[] = new field(FALSE, text('txt_date'), 'header');
    $fields[] =
      new field(FALSE, text('txt_from'), 'date', 'view_date_from', $_SESSION['report_groups']['view_date_from']);
    $fields[] = new field(FALSE, text('txt_to'), 'date', 'view_date_to', $_SESSION['report_groups']['view_date_to']);


    $fields[] = new field(FALSE, text('txt_columns'), 'header');
    $fields[] =
      new field(FALSE, text('txt_group'), 'checkbox', 'group_column', $_SESSION['report_books']['group_column'],
        'group_column');
    $fields[] = new field(FALSE, text('txt_user_name'), 'checkbox', 'user_name_column',
      $_SESSION['report_books']['user_name_column'], 'user_name_column');
    $fields[] =
      new field(FALSE, text('txt_date'), 'checkbox', 'view_date_column', $_SESSION['report_books']['view_date_column'],
        'view_date_column');
    $fields[] = new field(FALSE, text('txt_book'), 'checkbox', 'book_column', $_SESSION['report_books']['book_column'],
      'book_column');
    $fields[] =
      new field(FALSE, text('txt_chapter'), 'checkbox', 'chapter_column', $_SESSION['report_books']['chapter_column'],
        'chapter_column');

    $WEB_APP['editform'] = TRUE;
    $WEB_APP['hide_delete'] = 1;
    $WEB_APP['fields'] = $fields;
    $WEB_APP['title_add'] = text('txt_report_settings');
    $WEB_APP['submit_title'] = text('txt_create');
    $WEB_APP['escape'] = FALSE;
    $WEB_APP['scripts'][] = 'moment-with-locales.min.js';
    $WEB_APP['scripts'][] = 'bootstrap-datetimepicker.min.js';
    $WEB_APP['title'] = text('txt_report_book_groups');
    $WEB_APP['view']->display('table_array_rows.tpl', text('txt_report_book_groups'));
  }

  /** @noinspection PhpUnused */
  function change_course()
  {
    $limited = defined('GROUP_REPORT_LIMITED_SECTIONS') || GROUP_REPORT_LIMITED_SECTIONS == TRUE;

    if (isset($_GET['course_id']) && is_scalar($_GET['course_id'])) {
      $course_id = (int)$_GET['course_id'];
      $_SESSION['report_books']['course_id'] = $course_id;
      if (($course_id !== 0) || !$limited) {
        $section = get_course($course_id);
        $books = get_all_books_by_course_id($section->id);
        foreach ($books as $book) {
          printf("<option value=\"%d\">%s</option>\n", $book['id'], htmlspecialchars($book['book_title']));
        }
      }
      die();
    } else {
      if (!$limited) {
        $books = get_books('book_title','ASC');
        foreach ($books as $book) {
          printf("<option value=\"%d\">%s</option>\n", $book['id'], htmlspecialchars($book['book_title']));
        }
        die();
      }
    }
  }
}

