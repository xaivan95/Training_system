<?php
/* Smarty version 3.1.39, created on 2022-04-02 19:53:58
  from 'C:\OpenServer\domains\localhost\themes\cosmo\templates\filter.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_62487fa6aa8f16_53193435',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '222fd3a1c36b8964bde59c9975d6349b80c2b229' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\cosmo\\templates\\filter.tpl',
      1 => 1636779968,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62487fa6aa8f16_53193435 (Smarty_Internal_Template $_smarty_tpl) {
?>
<form class="form-inline" role="form">
    <div class="form-group">
        <input type="hidden" name="module" value="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['module'];?>
">
        <label class="sr-only" for="combo">Field</label>
        <select name = "field" class="form-control input-sm" id="combo">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['columns'], 'column');
$_smarty_tpl->tpl_vars['column']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['column']->value) {
$_smarty_tpl->tpl_vars['column']->do_else = false;
?>
                <option value = "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['column']->value->title, ENT_QUOTES, 'UTF-8', true);?>
" <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['field'] == $_smarty_tpl->tpl_vars['column']->value->title) {?>SELECTED<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['column']->value->name, ENT_QUOTES, 'UTF-8', true);?>
</option>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </select>
        </div>
    <div class="form-group">
        <label class="sr-only" for="text">Text</label>
        <input class="form-control input-sm" type = "text" name = "text" id="text" size=40 value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['WEB_APP']->value['text_field'], ENT_QUOTES, 'UTF-8', true);?>
">
        <input type = "submit" class = "btn btn-default btn-sm" value = "<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_filter'];?>
">
    </div>
</form>

<?php }
}
