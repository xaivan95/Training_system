<?php

/**
 * @see module_base
 */
class module_import extends module_base
{

    function __construct()
    {
        global $WEB_APP;
        $WEB_APP['title'] = text('txt_import');

    }

    function view()
    {
        global $WEB_APP;

        $groups = get_groups('group_name');
        $user_grants = get_grants('grant_title');
        $WEB_APP['log'] = array();
        if (is_add_edit_form("group")) {
            $grants = array();


            foreach ($user_grants as $key => $value) {
                if (isset($_POST['grants'][$value['id']])) {
                    $grants[$value['id']] = 1;
                }
            }

            $group = get_group($_POST['group']);

            // isset($group['id']) ?

            $group = $group->name;
            $charset = $_POST['charset'];

            $correct_post = TRUE;
            if ($_POST['group'] == 0) {
                $WEB_APP['errorstext'] .= text('txt_insert_group') . "<br>";
                $correct_post = FALSE;
            }

            if (!isset($_FILES["file"]["error"])) {
                $WEB_APP['errorstext'] .= text('txt_enter_imported_file') . "<br>";
                $correct_post = FALSE;
            } else {
                if ($_FILES["file"]["error"] != UPLOAD_ERR_OK) {
                    $WEB_APP['errorstext'] .= text('txt_enter_imported_file') . "<br>";
                    $correct_post = FALSE;
                }
            }

            if ($_POST['charset'] == '') {
                $WEB_APP['errorstext'] .= text('txt_insert_charset') . "<br>";
                $correct_post = FALSE;
            }

            if ($correct_post) {

                $list = file($_FILES["file"]["tmp_name"]);
                if ($list === FALSE) {
                    $WEB_APP['errorstext'] .= text('txt_error_open_file') . '<br>';
                } else {
                    $count = sizeof($list);
                    if ($charset != 'utf-8') {
                        for ($i = 0; $i < $count; $i++) {
                            $list[$i] = iconv($charset, 'utf-8', $list[$i]);
                            if ($list[$i] === FALSE) {
                                $WEB_APP['errorstext'] .= text('txt_uncorrect_charset') . '<br>';
                                $correct_post = FALSE;
                                break;
                            }
                        }
                    }
                    if ($correct_post) {
                        for ($i = 0; $i < $count; $i = $i + 3) {
                            if (!isset($list[$i]) || !isset($list[$i + 1]) || !isset($list[$i + 2])) {
                                $WEB_APP['errorstext'] .= text('txt_incorrect_file_format') . '<br>';
                                break;
                            }
                            $user_name = trim($list[$i]);
                            $user_password = trim($list[$i + 1]);
                            $user_login = trim($list[$i + 2]);

                            // Set language_id
                            $user = get_user_from_login_password($_SESSION['user_login'], $_SESSION['user_password']);
                            if (($user->id !== null AND $user->name !== 'anonymous') AND ($user->language_id != 0)
                                AND $WEB_APP['settings']['admset_user_change_language']
                            ) {
                                $user_language_id = $user->language_id;
                            } else {
                                $user_language_id = $WEB_APP['settings']['language_id'];
                            }

                            if ($user_login !=
                                htmlspecialchars($user_login)
                            ) {
                                $log[] = text('txt_uncorrect_user_login') . ': ' . $user_login;
                            } else {
                                if (get_user_id($user_login) == 0) {
                                    add_user($_POST['group'], $user_login, $user_password, $user_name, "", "", 0,
                                        isset($_POST['grants']) ? $_POST['grants'] : NULL, $user_language_id);
                                    $WEB_APP['log'][] = text('txt_user_added') . " " . $user_login . '.';
                                } else {
                                    $WEB_APP['log'][] =
                                        text('txt_user') . " " . $user_login . " " . text('txt_already_exist');
                                }
                            }
                        }
                    }

                }

            }
        } else {
            $group = '';
            $charset = '';
        }


        $charsets = array(array('name' => 'cp866', 'value' => 'cp866'),
            array('name' => 'ibm855', 'value' => 'ibm855'),
            array('name' => 'iso-8859-5', 'value' => 'iso-8859-5'),
            array('name' => 'koi8r', 'value' => 'koi8r'),
            array('name' => 'utf-8', 'value' => 'utf-8'),
            array('name' => 'windows-1251', 'value' => 'windows-1251')
        );

        $fields = array();
        $fields[] = new field(TRUE, text('txt_group'), "select", "group",
            $group, "", $groups, "id", "group_name", null, FALSE, '', '', 'data-live-search="true"');
        $fields[] =
            new field(TRUE, text('txt_imported_file'), "file", "file", "", "", "", "", "", NULL, FALSE, 'text/plain');
        $fields[] = new field(TRUE, text('txt_charset'), "select", "charset", $charset, '', $charsets, "name", "value");
        $fields[] = new field(FALSE, text('txt_permissions'), "header");

        foreach ($user_grants as $grant) {

            $fields[] = new field(FALSE, $grant['grant_title'], "checkbox", "grants[" . $grant['id'] . "]",
                isset($grants[$grant['id']]) ? 1 : 0, "grants[" . $grant['id'] . "]");
        }

        $WEB_APP['fields'] = $fields;
        $WEB_APP['submit_title'] = text('txt_import');
        $WEB_APP['form_title'] = text('txt_import');
        $WEB_APP['title'] = text('txt_import_users');
        $WEB_APP['title_edit'] = text('txt_import');
        $WEB_APP['form_enctype'] = TRUE;


        $WEB_APP['view']->display('import.tpl', text('txt_import_users'));
    }
}

