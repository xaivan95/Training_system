<?php

class module_testing_settings extends module_base
{
  function view()
  {
    global $WEB_APP;

    $WEB_APP['title'] = text('txt_testing_settings');

    $tst_test_selection_styles = array();
    $tst_test_selection_styles[] = array('name' => text('txt_select_test_from_section'), 'value' => 1);
    $tst_test_selection_styles[] = array('name' => text('txt_all_tests_in_list'), 'value' => 2);
    $reg_exp_array = array();
    $reg_exp_array[] = array('name' => 'POSIX', 'value' => 'POSIX');
    $reg_exp_array[] = array('name' => 'Perl', 'value' => 'Perl');

    if (is_add_edit_form('tst_test_selection_style')) {
      if (($_POST['tst_test_selection_style'] > 0) && ($_POST['tst_test_selection_style'] < 3)) {
        $selection_style = $tst_test_selection_styles[$_POST['tst_test_selection_style'] - 1]['name'];
      } else {
        $selection_style = $tst_test_selection_styles[0]['name'];
      }
      $tst_showstats = isset($_POST['tst_showstats']);
      $tst_showquestionnumber = isset($_POST['tst_showquestionnumber']);
      $tst_collect_ip = isset($_POST['$tst_collect_ip']);

      $admset_regexpformat = $_POST['admset_regexpformat'];
      $admset_dateformat = $_POST['admset_dateformat'];
      $admset_percprecision = $_POST['admset_percprecision'];

      $tst_showrating = isset($_POST['tst_showrating']);
      $tst_ratingquantity = $_POST['tst_ratingquantity'];
      $tst_resmailuser_send = isset($_POST['tst_resmailuser_send']);
      $tst_resmailuser_subject = $_POST['tst_resmailuser_subject'];
      $tst_resmailuser_from = $_POST['tst_resmailuser_from'];
      $tst_resmailuser_template = $_POST['tst_resmailuser_template'];

      $admset_resmail_send = isset($_POST['admset_resmail_send']);
      $admset_resmail_subject = $_POST['admset_resmail_subject'];
      $admset_resmail_to = $_POST['admset_resmail_to'];
      $admset_resmail_from = $_POST['admset_resmail_from'];
      $admset_resmail_template = $_POST['admset_resmail_template'];

      $correct_post = TRUE;
      if (isset($_POST['tst_resmailuser_send'])) {
        if ($_POST['tst_resmailuser_from'] == '') {
          $WEB_APP['errorstext'] .= text('txt_insert_mail') . "<br>";
          $correct_post = FALSE;
        } else {
          if (!is_email($_POST['tst_resmailuser_from'])) {
            $WEB_APP['errorstext'] .= text('txt_resmailuser_from_does_not_correct_insert_correct_email') . "<br>";
            $correct_post = FALSE;
          }
        }


      }

      if (isset($_POST['admset_resmail_send'])) {
        if ($_POST['admset_resmail_to'] == '') {
          $WEB_APP['errorstext'] .= text('txt_insert_resmail_to_mail') . "<br>";
          $correct_post = FALSE;
        } else {
          if (!is_email($_POST['admset_resmail_to'])) {
            $WEB_APP['errorstext'] .= text('txt_send_email_to_address_does_not_correct_insert_correct_email') . "<br>";
            $correct_post = FALSE;
          }
        }

        if ($_POST['admset_resmail_from'] == '') {
          $WEB_APP['errorstext'] .= text('txt_insert_resmail_from_mail') . "<br>";
          $correct_post = FALSE;
        } else {
          if (!is_email($_POST['admset_resmail_from'])) {
            $WEB_APP['errorstext'] .= text('txt_resmailuser_from_does_not_correct_insert_correct_email') . "<br>";
            $correct_post = FALSE;
          }
        }
      }

      if ($correct_post) {
        setting_set('tst_test_selection_style', $_POST['tst_test_selection_style']);
        setting_set('tst_showstats', isset($_POST['tst_showstats']) ? 1 : 0);
        setting_set('tst_showquestionnumber', isset($_POST['tst_showquestionnumber']) ? 1 : 0);
        setting_set('tst_collect_ip', isset($_POST['tst_collect_ip']) ? 1 : 0);

        setting_set('admset_regexpformat', $_POST['admset_regexpformat']);
        setting_set('admset_dateformat', $_POST['admset_dateformat']);
        setting_set('admset_percprecision', $_POST['admset_percprecision']);

        setting_set('tst_showrating', isset($_POST['tst_showrating']) ? 1 : 0);
        setting_set('tst_ratingquantity', $_POST['tst_ratingquantity']);
        setting_set('tst_resmailuser_send', isset($_POST['tst_resmailuser_send']) ? 1 : 0);

        setting_set('tst_resmailuser_subject', $_POST['tst_resmailuser_subject']);
        setting_set('tst_resmailuser_from', $_POST['tst_resmailuser_from']);
        setting_set('tst_resmailuser_template', $_POST['tst_resmailuser_template']);

        setting_set('admset_resmail_send', isset($_POST['admset_resmail_send']));
        setting_set('admset_resmail_subject', $_POST['admset_resmail_subject']);
        setting_set('admset_resmail_to', $_POST['admset_resmail_to']);
        setting_set('admset_resmail_from', $_POST['admset_resmail_from']);
        setting_set('admset_resmail_template', $_POST['admset_resmail_template']);


        redirect($WEB_APP['errorstext']);
      }

    } else {
      if (($WEB_APP['settings']['tst_test_selection_style'] > 0) &&
        ($WEB_APP['settings']['tst_test_selection_style'] < 3)) {
        $selection_style = $tst_test_selection_styles[$WEB_APP['settings']['tst_test_selection_style'] - 1]['name'];
      } else {
        $selection_style = $tst_test_selection_styles[0]['name'];
      }
      $tst_showstats = $WEB_APP['settings']['tst_showstats'];
      $tst_showquestionnumber = $WEB_APP['settings']['tst_showquestionnumber'];
      $tst_collect_ip = $WEB_APP['settings']['tst_collect_ip'];

      $admset_regexpformat = $WEB_APP['settings']['admset_regexpformat'];
      $admset_dateformat = $WEB_APP['settings']['admset_dateformat'];
      $admset_percprecision = $WEB_APP['settings']['admset_percprecision'];

      $tst_showrating = $WEB_APP['settings']['tst_showrating'];
      $tst_ratingquantity = $WEB_APP['settings']['tst_ratingquantity'];
      $tst_resmailuser_send = $WEB_APP['settings']['tst_resmailuser_send'];
      $tst_resmailuser_subject = $WEB_APP['settings']['tst_resmailuser_subject'];
      $tst_resmailuser_from = $WEB_APP['settings']['tst_resmailuser_from'];
      $tst_resmailuser_template = $WEB_APP['settings']['tst_resmailuser_template'];

      $admset_resmail_send = $WEB_APP['settings']['admset_resmail_send'];
      $admset_resmail_subject = $WEB_APP['settings']['admset_resmail_subject'];
      $admset_resmail_to = $WEB_APP['settings']['admset_resmail_to'];
      $admset_resmail_from = $WEB_APP['settings']['admset_resmail_from'];
      $admset_resmail_template = $WEB_APP['settings']['admset_resmail_template'];
    }


    $fields = array();

    $fields[] =
      new field(FALSE, text('txt_test_selection_style'), 'select', 'tst_test_selection_style', $selection_style, '',
        $tst_test_selection_styles, 'value', 'name');
    $fields[] =
      new field(FALSE, text('txt_regular_expression_format'), "select", "admset_regexpformat", $admset_regexpformat, "",
        $reg_exp_array, "value", "name");
    $fields[] = new field(FALSE, text('txt_date_format'), "text", 'admset_dateformat', $admset_dateformat, "");
    $fields[] =
      new field(FALSE, text('txt_percent_precision'), "number", "admset_percprecision", $admset_percprecision, "");
    $fields[] = new field(FALSE, text('txt_display_statistics'), 'checkbox', 'tst_showstats', $tst_showstats,
      'txt_display_statistics');
    $fields[] = new field(FALSE, text('txt_display_question_number'), 'checkbox', 'tst_showquestionnumber',
      $tst_showquestionnumber, 'tst_showquestionnumber');
    $fields[] =
      new field(FALSE, text('txt_collect_ip'), 'checkbox', 'tst_collect_ip', $tst_collect_ip, 'tst_collect_ip');


    //---------------------------------------------------------
    $fields[] = new field(FALSE, text('txt_after_testing'), 'header');
    $fields[] =
      new field(FALSE, text('txt_display_rating'), 'checkbox', 'tst_showrating', $tst_showrating, 'tst_showrating');
    $fields[] =
      new field(FALSE, text('txt_notify_by_e_mail_when_user_complete_a_test'), 'checkbox', 'tst_resmailuser_send',
        $tst_resmailuser_send, 'tst_resmailuser_send');
    $fields[] =
      new field(FALSE, text('txt_number_of_results_in_the_rating'), 'number', 'tst_ratingquantity', $tst_ratingquantity,
        'tst_ratingquantity');
    $fields[] =
      new field(FALSE, text('txt_subject_template'), 'text', 'tst_resmailuser_subject', $tst_resmailuser_subject, '');
    $fields[] =
      new field(FALSE, text('txt_send_mail_from_address'), 'email', 'tst_resmailuser_from', $tst_resmailuser_from, '');
    $fields[] =
      new field(FALSE, text('txt_body_template'), 'textarea', 'tst_resmailuser_template', $tst_resmailuser_template,
        '');

    //--------------------------------------------

    $fields[] = new field(FALSE, text('txt_notify_settings'), "header");

    $fields[] =
      new field(FALSE, text('txt_subject_template'), "text", "admset_resmail_subject", $admset_resmail_subject, "");
    $fields[] =
      new field(FALSE, text('txt_send_mail_to_address'), "email", "admset_resmail_to", $admset_resmail_to, "");
    $fields[] =
      new field(FALSE, text('txt_send_mail_from_address'), "email", "admset_resmail_from", $admset_resmail_from, "");
    $fields[] =
      new field(FALSE, text('txt_body_template'), "textarea", "admset_resmail_template", $admset_resmail_template, "");
    $fields[] =
      new field(FALSE, text('txt_notify_by_e_mail_when_user_complete_a_test'), "checkbox", "admset_resmail_send",
        $admset_resmail_send, "txt_notify_by_e_mail_when_user_complete_a_test");

    $WEB_APP['fields'] = $fields;
    $WEB_APP['show_empty_value'] = FALSE;
    $WEB_APP['submit_title'] = text('txt_change');
    $WEB_APP['form_title'] = text('txt_testing');
    $WEB_APP['unshow_asterisk'] = TRUE;
    $WEB_APP['view']->display('form_page.tpl', text('txt_testing_settings'));
  }
}

