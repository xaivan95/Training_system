<html lang="{$WEB_APP.language_code}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="notranslate">
    <meta name="google" value="notranslate">

    {** Favicons **}
    <meta name="theme-color" content="#ffffff">
    <link rel="icon" href="{$WEB_APP.cfg_url}favicons/favicon.svg">
    <link rel="apple-touch-icon" href="{$WEB_APP.cfg_url}favicons/apple-touch-icon.png">
    <link rel="manifest" href="{$WEB_APP.cfg_url}favicons/manifest.json">
    <link rel="shortcut icon" href="{$WEB_APP.cfg_url}favicon.ico">
    <meta name="msapplication-TileColor" content="#38B1FF">
    <meta name="msapplication-TileImage" content="{$WEB_APP.cfg_url}/tileicon.png">
    {***********}

    <meta name="description" content="КАОС 54 кафедра">
    <meta name="author" content="КАОС 54 кафедра">

    {** Title **}
    {if isset($WEB_APP.book)}
        <title>{$WEB_APP.book->title|escape} :: {$WEB_APP.chapter->title|escape}</title>
    {elseif $title neq ""}
        <title>{$title} - {$WEB_APP.user_info_title|escape}КАОС 54 кафедра</title>
    {else}
        <title>{$WEB_APP.user_info_title|escape}КАОС 54 кафедра</title>
    {/if}

    {****************************************
    *************    JS files    ************
    ****************************************}
    <script src="{$WEB_APP.cfg_url}js/jquery.min.js"></script>
    <script src="{$WEB_APP.cfg_url}js/bootstrap.min.js"></script>
    <script src="{$WEB_APP.cfg_url}js/jquery-ui.min.js"></script>
    <script src="{$WEB_APP.cfg_url}js/jquery.ui.touch-punch.min.js"></script>

    {** Bootstrap **}
    <link href="{$WEB_APP.css_dir}bootstrap.min.css" rel="stylesheet">
    <link href="{$WEB_APP.css_dir}navbar-fixed-top.css" rel="stylesheet">
    <link href="{$WEB_APP.css_dir}sticky-footer.css" rel="stylesheet">
    <link href="{$WEB_APP.css_dir}bootstrap-datetimepicker.min.css" rel="stylesheet">

    {****************************************
    *************   CSS files    ************
    ****************************************}
    {if isset($WEB_APP.test_css) && $WEB_APP.test_css neq ''}
        <style>{$WEB_APP.test_css}</style>
    {/if}

    {if isset($WEB_APP.question)}
        {if isset($WEB_APP.question->css)}
            <style>{$WEB_APP.question->css}</style>
        {/if}
    {/if}

    {if isset($WEB_APP.chapter->css) && $WEB_APP.chapter->css neq ''}
        <style>{$WEB_APP.chapter->css}</style>
    {/if}

    {if isset($WEB_APP.book)}
        {** Books created with version 4 and higher **}
        {if $WEB_APP.book->theme eq ""}
            <link href="{$WEB_APP.cfg_url}js/jstree/style.min.css" rel="stylesheet">
            <style>
                #jstree {
                    max-width: 280px;
                }

                #jstree a {
                    white-space: normal !important;
                    height: auto;
                    padding: 1px 2px;
                }

                {$WEB_APP.book->css}
            </style>
            {** Books created before version 4 **}
        {else}
            <link rel=stylesheet href="{$WEB_APP.cfg_url}book_themes/{$WEB_APP.book->theme}/theme.css" type=text/css>
            <script src="{$WEB_APP.cfg_url}js/jscooktree.js"></script>
            <script src="{$WEB_APP.cfg_url}book_themes/{$WEB_APP.book->theme}/theme.js"></script>
        {/if}
    {/if}
    {***********}


    {** Additional book or test headers **}
    {if isset($WEB_APP.html_header)}
        {$WEB_APP.html_header}
    {/if}
    {***********}


    <script src="{$WEB_APP.cfg_url}js/uppod.js"></script>
</head>

<body>

{***********************************
********  Navigation bar    ********
***********************************}
{if $main_module neq 'login.tpl'}
    {include file="modules_view.tpl"}
{/if}

{***********************************
*********  Main content*    ********
***********************************}
<div class="container-fluid">
    {if isset($smarty.session.new_messages_count) AND ($smarty.session.new_messages_count gt 0)}
        <div class="alert alert-info" role="alert">
            {$text.txt_you_have_new_message}&nbsp;{$smarty.session.new_messages_count}
            <a class="btn btn-danger" href="?module=messages_inbox" role="button">&nbsp;&nbsp;{$text.txt_view}&nbsp;&nbsp;</a>
        </div>
    {/if}
    {include file="$main_module"}
</div>

{***********************************
********      Footer        ********
***********************************}

<footer class="footer">
    <div class="container">
        <p class="text-muted text-center">{$text.txt_copyright}</p>
    </div>
</footer>

{****************************************
*************  JS files  ***************
****************************************}
<script src="{$WEB_APP.cfg_url}js/main.js"></script>

{****************************************
********  Additional (JS) files  ********
****************************************}
{if isset($WEB_APP.scripts)}
    {foreach from = $WEB_APP.scripts item = script}
        <script src="{$WEB_APP.cfg_url}js/{$script}"></script>
    {/foreach}
{/if}

{** Additional book or test footer **}
{if isset($WEB_APP.html_footer)}
    {$WEB_APP.html_footer}
{/if}

{****************************************
****  Fill fields in users answers  *****
****************************************}
{if isset($WEB_APP.results_fields) eq TRUE}
    <script>
        let afields = {$WEB_APP.results_fields};
        fill_fields(afields);
    </script>
{/if}

{if isset($WEB_APP.results_fields_message) eq TRUE}
    <script>
        let message_fields = {$WEB_APP.results_fields_message};
        {if $WEB_APP.hightlight_fields eq TRUE}
        fill_fields_with_marks_message(message_fields);
        {else}
        fill_fields_message(message_fields);
        {/if}
    </script>
{/if}

{****************************************
  *****  For ordered and matched lists ****
  ****************************************}
{if isset($WEB_APP.question)}
    <link href="{$WEB_APP.css_dir}jquery-ui.min.css" rel="stylesheet">
    <link href="{$WEB_APP.css_dir}jquery-ui-sunrav.css" rel="stylesheet">
    <link href="{$WEB_APP.css_dir}jquery-ui.theme.min.css" rel="stylesheet">
    <script>
        $(document).tooltip();
        ShowTimer("{$text.txt_time_left}", "{$text.txt_unrestrictedly}");
    </script>
    {*    <script src="{$WEB_APP.cfg_url}js/select2/select2.min.js"></script>*}
    {*    <link  href="{$WEB_APP.cfg_url}js/select2/select2.min.css" rel="stylesheet">*}
    {*    <script>$(document).ready(function() {*}
    {*            $('.js-example-basic-single').select2();*}
    {*        });</script>*}
{/if}
{if isset($WEB_APP.book)}
    <link href="{$WEB_APP.css_dir}jquery-ui.min.css" rel="stylesheet">
    <link href="{$WEB_APP.css_dir}jquery-ui-sunrav.css" rel="stylesheet">
    <link href="{$WEB_APP.css_dir}jquery-ui.theme.min.css" rel="stylesheet">
    <script>
        $(document).tooltip();
    </script>
{/if}
</body>
</html>