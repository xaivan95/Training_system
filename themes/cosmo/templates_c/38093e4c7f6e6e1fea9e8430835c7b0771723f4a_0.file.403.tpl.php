<?php
/* Smarty version 3.1.39, created on 2022-04-03 17:22:23
  from 'C:\OpenServer\domains\localhost\themes\cosmo\templates\403.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6249ad9f9b3108_38575482',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '38093e4c7f6e6e1fea9e8430835c7b0771723f4a' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\cosmo\\templates\\403.tpl',
      1 => 1636779968,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6249ad9f9b3108_38575482 (Smarty_Internal_Template $_smarty_tpl) {
?><h1 class="text-info h2"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h1>
<p><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_you_dont_have_permission_to_access'];?>

    <strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['WEB_APP']->value['request_uri'], ENT_QUOTES, 'UTF-8', true);?>
</strong> <?php echo $_smarty_tpl->tpl_vars['text']->value['txt_on_this_server'];?>
</p>
<?php }
}
