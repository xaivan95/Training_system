<?php
/* Smarty version 3.1.39, created on 2022-04-03 17:07:32
  from 'C:\OpenServer\domains\localhost\themes\cosmo\templates\import.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6249aa24191e17_08673708',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b9691c586b7ab7413602bd931a172f3fbf006135' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\cosmo\\templates\\import.tpl',
      1 => 1636779968,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:form_page.tpl' => 1,
  ),
),false)) {
function content_6249aa24191e17_08673708 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:form_page.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('form_title'=>$_smarty_tpl->tpl_vars['text']->value['txt_import']), 0, false);
?>


<?php if (count($_smarty_tpl->tpl_vars['WEB_APP']->value['log']) != 0) {?>
	<h1 class="text-info h2"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_log'];?>
</h1>
	<table>
	<?php $_smarty_tpl->_assignInScope('tmp', "1");?>		
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['log'], 'item', false, NULL, 'foreach_row', array (
));
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
?>
		<?php if ($_smarty_tpl->tpl_vars['tmp']->value == 2) {?>
				<?php $_smarty_tpl->_assignInScope('tmp', "1");?>
			<?php } else { ?>
				<?php $_smarty_tpl->_assignInScope('tmp', "2");?>
			<?php }?>
		<tr class = "line<?php echo $_smarty_tpl->tpl_vars['tmp']->value;?>
">
			<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value, ENT_QUOTES, 'UTF-8', true);?>
</td>
		</tr>
	<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>		
	</table>
	<br>		
<?php }
}
}
