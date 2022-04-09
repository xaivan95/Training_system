<?php
/* Smarty version 3.1.39, created on 2022-04-02 19:53:53
  from 'C:\OpenServer\domains\localhost\themes\cosmo\templates\index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_62487fa1e58445_57093361',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8e57d8643a2ae53c60d2edd551c0ab2bfb5d1e7f' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\cosmo\\templates\\index.tpl',
      1 => 1648916069,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62487fa1e58445_57093361 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="well">
    <h1 class="text-info text-center">Образовательная среда 54 кафедры</h1>
    <hr>
    <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['user_info_login'] == 'anonymous') {?>
        <p><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_welcome_message'];?>
</p>
        <p><a href="?module=login" class="btn btn-primary btn-large"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_to_login'];?>
 &raquo;</a></p>
    <?php } else { ?>
        <div class="row">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['categories'], 'category', false, 'category_name');
$_smarty_tpl->tpl_vars['category']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['category_name']->value => $_smarty_tpl->tpl_vars['category']->value) {
$_smarty_tpl->tpl_vars['category']->do_else = false;
?>
                <?php if (count($_smarty_tpl->tpl_vars['category']->value) > 0) {?>
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><?php echo $_smarty_tpl->tpl_vars['text']->value[$_smarty_tpl->tpl_vars['category_name']->value];?>
</h3>
                            </div>
                            <div class="list-group">
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['category']->value, 'module', false, NULL, 'modules_loop', array (
));
$_smarty_tpl->tpl_vars['module']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['module']->value) {
$_smarty_tpl->tpl_vars['module']->do_else = false;
?>
                                    <a class="list-group-item" href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
?module=<?php echo $_smarty_tpl->tpl_vars['module']->value['module'];?>
">
                                        <?php if ((isset($_smarty_tpl->tpl_vars['text']->value[$_smarty_tpl->tpl_vars['module']->value['module_name']])) == TRUE) {
echo $_smarty_tpl->tpl_vars['text']->value[$_smarty_tpl->tpl_vars['module']->value['module_name']];
} else {
echo $_smarty_tpl->tpl_vars['module']->value['module_name'];
}?>
                                    </a>
                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                            </div>
                        </div>
                    </div>
                <?php }?>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </div>
    <?php }?>
</div><?php }
}
