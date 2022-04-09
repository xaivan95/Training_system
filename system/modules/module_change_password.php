<?php

/**
 * @see module_base
 */
class module_change_password extends module_base
{
    function view()
    {
        global $WEB_APP;

        if (isset($_POST['password']))
        {
            $correct_post = TRUE;

            if (strlen($_POST['password']) == 0)
            {
                $WEB_APP['errorstext'] .= text('txt_insert_password')."<br>";
                $correct_post = FALSE;
            }
            else
            {
                $user = get_user_from_login_password($_SESSION['user_login'], $_POST['password']);
                if (!isset($user->id))
                {
                    $WEB_APP['errorstext'] .= text('txt_insert_correct_password')."<br>";
                    $correct_post = FALSE;
                }
            }

            if (strlen($_POST['new_password']) == 0)
            {
                $WEB_APP['errorstext'] .= text('txt_insert_new_password')."<br>";
                $correct_post = FALSE;
            }

            if (strlen($_POST['confirm_password']) == 0)
            {
                $WEB_APP['errorstext'] .= text('txt_insert_confirm_password')."<br>";
                $correct_post = FALSE;
            }
            else
            {
                if ($_POST['new_password'] != $_POST['confirm_password'])
                {
                    $WEB_APP['errorstext'] .= text('txt_your_password_entries_did_not_match')."<br>";
                    $correct_post = FALSE;
                }
                else
                {
                    if ($_SESSION['user_login'] == $_POST['new_password'])
                    {
                        $WEB_APP['errorstext'] .= text('txt_your_password_is_too_similar_to_your_login')."<br>";
                        $correct_post = FALSE;
                    }
                    else
                    {
                    if (strlen($_POST['new_password']) < 6)
                        {
                            $WEB_APP['errorstext'] .= text('txt_your_password_must_be_at_least_6_characters')."<br>";
                            $correct_post = FALSE;
                        }
                    }
                }
            }

            if ($correct_post)
            {
                change_user_password($user->id, $_POST['new_password']);
                $_SESSION['user_password'] = $_POST['new_password'];
                header('Location: '.$WEB_APP['cfg_url']);
                exit();
            }
        }
        else
        {
            $password = "";
            $new_password = "";
            $confirm_password = "";
        }

        if (!isset($password))
        {
            $password = '';
        }

        if (!isset($new_password))
        {
            $new_password = '';
        }

        if (!isset($confirm_password))
        {
            $confirm_password = '';
        }

        $fields = array();

        $fields[] = new field(  TRUE, text('txt_password'), "password", "password",
                    $password);
        $fields[] = new field(  TRUE, text('txt_new_password'), "password",
                    "new_password", $new_password);
        $fields[] = new field(  TRUE, text('txt_confirm_password'), "password",
                    "confirm_password", $confirm_password);
        $WEB_APP['fields'] = $fields;

        $WEB_APP['title'] = text('txt_change_password');
        $WEB_APP['form_title'] = text('txt_change_password');
        $WEB_APP['submit_title'] = text('txt_change');
        $WEB_APP['view']->display('form_page.tpl', text('txt_change_password'));
    }

}

