<?php
/* Smarty version 3.1.39, created on 2022-04-04 17:50:06
  from 'C:\OpenServer\domains\localhost\themes\cosmo\templates\test_message.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_624b059e530aa5_07013450',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7ed3f36970342534dd8ab8fa2211546661908f30' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\cosmo\\templates\\test_message.tpl',
      1 => 1636779968,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_624b059e530aa5_07013450 (Smarty_Internal_Template $_smarty_tpl) {
if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['wrong_message_title'])) && ($_smarty_tpl->tpl_vars['WEB_APP']->value['wrong_message_title'] != '')) {?>
    <div id="wrongAnswerMessage" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header  alert alert-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['wrong_message_title'];?>
</h4>
                </div>
                <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['explanation'])) && ($_smarty_tpl->tpl_vars['WEB_APP']->value['explanation'] != '')) {?>
                    <div class="modal-body">
                        <div><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['explanation'];?>
</div>
                    </div>
                    <hr>
                <?php }?>
                <div class="modal-header" id="btn_moreinfo">
                    <button type="button" class="btn btn-info" id="btn_show"
                            onclick="document.getElementById('moreinfo').style.display='block';
                                     document.getElementById('btn_moreinfo').style.display='none'"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_my_answer'];?>

                    </button>
                </div>
                <div id="moreinfo" style="display : none">
                    <div class="modal-header">
                        <h3><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_question'];?>
</h3>
                    </div>
                    <div class="modal-body">
                        <div><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['user_question'];?>
</div>
                    </div>
                    <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['user_answer'])) && ($_smarty_tpl->tpl_vars['WEB_APP']->value['user_answer'] != '')) {?>
                    <hr>
                    <div class="modal-header">
                        <h3><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_rep_answer'];?>
</h3>
                    </div>
                    <div class="modal-body">
                        <div><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['user_answer'];?>
</div>
                    </div>
                    <?php }?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="btn_close"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_close'];?>
</button>
                </div>
            </div>
        </div>
    </div>
    <?php echo '<script'; ?>
>$('#wrongAnswerMessage').on('shown.bs.modal', function () {
            $('#btn_close').focus();
        })  <?php echo '</script'; ?>
>
<?php }?>

<?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['right_message_title'])) && ($_smarty_tpl->tpl_vars['WEB_APP']->value['right_message_title'] != '')) {?>
    <div id="rightAnswerMessage" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header alert alert-success">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['right_message_title'];?>
</h4>
                </div>
                <div class="modal-body">
                    <div><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['explanation'];?>
</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="btn_close"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_close'];?>
</button>
                </div>
            </div>
        </div>
    </div>
    <?php echo '<script'; ?>
>$('#rightAnswerMessage').on('shown.bs.modal', function () {
            $('#btn_close').focus();
        })  <?php echo '</script'; ?>
>
<?php }
}
}
