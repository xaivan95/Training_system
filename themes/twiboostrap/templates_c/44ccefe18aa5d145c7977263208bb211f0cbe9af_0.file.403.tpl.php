<?php
/* Smarty version 3.1.39, created on 2022-04-02 17:08:18
  from 'C:\OpenServer\domains\localhost\themes\twiboostrap\templates\403.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_624858d267e7b5_19104099',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '44ccefe18aa5d145c7977263208bb211f0cbe9af' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\twiboostrap\\templates\\403.tpl',
      1 => 1636779968,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_624858d267e7b5_19104099 (Smarty_Internal_Template $_smarty_tpl) {
?><h1 class="text-info h2"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h1>
<p><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_you_dont_have_permission_to_access'];?>

    <strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['WEB_APP']->value['request_uri'], ENT_QUOTES, 'UTF-8', true);?>
</strong> <?php echo $_smarty_tpl->tpl_vars['text']->value['txt_on_this_server'];?>
</p>
<?php }
}
