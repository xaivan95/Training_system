<?php

/**
 * @see module_base
 */
class module_view_books extends module_base
{
  function view()
  {
    global $WEB_APP;
    if (isset($_SESSION['infotext'])) $WEB_APP['infotext'] = $_SESSION['infotext'];

    $courses = get_unhidden_courses_by_login($_SESSION['user_login']);
    $course_number = 0;
    foreach ($courses as $course) {
      $WEB_APP['courses'][] = $course['title'];
      $books = get_books_by_course_id($course['id']);
      $WEB_APP['courses_books_count'][] = 0;
      foreach ($books as $book) {
        $WEB_APP['books_id'][] = $book['id'];
        $WEB_APP['books_title'][] = htmlspecialchars($book['book_title']);
        $WEB_APP['books_description'][] = $book['book_description'];
        $WEB_APP['books_course'][] = $WEB_APP['courses'][$course_number];
        $WEB_APP['courses_books_count'][$course_number]++;
      }
      $course_number++;
    }

    $WEB_APP['title'] = text('txt_view_books');
    $WEB_APP['submit_title'] = text('txt_view');
    $WEB_APP['view']->display('view_books.tpl', text('txt_view_books'));
  }

  function menu()
  {
    global $WEB_APP;
    $book = get_book($_SESSION['book_id']);
    $WEB_APP['select_book'] = text('txt_view_books');
    $WEB_APP['book'] = $book;
    $WEB_APP['view']->display('view_book_menu.tpl', text('txt_404_not_found'), TRUE);
  }

  function content()
  {
    global $WEB_APP;
    $book = get_book($_SESSION['book_id']);
    $book->contents = trim($book->contents);
    if (isset($_GET['id'])) {
      $chapter = get_chapter_by_id($book->id, $_GET['id']);
      $_SESSION['chapter_id'] = $_GET['id'];
    } else {
      $chapter = get_first_chapter_by_book_id($book->id);
    }
    $user_id = get_user_id($_SESSION['user_login']);
    $chapter->text = replace_completed_tests_links($user_id, $chapter->text);
    if (defined('TRANSLATE_BOOK') && TRANSLATE_BOOK == TRUE) {
      $chapter->text = preg_replace_callback('/(txt_\S+;)/m', function ($m) use ($WEB_APP) {
        return ($WEB_APP['text'][substr($m[0], 0, -1)]);
      }, $chapter->text);
    }
    $WEB_APP['book_css'] = $WEB_APP['cfg_url'] . 'media/' . $book->mediastorage . '/style.css';
    $WEB_APP['book'] = $book;
    $WEB_APP['chapter'] = $chapter;
    $WEB_APP['html_header'] = $book->html_header;
    $WEB_APP['id'] = $book->id;

    $user_id = get_user_id($_SESSION['user_login']);
    add_viewed_chapter($user_id, $book->id, $chapter->id);
    $WEB_APP['view']->display('view_book_content.tpl', text('txt_view_books'),
      ($book->engine_version < 5) && ($book->contents !== ''));
  }

  function show()
  {
    global $WEB_APP;
    if (isset($_GET['book_id'])) {
      $book = get_book($_GET['book_id']);
    } elseif (isset($_GET['bid'])) {
      $book = get_book($_GET['bid']);
    } elseif (isset($_GET['bmid'])) {
      $book = get_book_by_multimedia_id($_GET['bmid']);
    }

    if (isset($book)) {
      if (isset($book->id)) {
        $books = get_book_by_login($_SESSION['user_login'], $book->id);
        if (($books[0]['id'] > 0) && book_available_for_user($_SESSION['user_login'], $book->id)) {
          $user_id = get_user_id($_SESSION['user_login']);
          $book->contents = trim($book->contents);
          $WEB_APP['book'] = $book;
          $WEB_APP['id'] = $book->id;
          $_SESSION['book_id'] = $book->id;
          $WEB_APP['html_header'] = $book->html_header;
          if (isset($_GET['cidx'])) {
            $chapter = get_chapter_by_id($book->id, $_GET['cidx']);
          } elseif (isset($_GET['cguid'])) {
            $chapter = get_chapter_by_guid($book->id, $_GET['cguid']);
          } elseif (isset($_GET['chap_index'])) {
            $chapter = get_chapter_by_index($book->id, $_GET['chap_index']);
          }
          if (isset($chapter)) {
            $chapter->text = replace_completed_tests_links($user_id, $chapter->text);
            add_viewed_chapter($user_id, $book->id, $chapter->id);
            $_SESSION['chapter_id'] = $chapter->id;
            $WEB_APP['chapter'] = $chapter;
            $WEB_APP['view']->display('view_book_content.tpl', text('txt_404_not_found'),
              ($book->engine_version < 5) && ($book->contents !== ''));
            exit();
          } else {
            $chapter = get_first_chapter_by_book_id($book->id);
            $WEB_APP['chapter'] = $chapter;
          }

          $chapter->text = replace_completed_tests_links($user_id, $chapter->text);
          if (defined('TRANSLATE_BOOK') && TRANSLATE_BOOK == TRUE) {
            $chapter->text = preg_replace_callback('/(txt_\S+;)/m', function ($m) use ($WEB_APP) {
              return ($WEB_APP['text'][substr($m[0], 0, -1)]);
            }, $chapter->text);
          }
          $_SESSION['book_id'] = $book->id;
          $_SESSION['chapter_id'] = $chapter->id;
          add_viewed_chapter($user_id, $book->id, $chapter->id);
          $WEB_APP['view']->display('view_book.tpl', text('txt_view_books'),
            ($book->engine_version < 5) && ($book->contents !== ''));
          exit();
        }

        $WEB_APP['action'] = 'view';
        $WEB_APP['title'] = text('txt_403_forbidden');
        $WEB_APP['view']->display('403.tpl', text('txt_403_forbidden'));
        exit();
      }
    } else {
      $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") .
        "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      $_SESSION['infotext'] = text('txt_book_not_found') . "<br>" . $actual_link;
    }

    header('Location: ' . $WEB_APP['cfg_url'] . '?module=view_books');
  }

}

