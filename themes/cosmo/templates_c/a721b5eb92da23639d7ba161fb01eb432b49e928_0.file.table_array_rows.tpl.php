<?php
/* Smarty version 3.1.39, created on 2022-04-03 17:07:41
  from 'C:\OpenServer\domains\localhost\themes\cosmo\templates\table_array_rows.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6249aa2d3bcbd5_56699787',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a721b5eb92da23639d7ba161fb01eb432b49e928' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\cosmo\\templates\\table_array_rows.tpl',
      1 => 1641885903,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:errors.tpl' => 1,
    'file:form.tpl' => 1,
    'file:table_header.tpl' => 1,
    'file:table_rows.tpl' => 1,
    'file:table_footer.tpl' => 1,
  ),
),false)) {
function content_6249aa2d3bcbd5_56699787 (Smarty_Internal_Template $_smarty_tpl) {
?><h1 class="text-info h2"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h1>
<?php $_smarty_tpl->_subTemplateRender("file:errors.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['show_form']) {?> 
	<?php $_smarty_tpl->_subTemplateRender("file:form.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('submit_title'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['submit_title'],'form_title'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['title_add']), 0, false);
?>	
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['show_table']) {?>
	<?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['show_form']) {?>
		<br>
	<?php }
if (!$_smarty_tpl->tpl_vars['WEB_APP']->value['show_table_only']) {?>
	<p><a class="btn btn-default" href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['print_url'];?>
"><span class="glyphicon glyphicon-print"></span>&nbsp;<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_print_report'];?>
</a></p>
<?php }?>
	<?php $_smarty_tpl->_subTemplateRender("file:table_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('columns'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['columns']), 0, false);
?>
	<?php $_smarty_tpl->_subTemplateRender("file:table_rows.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('index'=>'id','items'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['rows'],'columns'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['columns']), 0, false);
?>
	<?php $_smarty_tpl->_subTemplateRender("file:table_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('columns_count'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['columns_count']), 0, false);
}
}
}
