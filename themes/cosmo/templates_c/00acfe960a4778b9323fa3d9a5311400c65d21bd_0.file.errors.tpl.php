<?php
/* Smarty version 3.1.39, created on 2022-04-02 19:49:19
  from 'C:\OpenServer\domains\localhost\themes\cosmo\templates\errors.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_62487e8f9e82b9_64551785',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '00acfe960a4778b9323fa3d9a5311400c65d21bd' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\cosmo\\templates\\errors.tpl',
      1 => 1636779968,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62487e8f9e82b9_64551785 (Smarty_Internal_Template $_smarty_tpl) {
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
