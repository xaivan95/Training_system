<?php
/* Smarty version 3.1.39, created on 2022-04-02 17:03:48
  from 'C:\OpenServer\domains\localhost\themes\twiboostrap\templates\form_page.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_624857c49ac298_78499493',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7d90e1969f5b89e0afb71c36cb62d7805f135126' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\twiboostrap\\templates\\form_page.tpl',
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
function content_624857c49ac298_78499493 (Smarty_Internal_Template $_smarty_tpl) {
?><h1 class="text-info h2"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h1>
<?php $_smarty_tpl->_subTemplateRender("file:errors.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php $_smarty_tpl->_subTemplateRender("file:form.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('submit_title'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['submit_title'],'form_title'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['form_title']), 0, false);
}
}
