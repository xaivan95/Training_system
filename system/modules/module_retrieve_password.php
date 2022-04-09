<?php

/**
 * @see module_base
 */
class module_retrieve_password extends module_base
{
    function view()
    {
        global $WEB_APP;

        $fields = array();

        if (isset($_POST['user_info']))
        {
            $correct_post = TRUE;
            $user_id=0;

            if (strlen(trim($_POST['user_info'])) == 0)
            {
                $WEB_APP['errorstext'] .= text('txt_insert_login_or_email')."<br>";
                $correct_post = FALSE;
            }
            else
            {
                $user_info = trim($_POST['user_info']);
                $user_id = find_user($user_info);

                if ($user_id == 0)
                {
                    $WEB_APP['errorstext'] .= text('txt_user_not_found')."<br>";
                    $correct_post = FALSE;
                }
                else
                {
                    $user = get_user($user_id);
                    if ($user->mail == "")
                    {
                        $WEB_APP['errorstext'] .= text('txt_user_without_email_contact_to_support')."<br>";
                        $correct_post = FALSE;
                    }
                }
            }

            if ($correct_post)
            {
                $new_password = create_password();
                $user = get_user($user_id);
                change_user_password($user_id, $new_password);
                $to = $user->mail;
                $parse_url_array = parse_url(CFG_URL);
                $subject = text('txt_retrieve_password_on')." ".$parse_url_array['host'];
                $message = text('txt_login').': '.$user->login."\r\n".
                        text('txt_password').': '.$new_password."\r\n\r\n".
                        '---';

                $headers = 'From: accounts@'. $parse_url_array['host']. "\r\n" .
                'Reply-To: accounts@'.$parse_url_array['host'] . "\r\n" .
                "Content-Type: text/plain; charset=utf-8\r\n".
                'X-Mailer: PHP/' . phpversion();

                mail($to, $subject, $message, $headers);
                $WEB_APP['infotext'] = text('txt_new_password_send_on_your_email');

            }
        }
        else
        {
            $user_info = "";
        }

        if (!isset($user_info))
        {
            $user_info = '';
        }


        $fields[] = new field(TRUE, text('txt_login_or_email'), "text",
                "user_info", $user_info);

        $WEB_APP['fields'] = $fields;
        $WEB_APP['submit_title'] = text('txt_send');

        $WEB_APP['title'] = text('txt_retrieve_password');
        $WEB_APP['form_title'] = text('txt_retrieve_password');
        $WEB_APP['view']->display('form_page.tpl', text('txt_retrieve_password'));
    }

}

