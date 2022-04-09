<?php
/* Smarty version 3.1.39, created on 2022-04-04 17:50:18
  from 'C:\OpenServer\domains\localhost\themes\cosmo\templates\end_test.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_624b05aa24e913_02875058',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7913b301cffd1477c01a536fde60f8c31ba33ed9' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\cosmo\\templates\\end_test.tpl',
      1 => 1637646187,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:errors.tpl' => 1,
    'file:test_message.tpl' => 1,
    'file:table_header.tpl' => 2,
    'file:table_rows.tpl' => 2,
    'file:table_footer.tpl' => 2,
  ),
),false)) {
function content_624b05aa24e913_02875058 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:errors.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
$_smarty_tpl->_subTemplateRender("file:test_message.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<br><div class="alert alert-info"><strong><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_the_testing_is_over'];?>
</strong><br>
    <?php echo $_smarty_tpl->tpl_vars['text']->value['txt_test'];?>
: <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['test_name'];?>
</div>

<?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['is_show_results_message'] == 1) {?>
    <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['settings']['tst_showstats']) {?>
        <h2><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_statistics'];?>
</h2>
        <?php $_smarty_tpl->_subTemplateRender("file:table_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('columns'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['stat_columns']), 0, false);
?>
        <?php $_smarty_tpl->_subTemplateRender("file:table_rows.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('items'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['stat'],'columns'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['stat_columns']), 0, false);
?>
        <?php $_smarty_tpl->_subTemplateRender("file:table_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('columns_count'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['stat_columns_count']), 0, false);
?>
        <br>
    <?php }?>

    <?php if (count($_smarty_tpl->tpl_vars['WEB_APP']->value['user_result_themes']) != 0) {?>
        <?php $_smarty_tpl->_assignInScope('tmp', "1");?>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['user_result_themes'], 'item', false, NULL, 'foreach_row', array (
));
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
?>
            <?php if ($_smarty_tpl->tpl_vars['item']->value['name'] == "header") {?>
                <table class="table table-striped table-bordered table-condensed">
                <thead>
                <tr>
                    <th colspan=2><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['value'], ENT_QUOTES, 'UTF-8', true);?>
</th>
                </tr>
                </thead>
            <?php } else { ?>
                <tr>
                    <td style="width:30%"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
:</td>
                    <td style="width:70%"><?php echo $_smarty_tpl->tpl_vars['item']->value['value'];?>
</td>
                </tr>
            <?php }?>
        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </table>
        <br>
    <?php }?>
    <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['settings']['tst_showrating']) {?>
        <h2><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_rating'];?>
</h2>
        <?php $_smarty_tpl->_subTemplateRender("file:table_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('columns'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['columns']), 0, true);
?>
        <?php $_smarty_tpl->_subTemplateRender("file:table_rows.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('items'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['rows'],'columns'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['columns']), 0, true);
?>
        <?php $_smarty_tpl->_subTemplateRender("file:table_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('columns_count'=>$_smarty_tpl->tpl_vars['WEB_APP']->value['columns_count']), 0, true);
?>
    <?php }
}?>

<?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['show_answers_log']) {?>
    <a class="btn btn-info"
       href="?module=view_results&action=view_result&id=<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['result_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_view_results'];?>
</a>
<?php }?>

<?php if ((isset($_SESSION['bbid']))) {?>
    <p class="text-center">
        <?php if ($_SESSION['bcid']) {?>
            <?php if ($_SESSION['bm']) {?>
                <a class="btn btn-info"
                   href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
?module=view_books&action=show&bid=<?php echo $_SESSION['bbid'];?>
&cidx=<?php echo $_SESSION['bcid'];?>
#<?php echo $_SESSION['bm'];?>
"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_back_to_view_book'];?>
</a>
            <?php } else { ?>
                <a class="btn btn-info"
                   href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
?module=view_books&action=show&bid=<?php echo $_SESSION['bbid'];?>
&cidx=<?php echo $_SESSION['bcid'];?>
"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_back_to_view_book'];?>
</a>
            <?php }?>
        <?php } else { ?>
            <a class="btn btn-info"
               href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
?module=view_books&action=show&bid=<?php echo $_SESSION['bbid'];?>
"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_back_to_view_book'];?>
</a>
        <?php }?>
    </p>
<?php }
}
}
