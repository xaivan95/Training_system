<?php
/* Smarty version 3.1.39, created on 2022-04-03 20:35:46
  from 'C:\OpenServer\domains\localhost\themes\cosmo\templates\modules_view.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6249daf2d38149_25248744',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '52c100c2a480b911c8c0fab817137d778c124043' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\cosmo\\templates\\modules_view.tpl',
      1 => 1648996852,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6249daf2d38149_25248744 (Smarty_Internal_Template $_smarty_tpl) {
?><nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand hidden-md" href="index.php">
                <img src="<?php echo (defined('CFG_IMG_DIR') ? constant('CFG_IMG_DIR') : null);?>
logo.png" title="КАОС 54 кафедра" alt="Logo" width="32"
                     height="32" style="margin-top: -4px;">
            </a>
            <a class="navbar-brand hidden-md" href="index.php">54 кафедра</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['categories'], 'category', false, 'category_name');
$_smarty_tpl->tpl_vars['category']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['category_name']->value => $_smarty_tpl->tpl_vars['category']->value) {
$_smarty_tpl->tpl_vars['category']->do_else = false;
?>
                    <?php if (count($_smarty_tpl->tpl_vars['category']->value) > 0) {?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false"><?php echo $_smarty_tpl->tpl_vars['text']->value[$_smarty_tpl->tpl_vars['category_name']->value];?>
<span
                                        class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['category']->value, 'module', false, NULL, 'modules_loop', array (
));
$_smarty_tpl->tpl_vars['module']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['module']->value) {
$_smarty_tpl->tpl_vars['module']->do_else = false;
?>
                                    <li><a href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
?module=<?php echo $_smarty_tpl->tpl_vars['module']->value['module'];?>
">                                  
                                            <?php if (basename($_smarty_tpl->tpl_vars['WEB_APP']->value['module']) == $_smarty_tpl->tpl_vars['module']->value['module']) {?><strong><?php }?> 
                                                <?php if ((isset($_smarty_tpl->tpl_vars['text']->value[$_smarty_tpl->tpl_vars['module']->value['module_name']])) == TRUE) {
echo $_smarty_tpl->tpl_vars['text']->value[$_smarty_tpl->tpl_vars['module']->value['module_name']];
} else {
echo $_smarty_tpl->tpl_vars['module']->value['module_name'];
}?>
                                                <?php if (basename($_smarty_tpl->tpl_vars['WEB_APP']->value['module']) == $_smarty_tpl->tpl_vars['module']->value['module']) {?></strong><?php }?>
                                        </a></li>
                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                            </ul>
                        </li>
                    <?php }?>
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </ul>
        </div>
    </div>
        <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['user_info_title'] !== '') {?>
        <div class="row navbar-right navbar-text">
            <div class="col-xs-12">
                <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['book']))) {?>
                    <a href="?module=favorite_books&action=add" class="navbar-link">
                        <span class="glyphicon glyphicon-asterisk" title="<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_add_to_favorites'];?>
"></span>
                    </a>
                    &nbsp;
                <?php }?>
                <span class="glyphicon glyphicon-user"></span>&nbsp;
                <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['user_info_login'] == 'anonymous') {?><a href="?module=login" class="navbar-link"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_to_login'];?>
</a>
                <?php } else {
echo $_smarty_tpl->tpl_vars['WEB_APP']->value['user_info'];?>
&nbsp;
                <?php }?>
            </div>
        </div>
    <?php } else { ?>
        <div class="row text-right">
            <div class="col-xs-12">
                <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['book']))) {?>
                    <a href="?module=favorite_books&action=add">
                        <span class="glyphicon glyphicon-asterisk" title="<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_add_to_favorites'];?>
"></span>
                    </a>
                    &nbsp;
                <?php }?>
            </div>
        </div>
    <?php }?>
    </nav><?php }
}
