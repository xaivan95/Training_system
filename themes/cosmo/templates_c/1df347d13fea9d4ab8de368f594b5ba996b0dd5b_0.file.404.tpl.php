<?php
/* Smarty version 3.1.39, created on 2022-04-02 19:49:20
  from 'C:\OpenServer\domains\localhost\themes\cosmo\templates\404.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_62487e90127f10_89809132',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1df347d13fea9d4ab8de368f594b5ba996b0dd5b' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\cosmo\\templates\\404.tpl',
      1 => 1636779968,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62487e90127f10_89809132 (Smarty_Internal_Template $_smarty_tpl) {
?><h1 class="text-info h2"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_404_not_found'];?>
</h1>
<p><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_the_requested_url'];?>
 <strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['WEB_APP']->value['request_uri'], ENT_QUOTES, 'UTF-8', true);?>
</strong> <?php echo $_smarty_tpl->tpl_vars['text']->value['txt_was_not_found_on_this_server'];?>
</p>
<?php }
}
