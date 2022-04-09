<?php
/* Smarty version 3.1.39, created on 2022-04-02 17:11:35
  from 'C:\OpenServer\domains\localhost\themes\sandstone\templates\errors.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6248599791c593_66721575',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4a1914500109c1df9afdbeacbb5bdfe8032014a6' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\sandstone\\templates\\errors.tpl',
      1 => 1636779968,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6248599791c593_66721575 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['WEB_APP']->value['errorstext'] != '') {?>
<div class="alert alert-danger"><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['errorstext'];?>
</div>
<?php }?>

<?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['infotext']))) {?>
<div class="alert alert-info"><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['infotext'];?>
</div>
<?php }
}
}
