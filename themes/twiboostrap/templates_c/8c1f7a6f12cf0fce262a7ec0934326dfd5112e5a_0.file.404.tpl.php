<?php
/* Smarty version 3.1.39, created on 2022-04-02 17:03:40
  from 'C:\OpenServer\domains\localhost\themes\twiboostrap\templates\404.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_624857bc0c3c95_46108941',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8c1f7a6f12cf0fce262a7ec0934326dfd5112e5a' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\twiboostrap\\templates\\404.tpl',
      1 => 1636779968,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_624857bc0c3c95_46108941 (Smarty_Internal_Template $_smarty_tpl) {
?><h1 class="text-info h2"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_404_not_found'];?>
</h1>
<p><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_the_requested_url'];?>
 <strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['WEB_APP']->value['request_uri'], ENT_QUOTES, 'UTF-8', true);?>
</strong> <?php echo $_smarty_tpl->tpl_vars['text']->value['txt_was_not_found_on_this_server'];?>
</p>
<?php }
}
