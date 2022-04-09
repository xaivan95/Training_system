<?php
/* Smarty version 3.1.39, created on 2022-04-02 17:03:38
  from 'C:\OpenServer\domains\localhost\themes\twiboostrap\templates\index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_624857baa41417_38906227',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '526311568a83b0d44c4c5b4e3fc50684915d5051' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\twiboostrap\\templates\\index.tpl',
      1 => 1636779968,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_624857baa41417_38906227 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="well">
    <h1 class="text-info text-center">КАОС 54 кафедра</h1>
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
