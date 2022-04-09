<?php

/**
 * @see module_base
 */
class module_books_courses extends module_base
{
    function __construct()
    {
        global $WEB_APP;
        $WEB_APP['title_edit'] = text('txt_edit_book_course');
        $WEB_APP['title_delete'] = text('txt_delete_book_courses');
        $WEB_APP['title_add'] = text('txt_add_book_course');
    }

    function get_post_book_course()
    {
        $book_course = new book_course();

        $book_course->book = '';

        if (isset($_POST['book']))
        {
            $book = get_book($_POST['book']);
            if ($book->title != NULL)
            {
                $book_course->book = $book->title;
            }
        }

        $book_course->course = '';

        if (isset($_POST['course']))
        {
            $course = get_course($_POST['course']);
            if ($course->title != NULL)
            {
                $book_course->course = $course->title;
            }
        }

        $book_course->hidden = (isset($_POST['hidden']) ? 1 : 0);

        return $book_course;
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

        if (is_confirm_delete_action())
        {
            $result = delete_book_courses($_POST['selected_row']);
            if (!$result)
            {
                $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_book_courses')."<br>";
            }
            $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['action'] == 'delete') &&  (!is_delete_action()))
        {
            $WEB_APP['title'] = $WEB_APP['title_delete'];
            $WEB_APP['items'] = get_book_courses_from_array(array($WEB_APP['id']));
            $WEB_APP['editform'] = FALSE;
            $WEB_APP['list_action'] = 'delete';
        }

        $WEB_APP['title'] = $WEB_APP['title_delete'];
        $WEB_APP['items'] = get_book_courses_from_array($_POST['selected_row']);
        $WEB_APP['editform'] = FALSE;
        $WEB_APP['list_action'] = 'delete';

        $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
        $WEB_APP['list_actions'] = array($WEB_APP['list_action_delete'], $WEB_APP['list_action_move']);
        $WEB_APP['columns'] = array(new column('id', 'id'),
                        new column('book_title', text('txt_book')),
                        new column('title', text('txt_course')),
                        new column('hidden', text('txt_hidden')) );

        $WEB_APP['escape'] = TRUE;

        $WEB_APP['submit_title'] = text('txt_delete');


        $WEB_APP['view']->display('list_action.tpl', text('txt_manage_books_courses'));
    }

    function on_confirm_delete()
    {
        global $WEB_APP;
        global $adodb;

        $result = delete_book_courses($_POST['selected_row']);
        if (!$result)
        {
            $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_book_courses')."<br>";
            $this->on_delete();
            exit();
        }
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        redirect($WEB_APP['errorstext']);
    }

    function on_move()
    {
        global $WEB_APP;
        if (!isset($_POST['selected_row']))
        {
            $this->view();
            exit();
        }
        $courses = get_courses('title');
        $book_course = new book_course();


        $WEB_APP['title'] = text('txt_move');
        $WEB_APP['items'] = get_book_courses_from_array($_POST['selected_row']);
        $WEB_APP['editform'] = FALSE;
        $WEB_APP['list_action'] = 'move';

        $WEB_APP['columns'] = array(new column('id', 'id'),
                        new column('book_title', text('txt_book')),
                        new column('title', text('txt_course')),
                        new column('hidden', text('txt_hidden')) );

        $WEB_APP['escape'] = TRUE;

        // Form fields.
        $fields = array();

        $fields[] = new field(  TRUE, text('txt_course'), "select", "course",
                    $book_course->course, "", $courses, 'id', 'title', null, FALSE, '', '', 'data-live-search="true"');


        $WEB_APP['fields'] = $fields;
        $WEB_APP['list_action'] = 'move';
        $WEB_APP['submit_title'] = text('txt_move');
        $WEB_APP['view']->display('list_action.tpl', text('txt_manage_books_courses'));
    }

  /** @noinspection PhpUnused */
    function on_confirm_move()
    {
        global $WEB_APP;
        if ($_POST['course'] == 0)
        {
            $WEB_APP['errorstext'] .= text('txt_insert_course')."<br>";
            $this->on_move();
            exit();
        }
        $result = move_books($_POST['course'], $_POST['selected_row']);
        if (!$result)
        {
            $WEB_APP['errorstext'] .= text('txt_unpossible_move_all_book_courses')."<br>";
        }
        //$WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        redirect($WEB_APP['errorstext']);
        $this->view();
    }

    function view()
    {
        global $WEB_APP;
        global $adodb;
        $books = get_books('book_title', 'ASC');
        $courses = get_courses('title', 'ASC');
        $book_course = new book_course();
        if (is_confirm_delete_action())
        {
            $result = delete_book_courses($_POST['selected_row']);
            if (!$result)
            {
                $WEB_APP['errorstext'] .= text('txt_unpossible_delete_all_book_courses')."<br>";
            }
            $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            redirect($WEB_APP['errorstext']);
        }
        if (is_confirm_move_action())
        {
            if ($_POST['course'] == 0)
            {
                $WEB_APP['errorstext'] .= text('txt_insert_course')."<br>";
                $this->on_move();
                exit();
            }
            $result = move_books($_POST['course'], $_POST['selected_row']);
            if (!$result)
            {
                $WEB_APP['errorstext'] .= text('txt_unpossible_move_all_book_courses')."<br>";
            }
            //$WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            redirect($WEB_APP['errorstext']);
            $this->view();
            exit();
        }

        if (($WEB_APP['action'] == 'delete') &&  (!is_delete_action()))
        {
            $book_course = get_book_course($WEB_APP['id']);
            if (!isset($book_course->id))
            {
                redirect($WEB_APP['errorstext']);
            }

            $WEB_APP['title'] = $WEB_APP['title_delete'];
            $WEB_APP['items'] = get_book_courses_from_array(array($WEB_APP['id']));
            $WEB_APP['editform'] = FALSE;
            $WEB_APP['action'] = 'delete';

        }

        if ($WEB_APP['action'] == 'view')
        {
            // After press Add button.
            if (is_add_form('book'))
            {
                $correct_post = TRUE;
                if ($_POST['book'] == '')
                {
                    $WEB_APP['errorstext'] .= text('txt_insert_book')."<br>";
                    $correct_post = FALSE;
                }
                if (trim($_POST['course']) == "")
                {
                    $WEB_APP['errorstext'] .= text('txt_insert_course')."<br>";
                    $correct_post = FALSE;
                }


                if ($correct_post && (get_book_course_id($_POST['book'], $_POST['course']) != 0))
                {
                    $WEB_APP['errorstext'] .= text('txt_book_course_already_exist_insert_another_book_course')."<br>";
                    $correct_post = FALSE;
                }

                if ($correct_post)
                {
                    add_books_course($_POST['book'], $_POST['course'] , isset($_POST['hidden']) ? 1 : 0);
                    $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
                    redirect($WEB_APP['errorstext']);
                }
            }

            if (is_delete_action())
            {
                $WEB_APP['title'] = $WEB_APP['title_delete'];
                $WEB_APP['items'] = get_book_courses_from_array($_POST['selected_row']);
                $WEB_APP['editform'] = FALSE;
                $WEB_APP['action'] = 'delete';
            }
            else
            {
                $WEB_APP['title'] = text('txt_manage_books_courses');
                // Get sections. On show sections.
                $WEB_APP['items_count'] = get_book_courses_count(new book_course_filter());

                // Pages count.
                $pages = get_pages_count($WEB_APP['items_count'], ($WEB_APP['count'] == 0) ? $WEB_APP['items_count'] : $WEB_APP['count']);
                if ($WEB_APP['page'] > $pages)
                {
                    $WEB_APP['page'] = $pages;
                }
                $book_coursees = get_book_courses($WEB_APP['sort_field'],
                            $WEB_APP['sort_order'],
                            $WEB_APP['page'],
                            $WEB_APP['count'],
                            new book_course_filter()
                            );
                $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
                $WEB_APP['items'] = $book_coursees;

                $paginator = new paginator($WEB_APP['page'], $pages);
                $paginator->url = $WEB_APP['script_name'];
                $paginator->url_query_array  = $WEB_APP['url_query_array'];
                $WEB_APP['paginator'] = $paginator;
            }
            $book_course = $this->get_post_book_course();
        }

        $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
        $WEB_APP['list_actions'] = array($WEB_APP['list_action_delete'], $WEB_APP['list_action_move']);
        $WEB_APP['columns'] = array(new column('id', 'id'),
                        new column('book_title', text('txt_book')),
                        new column('title', text('txt_course')),
                        new column('hidden', text('txt_hidden')) );

        $WEB_APP['escape'] = TRUE;



        // Form fields.
        $fields = array();
        $fields[] = new field(  TRUE, text('txt_book'), "multiple_select", "book[]",
            $books, "", $books, 'id', 'book_title', null, FALSE, '', '', 'data-live-search="true"');
        $fields[] = new field(  TRUE, text('txt_course'), "select", "course",
            $book_course->course, "", $courses, 'id', 'title', null, FALSE, '', '', 'data-live-search="true"');
        $fields[] = new field(  FALSE, text('txt_hidden'), "checkbox", "hidden",
            $book_course->hidden, "hidden");

        $WEB_APP['fields'] = $fields;
        $WEB_APP['view']->display('table.tpl', text('txt_manage_books_courses'));
    }

    function edit()
    {
        global $WEB_APP;
        global $adodb;

        $books = get_books('book_title');
        $courses = get_courses('title');

        $WEB_APP['title'] = $WEB_APP['title_edit'];
        // After change press.
        $book_course = get_book_course($WEB_APP['id']);
        $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
        if (!isset($book_course->id))
        {
            redirect($WEB_APP['errorstext']);
        }
        if (($WEB_APP['id'] >= 0) && is_add_edit_form('book'))
        {
            $correct_post = TRUE;
            if ($_POST['book'] == '')
            {
                $WEB_APP['errorstext'] .= text('txt_insert_book')."<br>";
                $correct_post = FALSE;
            }
            if ($_POST['course'] == '')
            {
                $WEB_APP['errorstext'] .= text('txt_insert_course')."<br>";
                $correct_post = FALSE;
            }

            $book_course_id = get_book_course_id($_POST['book'], $_POST['course']);
            if (!(  ($book_course_id == 0) ||
                ($book_course_id == $WEB_APP['id'])))
            {
                $WEB_APP['errorstext'] .= text('txt_book_course_already_exist_insert_another_book_course')."<br>";
                $correct_post = FALSE;
            }

            if ($correct_post)
            {
                edit_book_course($WEB_APP['id'], $_POST['book'], $_POST['course'], isset($_POST['hidden']) ? 1 : 0);
                $WEB_APP['errorstext'] .= $adodb->ErrorMsg();
            }
            $book_course = $this->get_post_book_course();
            redirect($WEB_APP['errorstext']);
        }

        $WEB_APP['row_actions'] = array($WEB_APP['action_edit'], $WEB_APP['action_delete']);
        $WEB_APP['list_actions'] = array($WEB_APP['list_action_delete'], $WEB_APP['list_action_move']);
        $WEB_APP['columns'] = array(new column('id', 'id'),
                        new column('book_title', text('txt_book')),
                        new column('title', text('txt_course')),
                        new column('hidden', text('txt_hidden')) );

        $WEB_APP['escape'] = TRUE;



        // Form fields.
        $fields = array();
        $fields[] = new field(  TRUE, text('txt_book'), "select", "book",
                    $book_course->book, "", $books, 'id', 'book_title');

        $fields[] = new field(  TRUE, text('txt_course'), "select", "course",
                    $book_course->course, "", $courses, 'id', 'title', null, FALSE, '', '', 'data-live-search="true"');
        $fields[] = new field(  FALSE, text('txt_hidden'), "checkbox", "hidden",
                    $book_course->hidden, "hidden");

        $WEB_APP['fields'] = $fields;
        $WEB_APP['view']->display('table.tpl', text('txt_manage_books_courses'));
    }

}

