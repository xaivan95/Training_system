<?php
/* Smarty version 3.1.39, created on 2022-04-03 21:04:17
  from 'C:\OpenServer\domains\localhost\themes\cosmo\templates\main.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6249e1a1b74346_89768322',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7763633d806447e5b0e146646b213f16db84465f' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\cosmo\\templates\\main.tpl',
      1 => 1649008949,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:modules_view.tpl' => 1,
  ),
),false)) {
function content_6249e1a1b74346_89768322 (Smarty_Internal_Template $_smarty_tpl) {
?><html lang="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['language_code'];?>
">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="notranslate">
    <meta name="google" value="notranslate">

        <meta name="theme-color" content="#ffffff">
    <link rel="icon" href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
favicons/favicon.svg">
    <link rel="apple-touch-icon" href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
favicons/apple-touch-icon.png">
    <link rel="manifest" href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
favicons/manifest.json">
    <link rel="shortcut icon" href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
favicon.ico">
    <meta name="msapplication-TileColor" content="#38B1FF">
    <meta name="msapplication-TileImage" content="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
/tileicon.png">
    
    <meta name="description" content="КАОС 54 кафедра">
    <meta name="author" content="КАОС 54 кафедра">

        <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['book']))) {?>
        <title><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['WEB_APP']->value['book']->title, ENT_QUOTES, 'UTF-8', true);?>
 :: <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['WEB_APP']->value['chapter']->title, ENT_QUOTES, 'UTF-8', true);?>
</title>
    <?php } elseif ($_smarty_tpl->tpl_vars['title']->value != '') {?>
        <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
 - <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['WEB_APP']->value['user_info_title'], ENT_QUOTES, 'UTF-8', true);?>
КАОС 54 кафедра</title>
    <?php } else { ?>
        <title><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['WEB_APP']->value['user_info_title'], ENT_QUOTES, 'UTF-8', true);?>
КАОС 54 кафедра</title>
    <?php }?>

        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
js/jquery.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
js/bootstrap.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
js/jquery-ui.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
js/jquery.ui.touch-punch.min.js"><?php echo '</script'; ?>
>
 <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
system/AOS/1/TemplateData/UnityProgress.js"><?php echo '</script'; ?>
>
    
    
        <link href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['css_dir'];?>
bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['css_dir'];?>
navbar-fixed-top.css" rel="stylesheet">
    <link href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['css_dir'];?>
sticky-footer.css" rel="stylesheet">
    <link href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['css_dir'];?>
bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
system/AOS/1/TemplateData/style.css">

        <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['test_css'])) && $_smarty_tpl->tpl_vars['WEB_APP']->value['test_css'] != '') {?>
        <style><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['test_css'];?>
</style>
    <?php }?>

    <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['question']))) {?>
        <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['question']->css))) {?>
            <style><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['question']->css;?>
</style>
        <?php }?>
    <?php }?>

    <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['chapter']->css)) && $_smarty_tpl->tpl_vars['WEB_APP']->value['chapter']->css != '') {?>
        <style><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['chapter']->css;?>
</style>
    <?php }?>

    <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['book']))) {?>
                <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['book']->theme == '') {?>
            <link href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
js/jstree/style.min.css" rel="stylesheet">
            <style>
                #jstree {
                    max-width: 280px;
                }

                #jstree a {
                    white-space: normal !important;
                    height: auto;
                    padding: 1px 2px;
                }

                <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['book']->css;?>

            </style>
                    <?php } else { ?>
            <link rel=stylesheet href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
book_themes/<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['book']->theme;?>
/theme.css" type=text/css>
            <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
js/jscooktree.js"><?php echo '</script'; ?>
>
            <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
book_themes/<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['book']->theme;?>
/theme.js"><?php echo '</script'; ?>
>
        <?php }?>
    <?php }?>
    

        <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['html_header']))) {?>
        <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['html_header'];?>

    <?php }?>
    

    <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
js/uppod.js"><?php echo '</script'; ?>
>
</head>

<body>

<?php if ($_smarty_tpl->tpl_vars['main_module']->value != 'login.tpl') {?>
    <?php $_smarty_tpl->_subTemplateRender("file:modules_view.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?>

<div class="container-fluid">
    <?php if ((isset($_SESSION['new_messages_count'])) && ($_SESSION['new_messages_count'] > 0)) {?>
        <div class="alert alert-info" role="alert">
            <?php echo $_smarty_tpl->tpl_vars['text']->value['txt_you_have_new_message'];?>
&nbsp;<?php echo $_SESSION['new_messages_count'];?>

            <a class="btn btn-danger" href="?module=messages_inbox" role="button">&nbsp;&nbsp;<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_view'];?>
&nbsp;&nbsp;</a>
        </div>
    <?php }?>
    <?php $_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['main_module']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
</div>


<footer class="footer">
    <div class="container">
        <p class="text-muted text-center"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_copyright'];?>
</p>
    </div>
</footer>

<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
js/main.js"><?php echo '</script'; ?>
>

<?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['scripts']))) {?>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['scripts'], 'script');
$_smarty_tpl->tpl_vars['script']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['script']->value) {
$_smarty_tpl->tpl_vars['script']->do_else = false;
?>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
js/<?php echo $_smarty_tpl->tpl_vars['script']->value;?>
"><?php echo '</script'; ?>
>
    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}?>

<?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['html_footer']))) {?>
    <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['html_footer'];?>

<?php }?>

<?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['results_fields'])) == TRUE) {?>
    <?php echo '<script'; ?>
>
        let afields = <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['results_fields'];?>
;
        fill_fields(afields);
    <?php echo '</script'; ?>
>
<?php }?>

<?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['results_fields_message'])) == TRUE) {?>
    <?php echo '<script'; ?>
>
        let message_fields = <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['results_fields_message'];?>
;
        <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['hightlight_fields'] == TRUE) {?>
        fill_fields_with_marks_message(message_fields);
        <?php } else { ?>
        fill_fields_message(message_fields);
        <?php }?>
    <?php echo '</script'; ?>
>
<?php }?>

<?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['question']))) {?>
    <link href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['css_dir'];?>
jquery-ui.min.css" rel="stylesheet">
    <link href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['css_dir'];?>
jquery-ui-sunrav.css" rel="stylesheet">
    <link href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['css_dir'];?>
jquery-ui.theme.min.css" rel="stylesheet">
    <?php echo '<script'; ?>
>
        $(document).tooltip();
    <?php echo '</script'; ?>
>
                    <?php }
if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['book']))) {?>
    <link href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['css_dir'];?>
jquery-ui.min.css" rel="stylesheet">
    <link href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['css_dir'];?>
jquery-ui-sunrav.css" rel="stylesheet">
    <link href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['css_dir'];?>
jquery-ui.theme.min.css" rel="stylesheet">
    <?php echo '<script'; ?>
>
        $(document).tooltip();
    <?php echo '</script'; ?>
>
<?php }?>
</body>
</html><?php }
}
