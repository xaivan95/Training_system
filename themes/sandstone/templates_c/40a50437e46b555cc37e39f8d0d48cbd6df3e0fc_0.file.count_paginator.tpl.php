<?php
/* Smarty version 3.1.39, created on 2022-04-02 20:44:44
  from 'C:\OpenServer\domains\localhost\themes\sandstone\templates\count_paginator.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_62488b8c7945e7_83260813',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '40a50437e46b555cc37e39f8d0d48cbd6df3e0fc' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\sandstone\\templates\\count_paginator.tpl',
      1 => 1636779968,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62488b8c7945e7_83260813 (Smarty_Internal_Template $_smarty_tpl) {
?><ul class="pagination">
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['count_array'], 'count_value');
$_smarty_tpl->tpl_vars['count_value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['count_value']->value) {
$_smarty_tpl->tpl_vars['count_value']->do_else = false;
?>
	<?php if ($_smarty_tpl->tpl_vars['count_value']->value == $_smarty_tpl->tpl_vars['WEB_APP']->value['count']) {?>
		<li  class="active"><a href="#"><?php if ($_smarty_tpl->tpl_vars['count_value']->value == 0) {
echo $_smarty_tpl->tpl_vars['text']->value['txt_all'];
} else {
echo $_smarty_tpl->tpl_vars['count_value']->value;?>
</a></li><?php }?>
	<?php } else { ?>
        <li><a href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['paginator']->get_url_page(1);?>
&amp;count=<?php echo $_smarty_tpl->tpl_vars['count_value']->value;?>
"
		   title="<?php if ($_smarty_tpl->tpl_vars['count_value']->value == 0) {
echo $_smarty_tpl->tpl_vars['text']->value['txt_all_items_on_the_page'];
} else {
echo $_smarty_tpl->tpl_vars['count_value']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['text']->value['txt_items_on_the_page'];
}?>"><?php if ($_smarty_tpl->tpl_vars['count_value']->value == 0) {
echo $_smarty_tpl->tpl_vars['text']->value['txt_all'];
} else {
echo $_smarty_tpl->tpl_vars['count_value']->value;
}?></a></li>
	<?php }
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	</ul><?php }
}
