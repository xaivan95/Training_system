<?php
/* Smarty version 3.1.39, created on 2022-04-04 17:49:55
  from 'C:\OpenServer\domains\localhost\themes\cosmo\templates\questions.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_624b0593c41bc2_00983776',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bbd64419e3ecb216b21ae261f703c908fb261a3f' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\cosmo\\templates\\questions.tpl',
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
function content_624b0593c41bc2_00983776 (Smarty_Internal_Template $_smarty_tpl) {
?><h1 class="text-info h2"><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['title'];?>
</h1>
<?php if (count($_smarty_tpl->tpl_vars['WEB_APP']->value['questions_text']) != 0) {?>
    <table class="table table-striped table-bordered">
        <tr>
            <th><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_view_test_questions'];?>
 &laquo;<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['test']->name;?>
&raquo;</th>
        </tr>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['questions_text'], 'item', false, NULL, 'foreach_row', array (
));
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
?>
            <tr>
                <td><?php echo $_smarty_tpl->tpl_vars['item']->value['question_text_html'];?>
</td>
            </tr>
        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </table>
    <div class="container-fluid">
        <div class="col-md-9"><?php $_smarty_tpl->_subTemplateRender("file:paginator.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('paginator'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['paginator'],'url'=>((string)$_smarty_tpl->tpl_vars['WEB_APP']->value).".script_name"), 0, false);
?></div>
        <div class="col-md-3"><?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['settings']['show_items_per_page']) {
$_smarty_tpl->_subTemplateRender("file:count_paginator.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?></div>
    </div>
    <br>
<?php }?>

<?php }
}
