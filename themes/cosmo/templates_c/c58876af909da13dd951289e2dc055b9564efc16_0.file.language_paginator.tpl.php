<?php
/* Smarty version 3.1.39, created on 2022-04-02 22:36:08
  from 'C:\OpenServer\domains\localhost\themes\cosmo\templates\language_paginator.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6248a5a8d3a6b7_38728315',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c58876af909da13dd951289e2dc055b9564efc16' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\cosmo\\templates\\language_paginator.tpl',
      1 => 1636779968,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6248a5a8d3a6b7_38728315 (Smarty_Internal_Template $_smarty_tpl) {
?><ul class="pagination">
<?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['language'] == '') {?>
	<li class="active"><a href="#"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_all'];?>
</a></li>
<?php } else { ?>
	<li><a href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['script_name'];?>
&amp;language=all" title="<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_all_translations'];?>
"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_all'];?>
</a></li>
<?php }
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['languages'], 'item');
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
?>
	<?php if ($_smarty_tpl->tpl_vars['item']->value['short_name'] == $_smarty_tpl->tpl_vars['WEB_APP']->value['language']) {?>
    <li class="active"><a href="#"><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</a></li>
	<?php } else { ?>
		<li><a href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['script_name'];?>
&amp;language=<?php echo $_smarty_tpl->tpl_vars['item']->value['short_name'];?>
"
		   title="<?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
 <?php echo $_smarty_tpl->tpl_vars['text']->value['txt_lower_translations'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</a></li>
	<?php }
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</ul>
<?php }
}
