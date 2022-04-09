<?php
/* Smarty version 3.1.39, created on 2022-04-02 19:53:59
  from 'C:\OpenServer\domains\localhost\themes\cosmo\templates\form.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_62487fa74ccb62_97912434',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0353da0223e7edd29cfb8a7334fd6312353dab0b' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\cosmo\\templates\\form.tpl',
      1 => 1636779968,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62487fa74ccb62_97912434 (Smarty_Internal_Template $_smarty_tpl) {
if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['fields']))) {
if (!(isset($_smarty_tpl->tpl_vars['WEB_APP']->value['unshow_asterisk']))) {?>
    <div class="alert alert-info"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_fields_marked_with_an_asterisk_are_required'];?>
</div><?php }
}?>

<form action="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['action_url'];?>
" method="POST"
      name="FORM" <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['form_enctype'])) == TRUE) {?> enctype="multipart/form-data"<?php }?> role="form">
    <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['fields']))) {?>

        <fieldset class="well well-sm">
        <?php $_smarty_tpl->_assignInScope('tmp', "2");?>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['fields'], 'field', false, NULL, 'foreach_item', array (
));
$_smarty_tpl->tpl_vars['field']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['field']->value) {
$_smarty_tpl->tpl_vars['field']->do_else = false;
?>
            <?php if ($_smarty_tpl->tpl_vars['tmp']->value == 2) {?>
                <?php $_smarty_tpl->_assignInScope('tmp', "1");?>
            <?php } else { ?>
                <?php $_smarty_tpl->_assignInScope('tmp', "2");?>
            <?php }?>

            <?php if ($_smarty_tpl->tpl_vars['field']->value->type == 'submit_form') {?>
                <input type="submit" class="btn btn-primary" value="<?php echo $_smarty_tpl->tpl_vars['submit_title']->value;?>
" name="<?php echo $_smarty_tpl->tpl_vars['field']->value->name;?>
">
                &nbsp;
                <input type="button" class="btn btn-info" value="<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_help'];?>
"
                       onclick="window.open('<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['help_url'];?>
', 'mywindow')" name="help_button">
                <?php $_smarty_tpl->_assignInScope('show_header_footer', "0");?>
                            <?php }?>

            <?php if ($_smarty_tpl->tpl_vars['field']->value->type == "header") {?>
                </fieldset>
                <?php if (!(isset($_smarty_tpl->tpl_vars['show_header_footer']->value))) {
}?>
                <?php $_smarty_tpl->_assignInScope('tmp', "2");?>
                <h3><?php echo $_smarty_tpl->tpl_vars['field']->value->title;?>
:</h3>
                <fieldset class="well">
            <?php } else { ?>
                <?php if ($_smarty_tpl->tpl_vars['tmp']->value == 1) {?>
                    <div class="row-fluid">
                <?php }?>
                <div class="col-sm-6">
                    <div class="form-group">

                        <?php if ($_smarty_tpl->tpl_vars['field']->value->type == "textarea") {?>
                            <label for="<?php echo $_smarty_tpl->tpl_vars['field']->value->id;?>
"><?php if ($_smarty_tpl->tpl_vars['field']->value->require) {?>*<?php }
echo $_smarty_tpl->tpl_vars['field']->value->title;?>
</label>
                            <textarea <?php if ($_smarty_tpl->tpl_vars['field']->value->require) {?> required<?php }?> class="form-control" name="<?php echo $_smarty_tpl->tpl_vars['field']->value->name;?>
"
                                                                         id="<?php echo $_smarty_tpl->tpl_vars['field']->value->id;?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['field']->value->value, ENT_QUOTES, 'UTF-8', true);?>
</textarea>
                        <?php } elseif (($_smarty_tpl->tpl_vars['field']->value->type == "select") || ($_smarty_tpl->tpl_vars['field']->value->type == "multiple_select")) {?>
                            <label for="<?php echo $_smarty_tpl->tpl_vars['field']->value->name;?>
"><?php if ($_smarty_tpl->tpl_vars['field']->value->require) {?>*<?php }
echo $_smarty_tpl->tpl_vars['field']->value->title;?>
</label>
                            <select class="form-control <?php echo $_smarty_tpl->tpl_vars['field']->value->add_class;?>
" name="<?php echo $_smarty_tpl->tpl_vars['field']->value->name;?>
"
                                    id="<?php echo $_smarty_tpl->tpl_vars['field']->value->name;?>
" <?php echo $_smarty_tpl->tpl_vars['field']->value->data_attribute;?>

                                    <?php if ($_smarty_tpl->tpl_vars['field']->value->require) {?> required<?php }?>
                                    <?php if ((isset($_smarty_tpl->tpl_vars['field']->value->on_change))) {?>onchange="<?php echo $_smarty_tpl->tpl_vars['field']->value->on_change;?>
"<?php }?>
                                    <?php if ($_smarty_tpl->tpl_vars['field']->value->type == "multiple_select") {?>multiple size="12"<?php }?>>
                                <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['show_empty_value'])) == FALSE) {?>
                                    <option value="" <?php if ($_smarty_tpl->tpl_vars['field']->value->value == '') {?>SELECTED<?php }?>></option>
                                <?php }?>
                                <?php $_smarty_tpl->_assignInScope('value', $_smarty_tpl->tpl_vars['field']->value->option_value_field);?>
                                <?php $_smarty_tpl->_assignInScope('tmptext', $_smarty_tpl->tpl_vars['field']->value->option_text_field);?>
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['field']->value->array, 'item');
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
?>
                                    <?php if ($_smarty_tpl->tpl_vars['field']->value->type == "select") {?>
                                        <option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value[$_smarty_tpl->tpl_vars['value']->value], ENT_QUOTES, 'UTF-8', true);?>
"
                                                <?php if ($_smarty_tpl->tpl_vars['field']->value->value == $_smarty_tpl->tpl_vars['item']->value[$_smarty_tpl->tpl_vars['tmptext']->value]) {?>SELECTED<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value[$_smarty_tpl->tpl_vars['tmptext']->value], ENT_QUOTES, 'UTF-8', true);?>
</option><?php }?>
                                    <?php if ($_smarty_tpl->tpl_vars['field']->value->type == "multiple_select") {?>
                                        <option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value[$_smarty_tpl->tpl_vars['value']->value], ENT_QUOTES, 'UTF-8', true);?>
"
                                                <?php if (in_array($_smarty_tpl->tpl_vars['item']->value[$_smarty_tpl->tpl_vars['tmptext']->value],$_smarty_tpl->tpl_vars['field']->value->value)) {?>SELECTED<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value[$_smarty_tpl->tpl_vars['tmptext']->value], ENT_QUOTES, 'UTF-8', true);?>
</option><?php }?>
                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                            </select>
                        <?php if ((isset($_smarty_tpl->tpl_vars['field']->value->on_change)) && (isset($_smarty_tpl->tpl_vars['field']->value->show_button))) {?>&nbsp;<input type="submit"
                                                                                                class="btn"
                                                                                                name="select_button"
                                                                                                value="<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_select'];?>
"><?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['field']->value->show_description) {?>&nbsp;
                            <div id="<?php echo $_smarty_tpl->tpl_vars['field']->value->name;?>
_description_item"></div><?php }?>


                        <?php } elseif ($_smarty_tpl->tpl_vars['field']->value->type == "checkbox") {?>
                            <input class="col-md-1" type="<?php echo $_smarty_tpl->tpl_vars['field']->value->type;?>
" name="<?php echo $_smarty_tpl->tpl_vars['field']->value->name;?>
"
                                   <?php if ($_smarty_tpl->tpl_vars['field']->value->value == 1) {?>CHECKED<?php } else { ?>UNCHECKED<?php }?>
                                    <?php if ($_smarty_tpl->tpl_vars['field']->value->require) {?> required<?php }?>
                                   id="<?php echo $_smarty_tpl->tpl_vars['field']->value->id;?>
">
                            <label for="<?php echo $_smarty_tpl->tpl_vars['field']->value->id;?>
"><?php if ($_smarty_tpl->tpl_vars['field']->value->require) {?>*<?php }
echo $_smarty_tpl->tpl_vars['field']->value->title;?>
</label>
                        <?php } elseif ($_smarty_tpl->tpl_vars['field']->value->type == "date") {?>
                            <label for="<?php echo $_smarty_tpl->tpl_vars['field']->value->id;?>
"><?php if ($_smarty_tpl->tpl_vars['field']->value->require) {?>*<?php }
echo $_smarty_tpl->tpl_vars['field']->value->title;?>
</label>
                            <div class="input-group" id="<?php echo $_smarty_tpl->tpl_vars['field']->value->name;?>
">
                                <input type="<?php echo $_smarty_tpl->tpl_vars['field']->value->type;?>
" name="<?php echo $_smarty_tpl->tpl_vars['field']->value->name;?>
" id="<?php echo $_smarty_tpl->tpl_vars['field']->value->name;?>
"
                                       value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['field']->value->value, ENT_QUOTES, 'UTF-8', true);?>
"
                                       class="form-control"
                                        <?php if ($_smarty_tpl->tpl_vars['field']->value->type == "file") {?> accept="<?php echo $_smarty_tpl->tpl_vars['field']->value->accept;?>
"<?php }?>
                                        <?php if ($_smarty_tpl->tpl_vars['field']->value->require) {?> required<?php }?>>
                                <span class="input-group-addon"><span
                                            class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <?php echo '<script'; ?>
>
                                $(function () {
                                    $('#<?php echo $_smarty_tpl->tpl_vars['field']->value->name;?>
').datetimepicker({
                                        format: 'YYYY-MM-DD', locale: '<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['language_code'];?>
'
                                    });
                                });
                            <?php echo '</script'; ?>
>
                        <?php } else { ?>
                            <label for="<?php echo $_smarty_tpl->tpl_vars['field']->value->id;?>
"><?php if ($_smarty_tpl->tpl_vars['field']->value->require) {?>*<?php }
echo $_smarty_tpl->tpl_vars['field']->value->title;?>
</label>
                            <input type="<?php echo $_smarty_tpl->tpl_vars['field']->value->type;?>
" name="<?php echo $_smarty_tpl->tpl_vars['field']->value->name;?>
" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['field']->value->value, ENT_QUOTES, 'UTF-8', true);?>
"
                                   class="form-control"
                                    <?php if ($_smarty_tpl->tpl_vars['field']->value->type == "file") {?> accept="<?php echo $_smarty_tpl->tpl_vars['field']->value->accept;?>
"<?php }?>
                                    <?php if ($_smarty_tpl->tpl_vars['field']->value->require) {?> required<?php }?>
                                   id="<?php echo $_smarty_tpl->tpl_vars['field']->value->id;?>
">
                        <?php }?>

                    </div>
                </div>
                <?php if ($_smarty_tpl->tpl_vars['tmp']->value == 2) {?>
                    </div>
                <?php }?>
            <?php }?>
        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </fieldset>
    <?php }?>

    <?php if ((!(isset($_smarty_tpl->tpl_vars['show_buttons']->value))) || ((isset($_smarty_tpl->tpl_vars['show_buttons']->value)) && ($_smarty_tpl->tpl_vars['show_buttons']->value != 0))) {?>
    <div style="text-align: right">
        <input type="submit" class="btn btn-primary" value="<?php echo $_smarty_tpl->tpl_vars['submit_title']->value;?>
" name="submit_button">&nbsp;
        <input type="button" class="btn btn-info" value="<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_help'];?>
"
               onclick="window.open('<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['help_url'];?>
', 'mywindow')" name="help_button">
    </div>
    <div>&nbsp;</div>
</form>
<?php }?>

<?php }
}
