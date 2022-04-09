<?php
/* Smarty version 3.1.39, created on 2022-04-02 17:05:48
  from 'C:\OpenServer\domains\localhost\themes\twiboostrap\templates\table_rows.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6248583ced0086_34100957',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '00836f6e1ae3ef479a7d2088fd488ec1e789562c' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\twiboostrap\\templates\\table_rows.tpl',
      1 => 1641885903,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6248583ced0086_34100957 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\OpenServer\\domains\\localhost\\system\\lib\\smarty\\plugins\\function.cycle.php','function'=>'smarty_function_cycle',),1=>array('file'=>'C:\\OpenServer\\domains\\localhost\\system\\lib\\smarty\\plugins\\modifier.replace.php','function'=>'smarty_modifier_replace',),));
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['items']->value, 'row', false, NULL, 'foreach_row', array (
  'iteration' => true,
));
$_smarty_tpl->tpl_vars['row']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_foreach_row']->value['iteration']++;
?>
	<?php echo smarty_function_cycle(array('values'=>"line1,line2",'assign'=>"line"),$_smarty_tpl);?>

	<tr>

	<?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['editform'] == TRUE && $_smarty_tpl->tpl_vars['WEB_APP']->value['list_actions_access']) {?>
		<?php if (!(isset($_smarty_tpl->tpl_vars['WEB_APP']->value['hide_delete']))) {?><td><input type="checkbox" name="selected_row[<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_foreach_row']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_foreach_row']->value['iteration'] : null);?>
]" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
" id="checkbox_row_<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"  /></td><?php }?>
	<?php }?>
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['columns']->value, 'item');
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
?>
	<?php $_smarty_tpl->_assignInScope('field', $_smarty_tpl->tpl_vars['item']->value->title);?>
        <?php if (((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['average']))) && ($_smarty_tpl->tpl_vars['WEB_APP']->value['average'] == TRUE) && ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_foreach_row']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_foreach_row']->value['iteration'] : null) == $_smarty_tpl->tpl_vars['WEB_APP']->value['rows_count'])) {?>
            <th><?php echo $_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['field']->value];?>
</th>
		<?php } elseif ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['test_max_score'])) && ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_foreach_row']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_foreach_row']->value['iteration'] : null) == 1)) {?>
			<th><?php echo $_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['field']->value];?>
</th>
		<?php } else { ?>
		<?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['escape']))) {?>
			<td><?php echo smarty_modifier_replace(htmlspecialchars($_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['field']->value], ENT_QUOTES, 'UTF-8', true),"\r\n","<br>");?>
</td>
		<?php } else { ?>
			<td><?php echo $_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['field']->value];?>
</td>
		<?php }?>
        <?php }?>
	<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	<?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['editform'] == TRUE && (isset($_smarty_tpl->tpl_vars['WEB_APP']->value['row_actions'])) && $_smarty_tpl->tpl_vars['WEB_APP']->value['row_actions_access'] && (!(isset($_smarty_tpl->tpl_vars['WEB_APP']->value['average'])) || !(($_smarty_tpl->tpl_vars['WEB_APP']->value['average'] == TRUE) && ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_foreach_row']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_foreach_row']->value['iteration'] : null) == $_smarty_tpl->tpl_vars['WEB_APP']->value['rows_count'])))) {?>
		<td>
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['row_actions'], 'action');
$_smarty_tpl->tpl_vars['action']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['action']->value) {
$_smarty_tpl->tpl_vars['action']->do_else = false;
?>
					<?php if (in_array($_smarty_tpl->tpl_vars['action']->value->name,$_smarty_tpl->tpl_vars['WEB_APP']->value['actions'])) {?>
                        <a href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['script_name'];?>
&amp;action=<?php echo $_smarty_tpl->tpl_vars['action']->value->name;?>
&amp;id=<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"><span class="glyphicon glyphicon-<?php echo $_smarty_tpl->tpl_vars['action']->value->glyphicon;?>
" title="<?php echo $_smarty_tpl->tpl_vars['action']->value->title;?>
"></span></a>&nbsp;&nbsp;
                    <?php }?>
				<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		</td>
 	<?php }?>
	</tr>
<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}
