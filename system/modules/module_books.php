<?php


/**
 * @param $a array
 * @param $b array
 * @return int
 */
function cmp_items($a, $b)
{
  return strcasecmp($a['value'], $b['value']);
}

/**
 * @see module_base
 */
class module_books extends module_base
{
  function __construct()
  {
    global $WEB_APP;
    $WEB_APP['title_edit'] = text('txt_edit_book');
    $WEB_APP['title_delete'] = text('txt_delete_books');
    $WEB_APP['title_add'] = text('txt_add_book');
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
      $result = delete_books($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_books') . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      $WEB_APP['title'] = $WEB_APP['title_delete'];
      $WEB_APP['items'] = get_books_from_array(array($WEB_APP['id']));
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['list_action'] = 'delete';

    }

    $WEB_APP['title'] = $WEB_APP['title_delete'];
    $WEB_APP['items'] = get_books_from_array($_POST['selected_row']);
    $WEB_APP['editform'] = FALSE;
    $WEB_APP['list_action'] = 'delete';

    $WEB_APP['columns'] = array(new column('id', 'id'), new column('book_title', text('txt_name')));

    $WEB_APP['escape'] = TRUE;

    $WEB_APP['submit_title'] = text('txt_delete');


    $WEB_APP['view']->display('list_action.tpl', text('txt_books'));
  }

  function view()
  {
    global $WEB_APP;
    global $adodb;

    $books = $this->get_books();
    $courses = get_courses('title');

    if (is_confirm_delete_action()) {
      $result = delete_books($_POST['selected_row']);
      if (!$result) {
        $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_books') . "<br>";
      }
      $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
      redirect($WEB_APP['errorstext']);
    }
    if (($WEB_APP['action'] == 'delete') && (!is_delete_action())) {
      $book = get_book($WEB_APP['id']);
      if (!isset($book->id)) {
        redirect($WEB_APP['errorstext']);
      }

      $WEB_APP['title'] = $WEB_APP['title_delete'];
      $WEB_APP['items'] = get_books_from_array(array($WEB_APP['id']));
      $WEB_APP['editform'] = FALSE;
      $WEB_APP['action'] = 'delete';

    }
    if ($WEB_APP['action'] == 'view') {
      // After press Add button.
      if (is_add_form('file')) {
        $correct_post = TRUE;

        if ($_POST['file'] == "") {
          $WEB_APP['errorstext'] .= text('txt_insert_file') . "<br>";
          $correct_post = FALSE;
        }

        if ($_POST['course'] == "") {
          $WEB_APP['errorstext'] .= text('txt_insert_course') . "<br>";
          $correct_post = FALSE;
        }

        if ($correct_post) {
          $file = $_SESSION['xml_files'][$_POST['file']];
          $file = CFG_BOOKS_DIR . $file;
          $items = get_xml_data($file);
          $title = get_value_array($items, 'sunravbook/info/title');
          $media_id = get_value_array($items, 'sunravbook/info/mediastorage');
          $guid = get_value_array($items, 'sunravbook/info/guid');

          if (is_string($items) == TRUE) {
            $WEB_APP['errorstext'] .= "XML error: " . $items[0] . "<br>";
            $correct_post = FALSE;
          } elseif (get_book_id($title) != 0) {
            $WEB_APP['errorstext'] .= text('txt_book_already_exist_insert_another_book_name') . "<br>";
            $correct_post = FALSE;
          } elseif (get_book_id_by_multimedia_id($media_id) != 0) {
            $WEB_APP['errorstext'] .= text('txt_book_with_media_id_already_exist') . $media_id . "<br>";
            $correct_post = FALSE;
          } elseif (get_book_id_by_guid($guid) != 0) {
            $WEB_APP['errorstext'] .= text('txt_book_with_guid_already_exist') . $guid . "<br>";
            $correct_post = FALSE;
          }

          if ($correct_post) {
            $book_id = import_book($_SESSION['xml_files'][$_POST['file']]);
            add_book_course($book_id, $_POST['course'], isset($_POST['hidden']) ? 1 : 0);
            $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            redirect($WEB_APP['errorstext']);
          }
        }


      }

      if (is_delete_action()) {
        $WEB_APP['title'] = $WEB_APP['title_delete'];
        $WEB_APP['items'] = get_books_from_array($_POST['selected_row']);
        $WEB_APP['editform'] = FALSE;
        $WEB_APP['action'] = 'delete';
      } else {
        $WEB_APP['title'] = text('txt_books');
        $user_id = get_user_id($_SESSION['user_login']);
        // Get books. On show books.
        $WEB_APP['items_count'] = get_books_count(new book_filter(), $user_id);

        // Pages count.
        $pages = get_pages_count($WEB_APP['items_count'],
          ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);

        if ($WEB_APP['page'] > $pages) {
          $WEB_APP['page'] = $pages;
        }
        $categories = get_books($WEB_APP['sort_field'], $WEB_APP['sort_order'], $WEB_APP['page'], $WEB_APP['count'],
          new book_filter(), $user_id);
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        $WEB_APP['items'] = $categories;

        $paginator = new paginator($WEB_APP['page'], $pages);
        $paginator->url = $WEB_APP['script_name'];
        $paginator->url_query_array = $WEB_APP['url_query_array'];
        $WEB_APP['paginator'] = $paginator;
      }

    }
    $post_values = $this->get_post_book();
    $WEB_APP['row_actions'] = array($WEB_APP['action_view_book'], $WEB_APP['action_update'], $WEB_APP['action_edit'],
      $WEB_APP['action_delete']);
    $WEB_APP['columns'] =
      array(new column('id', 'id'), new column('book_guid', 'GUID'), new column('book_mediastorage', 'Media ID'),
        new column('book_title', text('txt_name')));

    $WEB_APP['escape'] = TRUE;

    $fields = array();
    $fields[] =
      new field(FALSE, text('txt_file'), "select", "file", $post_values['file'], "", $books, "name", "value", null,
        FALSE, '', '', 'data-live-search="true"');

//  Will be realesed later
//    $fields[] =
//      new field(FALSE, text('txt_file'), "file", "uploaded_files[]", "", "", '', "name", "value", null, FALSE, '.srbx',
//        '', '');
    $fields[] =
      new field(TRUE, text('txt_course'), "select", "course", $post_values['course'], '', $courses, 'id', 'title', null,
        FALSE, '', '', 'data-live-search="true"');
    $fields[] = new field(FALSE, text('txt_hidden'), "checkbox", "hidden", $post_values['hidden'] == 1, "hidden");

    $WEB_APP['fields'] = $fields;
    $WEB_APP['view']->display('table.tpl', text('txt_books'));
  }

  function get_books()
  {
    global $WEB_APP;

    $_SESSION['xml_files'] = array();

    $books = array();
    $path = CFG_BOOKS_DIR;
    $pattern = '/([0-9a-zA-Z_]*)\.xml/';
    if ($handle = opendir($path)) {
      $i = 0;
      while (($file_name = readdir($handle)) !== FALSE) {
        if ((!is_dir($path . '/' . $file_name))) {
          // Check if file_name like xxxxx.xml
          if (preg_match($pattern, $file_name, $matches) !== FALSE) {
            $tmp_file_name = $file_name;

            if ($WEB_APP['settings']['use_file_name_charset'] == '1') {
              $file_name = @iconv($WEB_APP['settings']['file_name_charset'], 'utf-8', $file_name);

              if ($file_name === FALSE) {
                $WEB_APP['errorstext'] .= text('txt_incorrect_file_name_charset') . "<br>";
                return array();
              }
            }
            $_SESSION['xml_files'][$i] = $tmp_file_name;

            $books[$file_name] = array('name' => $i, 'value' => $file_name);
            $i++;
          }

        }
      }
      closedir($handle);
    }

    usort($books, 'cmp_items');

    return $books;
  }

  function get_post_book()
  {

    $file = (isset($_POST['file'])) ? trim($_POST['file']) : '';
    $course_id = (isset($_POST['course'])) ? $_POST['course'] : 0;
    $course = get_course($course_id);
    $hidden = (isset($_POST['hidden'])) ? 1 : 0;
    return array('file' => $file, 'course' => $course->title, 'hidden' => $hidden);
  }

  function on_confirm_delete()
  {
    global $WEB_APP;
    global $adodb;

    $result = delete_books($_POST['selected_row']);
    if (!$result) {
      $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_books') . "<br>";
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
    $menu_themes = $this->get_menu_themes();
    $book = get_book($WEB_APP['id']);
    $WEB_APP['title'] = $WEB_APP['title_edit'];
    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
    if (!isset($book->id)) {
      redirect($WEB_APP['errorstext']);
    }
    if (is_add_edit_form('title')) {
      $correct_post = TRUE;
      if (trim($_POST['title']) == '') {
        $WEB_APP['errorstext'] .= text('txt_insert_title') . "<br>";
        $correct_post = FALSE;
      }

      if ($correct_post) {
        $book_id = get_book_id(trim($_POST['title']));
        if (!(($book_id == 0) || ($book_id == $WEB_APP['id']))) {
          $WEB_APP['errorstext'] .= text('txt_title_already_exist_insert_another_title') . "<br>";
          $correct_post = FALSE;
        }
      }

      if ($correct_post) {
        edit_book($this->get_post_edit_book());
      }

      $book = $this->get_post_edit_book();
      redirect($WEB_APP['errorstext']);
    }

    $fields = array();
    $fields[] = new field(TRUE, text('txt_title'), "text", 'title', $book->title, "");
    $fields[] = new field(FALSE, text('txt_author'), "text", 'author', $book->author, "");
    $fields[] = new field(FALSE, text('txt_w_copyright'), "text", 'copyright', $book->copyright, "");
    $fields[] = new field(FALSE, text('txt_mediastorage'), "text", 'mediastorage', $book->mediastorage, "");
    $fields[] =
      new field(FALSE, text('txt_menu_theme'), "select", "theme", $book->theme, "", $menu_themes, "name", "name");
    $fields[] = new field(FALSE, text('txt_description'), "textarea", 'description', $book->description, "");
    $fields[] = new field(FALSE, text('txt_header'), "textarea", 'header', $book->header, "");
    $fields[] = new field(FALSE, text('txt_footer'), "textarea", 'footer', $book->footer, "");

    $WEB_APP['fields'] = $fields;
    $WEB_APP['view']->display('table.tpl', $WEB_APP['title']);
  }

  function get_menu_themes()
  {
    $menu_themes = array();
    $path = CFG_MENU_THEMES_DIR;
    $pattern = '/([0-9a-zA-Z_]*)/';
    if ($handle = opendir($path)) {
      while (($file_name = readdir($handle)) !== FALSE) {
        if ((is_dir($path . '/' . $file_name))) {
          if ((preg_match($pattern, $file_name, $matches) !== FALSE) && (isset($matches[0])) && ($matches[0] != '')) {
            $menu_themes[$file_name] = array('name' => $file_name);
          }

        }
      }
      closedir($handle);
    }

    usort($menu_themes, 'cmp_items');

    return $menu_themes;
  }

  function get_post_edit_book()
  {
    global $WEB_APP;

    $book = get_book($WEB_APP['id']);

    $book->title = (isset($_POST['title'])) ? trim($_POST['title']) : '';
    $book->author = (isset($_POST['author'])) ? trim($_POST['author']) : '';
    $book->copyright = (isset($_POST['copyright'])) ? trim($_POST['copyright']) : '';
    $book->mediastorage = (isset($_POST['mediastorage'])) ? trim($_POST['mediastorage']) : '';
    $book->theme = (isset($_POST['theme'])) ? trim($_POST['theme']) : '';
    $book->description = (isset($_POST['description'])) ? trim($_POST['description']) : '';
    $book->header = (isset($_POST['header'])) ? trim($_POST['header']) : '';
    $book->footer = (isset($_POST['footer'])) ? trim($_POST['footer']) : '';

    return $book;
  }

  function update()
  {
    global $WEB_APP;
    global $adodb;
    $book = get_book($WEB_APP['id']);
    if (!isset($book->id)) {
      redirect($WEB_APP['errorstext']);
    }

    $books = $this->get_books();
    if (is_add_edit_form('file')) {
      $correct_post = TRUE;

      if ($_POST['file'] == "") {
        $WEB_APP['errorstext'] .= text('txt_insert_file') . "<br>";
        $correct_post = FALSE;
      }

      if ($correct_post) {
        $file = $_SESSION['xml_files'][$_POST['file']];
        $file = CFG_BOOKS_DIR . $file;
        $items = get_xml_data($file);
        $title = get_value_array($items, 'sunravbook/info/title');
        $book_id = get_book_id(trim($title));
        if (!(($book_id == 0) || ($book_id == $WEB_APP['id']))) {
          $WEB_APP['errorstext'] .= text('txt_book_already_exist_insert_another_book_name') . "<br>";
          $correct_post = FALSE;
        }
      }

      if ($correct_post) {
        import_book($_SESSION['xml_files'][$_POST['file']], $WEB_APP['id']);
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        redirect($WEB_APP['errorstext']);
      }
    }
    $post_values = $this->get_post_book();
    $fields = array();
    $fields[] = new field(TRUE, text('txt_file'), "select", "file", $post_values['file'], "", $books, "name", "value");
    $WEB_APP['fields'] = $fields;
    $WEB_APP['submit_title'] = text('txt_update');
    $WEB_APP['form_title'] = text('txt_update_book');
    $WEB_APP['title'] = text('txt_update_book') . ' "' . $book->title . '"';
    $WEB_APP['view']->display('table.tpl', text('txt_books'));
  }

  function view_book()
  {
    global $WEB_APP;
    $book = get_book($WEB_APP['id']);

    if (isset($book->id)) {
      header('Location: ' . $WEB_APP['cfg_url'] . '?module=view_books&action=show&book_id=' . $book->id);
      exit();
    }

    header('Location: ' . $WEB_APP['cfg_url'] . '?module=books');
  }
}

