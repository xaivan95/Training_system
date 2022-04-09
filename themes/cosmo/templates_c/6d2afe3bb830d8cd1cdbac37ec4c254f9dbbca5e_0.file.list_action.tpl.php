<?php
/* Smarty version 3.1.39, created on 2022-04-03 17:18:52
  from 'C:\OpenServer\domains\localhost\themes\cosmo\templates\list_action.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6249accc8ce602_82363808',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6d2afe3bb830d8cd1cdbac37ec4c254f9dbbca5e' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\cosmo\\templates\\list_action.tpl',
      1 => 1636779968,
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
function content_6249accc8ce602_82363808 (Smarty_Internal_Template $_smarty_tpl) {
?><h1 class="text-info h2"><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['title'];?>
</h1>
<?php $_smarty_tpl->_subTemplateRender("file:errors.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php $_smarty_tpl->_subTemplateRender("file:form.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('submit_title'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['submit_title'],'form_title'=>$_smarty_tpl->tpl_vars['title']->value,'show_buttons'=>0), 0, false);
?>
<br>
<?php $_smarty_tpl->_subTemplateRender("file:table_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('columns'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['columns']), 0, false);
$_smarty_tpl->_subTemplateRender("file:table_rows.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('index'=>'ID','items'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['items'],'columns'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['columns']), 0, false);
$_smarty_tpl->_subTemplateRender("file:table_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('columns_count'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['columns_count']), 0, false);
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['items'], 'row', false, NULL, 'foreach_row', array (
  'iteration' => true,
));
$_smarty_tpl->tpl_vars['row']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_foreach_row']->value['iteration']++;
?>
    <input type="hidden" name="selected_row[<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_foreach_row']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_foreach_row']->value['iteration'] : null);?>
]" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"
           id="checkbox_row_<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
"/>
<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
<input type="hidden" name="list_action" value="confirm_<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['list_action'];?>
">
<div class="text-right">
    <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['list_action_extra_checkbox'] == TRUE) {?>
    <input type="checkbox" name="extra_checkbox" value="checked" id="extra_checkbox"/>
    <label for="extra_checkbox"><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['list_action_extra_checkbox_title'];?>
</label>&nbsp;&nbsp;&nbsp;&nbsp;
    <?php }?>
    <input type="submit" <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['list_action'] == "delete") {?>class="btn btn btn-danger"
           <?php } else { ?>class="btn btn-primary"<?php }?>" value="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['submit_title'];?>
"/>&nbsp;
    <input type="button" class="btn btn-info" value="<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_help'];?>
"
           onclick="window.open('<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['help_url'];?>
', 'help_window')">
</div>
</form>
<?php }
}
