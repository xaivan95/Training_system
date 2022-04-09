<?php
/* Smarty version 3.1.39, created on 2022-04-02 20:44:44
  from 'C:\OpenServer\domains\localhost\themes\sandstone\templates\table_footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_62488b8c54c7f8_07349766',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '102164892e6544cba7a8b5b68dbea6ac6e698a69' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\sandstone\\templates\\table_footer.tpl',
      1 => 1636779968,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:paginator.tpl' => 1,
    'file:count_paginator.tpl' => 1,
  ),
),false)) {
function content_62488b8c54c7f8_07349766 (Smarty_Internal_Template $_smarty_tpl) {
?>
</table>
</div>
</div>

<?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['editform'] == TRUE) {?>
    <?php if (!(isset($_smarty_tpl->tpl_vars['WEB_APP']->value['hide_delete'])) && $_smarty_tpl->tpl_vars['WEB_APP']->value['list_actions_access']) {?>
    <div class="row-fluid">
        <form class="form-inline">
            <div class="form-group">
                <label>
                    <input type="checkbox"  name="all_items" value="all" id='all_items' onclick="MarkAllRows('fieldsForm')">&nbsp<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_check_all'];?>
&nbsp;
                </label>
            </div>
            <div class="form-group">
                <label class="sr-only" for="list_action">List</label>
                <select class="form-control input-sm" id="list_action" name="list_action">
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['list_actions'], 'action');
$_smarty_tpl->tpl_vars['action']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['action']->value) {
$_smarty_tpl->tpl_vars['action']->do_else = false;
if (in_array($_smarty_tpl->tpl_vars['action']->value->name,$_smarty_tpl->tpl_vars['WEB_APP']->value['actions'])) {?>
                            <option value="<?php echo $_smarty_tpl->tpl_vars['action']->value->name;?>
"><?php echo $_smarty_tpl->tpl_vars['action']->value->title;?>
</option><?php }?>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                 </select>&nbsp;
            </div>
            <div class="form-group">
                 <input type="submit" class="btn btn-default btn-sm" value="OK"/>
            </div>
        </form>
    </div>
        </form>
    <?php }?>
    <div class="row">
        <div class="col-md-6 col-sm-7"><?php $_smarty_tpl->_subTemplateRender("file:paginator.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('paginator'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['paginator'],'url'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['script_name']), 0, false);
?></div>
        <div class="col-md-6 col-sm-5 text-right"><?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['settings']['show_items_per_page']) {
$_smarty_tpl->_subTemplateRender("file:count_paginator.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?></div>
    </div>
<?php }?>

<?php }
}
