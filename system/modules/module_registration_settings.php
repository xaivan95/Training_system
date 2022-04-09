<?php

/**
 * @see module_base
 */
class module_registration_settings extends module_base
{
    function view()
    {
        global $WEB_APP;

        $grants = get_grants('grant_title');
        $WEB_APP['title'] = text('txt_configure');

        if (is_add_edit_form('reg_mailfrom'))
        {
            // Get $_POST values.
            $cfg_reg_mailnotify = isset($_POST['reg_mailnotify']) ? 1 : 0;
            $cfg_reg_mailfrom   = isset($_POST['reg_mailfrom']) ? $_POST['reg_mailfrom'] : '';
            $cfg_reg_mailbegin  = isset($_POST['reg_mailbegin']) ? $_POST['reg_mailbegin'] : '';
            $cfg_reg_mailend    = isset($_POST['reg_mailend']) ? $_POST['reg_mailend'] : '';
            $cfg_reg_grants     = isset($_POST['reg_grants']) ? $_POST['reg_grants'] : array();

            // Check $_POST values.
            $correct_post = TRUE;
            if (isset($_POST['reg_mailnotify']))
            {
                if (trim($_POST['reg_mailfrom']) == '')
                {
                    $WEB_APP['errorstext'] .= text('txt_insert_mail').'<br>';
                    $correct_post = FALSE;
                }
                else
                {
                    if (!is_email($_POST['reg_mailfrom']))
                    {
                        $WEB_APP['errorstext'] .= text('txt_insert_correct_mail').'<br>';
                        $correct_post = FALSE;
                    }
                }
            }

            if ($correct_post)
            {
                // Set settings.
                setting_set('reg_mailnotify', $cfg_reg_mailnotify);
                setting_set('reg_mailfrom',   $cfg_reg_mailfrom);
                setting_set('reg_mailbegin',  $cfg_reg_mailbegin);
                setting_set('reg_mailend',    $cfg_reg_mailend);
                setting_set('reg_grants',     serialize($cfg_reg_grants));
            }

            redirect($WEB_APP['errorstext']);
        }
        else
        {
            // Get config values.
            $cfg_reg_mailnotify = $WEB_APP['settings']['reg_mailnotify'];
            $cfg_reg_mailfrom   = $WEB_APP['settings']['reg_mailfrom'];
            $cfg_reg_mailbegin  = $WEB_APP['settings']['reg_mailbegin'];
            $cfg_reg_mailend    = $WEB_APP['settings']['reg_mailend'];
            $cfg_reg_grants     = @unserialize($WEB_APP['settings']['reg_grants']);
        }

        // Form fields.
        $fields = array();
        $fields[] = new field(FALSE, text('txt_notify_by_mail_when_user_complete_a_test'),
                    'checkbox', 'reg_mailnotify',    $cfg_reg_mailnotify, 'reg_mailnotify');

        $fields[] = new field(  FALSE, text('txt_send_e_mail_after_registering'),
                    "header");
        $fields[] = new field(  FALSE, text('txt_mail_header'),
                    "textarea", "reg_mailbegin",
                    $cfg_reg_mailbegin, "");
        $fields[] = new field(  FALSE, text('txt_mail_footer'),
                    "textarea", "reg_mailend",
                    $cfg_reg_mailend, "");
        $fields[] = new field(  FALSE, text('txt_mail_from'),  "email", "reg_mailfrom",
            $cfg_reg_mailfrom, "");

        $fields[] = new field(  FALSE, text('txt_permissions'), "header");

        foreach($grants as $grant)
        {
            $fields[] = new field(  FALSE, $grant['grant_title'], "checkbox",
                "reg_grants[".$grant['id']."]", isset($cfg_reg_grants[$grant['id']]), "reg_grants[".$grant['id']."]");
        }

        $WEB_APP['fields'] = $fields;
        $WEB_APP['show_empty_value'] = FALSE;
        $WEB_APP['submit_title'] = text('txt_change');
        $WEB_APP['form_title'] = text('txt_configure');
        $WEB_APP['title_edit'] = text('txt_registration_settings');
        $WEB_APP['unshow_asterisk'] = TRUE;

        $WEB_APP['view']->display('form_page.tpl', text('txt_registration_settings'));
    }

}

