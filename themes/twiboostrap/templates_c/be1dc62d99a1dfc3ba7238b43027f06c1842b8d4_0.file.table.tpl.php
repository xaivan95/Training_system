<?php
/* Smarty version 3.1.39, created on 2022-04-02 17:05:48
  from 'C:\OpenServer\domains\localhost\themes\twiboostrap\templates\table.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6248583ca7f4a3_98958609',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'be1dc62d99a1dfc3ba7238b43027f06c1842b8d4' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\twiboostrap\\templates\\table.tpl',
      1 => 1636779968,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:errors.tpl' => 1,
    'file:table_header.tpl' => 3,
    'file:table_rows.tpl' => 3,
    'file:table_footer.tpl' => 3,
    'file:language_paginator.tpl' => 1,
    'file:filter.tpl' => 1,
    'file:form.tpl' => 4,
  ),
),false)) {
function content_6248583ca7f4a3_98958609 (Smarty_Internal_Template $_smarty_tpl) {
?><h1 class="text-info h2"><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['title'];?>
</h1>
<?php $_smarty_tpl->_subTemplateRender("file:errors.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['action'] == "finish") {?>
    <?php $_smarty_tpl->_subTemplateRender("file:table_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('columns'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['columns']), 0, false);
?>
    <?php $_smarty_tpl->_subTemplateRender("file:table_rows.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('index'=>'ID','items'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['items'],'columns'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['columns']), 0, false);
?>
    <?php $_smarty_tpl->_subTemplateRender("file:table_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('columns_count'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['columns_count']), 0, false);
?>
    <form method="post" action="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['script_name'];?>
">
        <?php
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
        <input type="hidden" name="action" value="confirm_finish">
        <div class="text-right"><input type="submit" class="btn" value="<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_finish'];?>
"/>&nbsp;
            <input type="button" class="btn btn-info" value="<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_help'];?>
"
                   onclick="window.open('<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['help_url'];?>
', 'help_window')"></div>
    </form>
<?php } else { ?>

    <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['action'] == "delete") {?>
        <?php $_smarty_tpl->_subTemplateRender("file:table_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('columns'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['columns']), 0, true);
?>
        <?php $_smarty_tpl->_subTemplateRender("file:table_rows.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('index'=>'ID','items'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['items'],'columns'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['columns']), 0, true);
?>
        <?php $_smarty_tpl->_subTemplateRender("file:table_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('columns_count'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['columns_count']), 0, true);
?>
        <form method="post" action="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['script_name'];?>
">
            <?php
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
            <input type="hidden" name="action" value="confirm_delete">
            <div class="text-right">
                <input type="submit" class="btn btn-danger" value="<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_delete'];?>
"/>&nbsp;
                <input type="button" class="btn btn-info" value="<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_help'];?>
"
                       onclick="window.open('<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['help_url'];?>
', 'help_window')">
            </div>
        </form>
    <?php } else { ?>

        <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['action'] == "view") {?>
            <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['language_paginator']))) {?>
                <?php $_smarty_tpl->_subTemplateRender("file:language_paginator.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

            <?php }?>
            <br>
            <?php $_smarty_tpl->_subTemplateRender("file:filter.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
            <br>
            <?php $_smarty_tpl->_subTemplateRender("file:table_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('columns'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['columns']), 0, true);
?>
            <?php $_smarty_tpl->_subTemplateRender("file:table_rows.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('index'=>'ID','items'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['items'],'columns'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['columns']), 0, true);
?>
            <?php $_smarty_tpl->_subTemplateRender("file:table_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('columns_count'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['columns_count']), 0, true);
?>
            <?php if (in_array('add',$_smarty_tpl->tpl_vars['WEB_APP']->value['actions'])) {?>
                <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['show_insert'])) == TRUE) {?>
                    <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['show_insert'] == TRUE) {?>
                        <h2><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['title_add'];?>
</h2>
                        <?php $_smarty_tpl->_subTemplateRender("file:form.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('submit_title'=>$_smarty_tpl->tpl_vars['text']->value['txt_add'],'form_title'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['title_add']), 0, false);
?>
                    <?php }?>
                <?php } else { ?>
                    <h2><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['title_add'];?>
</h2>
                    <?php $_smarty_tpl->_subTemplateRender("file:form.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('submit_title'=>$_smarty_tpl->tpl_vars['text']->value['txt_add'],'form_title'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['title_add']), 0, true);
?>
                <?php }?>
            <?php }?>
        <?php } else { ?>

            <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['action'] == "edit") {?>
                <?php $_smarty_tpl->_subTemplateRender("file:form.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('submit_title'=>$_smarty_tpl->tpl_vars['text']->value['txt_change'],'form_title'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['title_edit']), 0, true);
?>
            <?php } else { ?>
                <?php $_smarty_tpl->_subTemplateRender("file:form.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('submit_title'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['submit_title'],'form_title'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['form_title']), 0, true);
?>
            <?php }?>
        <?php }?>
    <?php }?>

<?php }
}
}
