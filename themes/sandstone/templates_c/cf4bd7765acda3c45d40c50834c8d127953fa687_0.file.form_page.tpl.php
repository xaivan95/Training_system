<?php
/* Smarty version 3.1.39, created on 2022-04-02 17:11:45
  from 'C:\OpenServer\domains\localhost\themes\sandstone\templates\form_page.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_624859a12785a3_17749004',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'cf4bd7765acda3c45d40c50834c8d127953fa687' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\sandstone\\templates\\form_page.tpl',
      1 => 1636779968,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:errors.tpl' => 1,
    'file:form.tpl' => 1,
  ),
),false)) {
function content_624859a12785a3_17749004 (Smarty_Internal_Template $_smarty_tpl) {
?><h1 class="text-info h2"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h1>
<?php $_smarty_tpl->_subTemplateRender("file:errors.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php $_smarty_tpl->_subTemplateRender("file:form.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('submit_title'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['submit_title'],'form_title'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['form_title']), 0, false);
}
}
