<?php
/* Smarty version 3.1.39, created on 2022-04-02 17:03:48
  from 'C:\OpenServer\domains\localhost\themes\twiboostrap\templates\errors.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_624857c4a108a1_46171769',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e5d07be4683a4fe67d2e68248bd65ac8b93efc27' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\twiboostrap\\templates\\errors.tpl',
      1 => 1636779968,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_624857c4a108a1_46171769 (Smarty_Internal_Template $_smarty_tpl) {
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
