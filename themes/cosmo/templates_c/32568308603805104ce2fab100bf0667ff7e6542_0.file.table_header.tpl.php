<?php
/* Smarty version 3.1.39, created on 2022-04-02 19:53:58
  from 'C:\OpenServer\domains\localhost\themes\cosmo\templates\table_header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_62487fa6da7f20_76016042',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '32568308603805104ce2fab100bf0667ff7e6542' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\cosmo\\templates\\table_header.tpl',
      1 => 1636779968,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62487fa6da7f20_76016042 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['WEB_APP']->value['editform'] == TRUE) {
if (!(isset($_smarty_tpl->tpl_vars['hide_delete']->value))) {?>
<form method="post" class="form-inline" action="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['action_url'];?>
" name="fieldsForm" id="fieldsForm"
      role="form"><?php }?>
    <?php }?>
    <div class="row-fluid">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed">
                <thead>
                <tr>
                    <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['editform'] == TRUE && $_smarty_tpl->tpl_vars['WEB_APP']->value['list_actions_access']) {?>
                        <?php if (!(isset($_smarty_tpl->tpl_vars['WEB_APP']->value['hide_delete']))) {?>
                            <th>#</th>
                        <?php }?>
                    <?php }?>
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['columns']->value, 'column');
$_smarty_tpl->tpl_vars['column']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['column']->value) {
$_smarty_tpl->tpl_vars['column']->do_else = false;
?>
                        <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['editform'] == FALSE) {?>
                            <th><?php echo $_smarty_tpl->tpl_vars['column']->value->name;?>
</th>
                        <?php } else { ?>
                            <?php $_smarty_tpl->_assignInScope('sort_order', ((string)$_smarty_tpl->tpl_vars['WEB_APP']->value['default_order']));?>
                            <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['sort_order']))) {?>
                                <?php $_smarty_tpl->_assignInScope('sort_order', ((string)$_smarty_tpl->tpl_vars['WEB_APP']->value['sort_order']));?>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['sort_order']->value == 'DESC') {?>
                                <?php $_smarty_tpl->_assignInScope('sort_order_glyph', "glyphicon glyphicon-sort-by-attributes-alt");?>
                                <?php $_smarty_tpl->_assignInScope('sort_order_link', "ASC");?>
                            <?php } else { ?>
                                <?php $_smarty_tpl->_assignInScope('sort_order_glyph', "glyphicon glyphicon-sort-by-attributes");?>
                                <?php $_smarty_tpl->_assignInScope('sort_order_link', "DESC");?>
                            <?php }?>

                            <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['sort_field'] == $_smarty_tpl->tpl_vars['column']->value->title) {?>
                                <th>
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['script_name'];?>
&amp;action=<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['action'];?>
&amp;sort=<?php echo $_smarty_tpl->tpl_vars['column']->value->title;?>
&amp;order=<?php echo $_smarty_tpl->tpl_vars['sort_order_link']->value;
if ($_smarty_tpl->tpl_vars['WEB_APP']->value['field_field'] != '') {?>&amp;field=<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['field_field'];
}
if ($_smarty_tpl->tpl_vars['WEB_APP']->value['text_field'] != '') {?>&amp;text=<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['text_field'];
}
if ($_smarty_tpl->tpl_vars['WEB_APP']->value['page'] != 1) {?>&amp;page=<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['page'];
}?>"
                                       title="<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_sort'];?>
"><?php echo $_smarty_tpl->tpl_vars['column']->value->name;?>
</a>
                                    <span class="<?php echo $_smarty_tpl->tpl_vars['sort_order_glyph']->value;?>
"></span></th>
                            <?php } else { ?>
                                <th>
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['script_name'];?>
&amp;action=<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['action'];?>
&amp;sort=<?php echo $_smarty_tpl->tpl_vars['column']->value->title;?>
&amp;order=<?php echo $_smarty_tpl->tpl_vars['sort_order_link']->value;
if ($_smarty_tpl->tpl_vars['WEB_APP']->value['field_field'] != '') {?>&amp;field=<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['field_field'];
}
if ($_smarty_tpl->tpl_vars['WEB_APP']->value['text_field'] != '') {?>&amp;text=<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['text_field'];
}
if ($_smarty_tpl->tpl_vars['WEB_APP']->value['page'] != 1) {?>&amp;page=<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['page'];
}?>"
                                       title="<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_sort'];?>
"><?php echo $_smarty_tpl->tpl_vars['column']->value->name;?>
</a></th>
                            <?php }?>
                        <?php }?>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                    <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['editform'] == TRUE && (isset($_smarty_tpl->tpl_vars['WEB_APP']->value['row_actions'])) && $_smarty_tpl->tpl_vars['WEB_APP']->value['row_actions_access']) {?>
                        <th><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_actions'];?>
</th>
                    <?php }?>
                </tr>
                </thead><?php }
}
