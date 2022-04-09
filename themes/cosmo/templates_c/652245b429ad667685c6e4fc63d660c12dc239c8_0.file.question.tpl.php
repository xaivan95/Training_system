<?php
/* Smarty version 3.1.39, created on 2022-04-04 17:50:05
  from 'C:\OpenServer\domains\localhost\themes\cosmo\templates\question.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_624b059d585ba3_36988332',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '652245b429ad667685c6e4fc63d660c12dc239c8' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\cosmo\\templates\\question.tpl',
      1 => 1642393767,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:test_message.tpl' => 1,
    'file:js/shuffle.js' => 1,
  ),
),false)) {
function content_624b059d585ba3_36988332 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['WEB_APP']->value['errorstext'] != '') {?>
    <div class="alert alert-danger" xmlns="http://www.w3.org/1999/html"><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['errorstext'];?>
</div>
<?php }?>

<?php $_smarty_tpl->_subTemplateRender("file:test_message.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['hint']->text != '' || $_smarty_tpl->tpl_vars['WEB_APP']->value['hint']->html_text != '') {?>
    <div class="alert alert-info alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['hint']->text;
echo $_smarty_tpl->tpl_vars['WEB_APP']->value['hint']->html_text;?>

    </div>
<?php }?>


<?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['question']))) {?>
    <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['settings']['tst_showquestionnumber'] == 1) {?>
        <h3><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['title'];?>
</h3>
    <?php }?>
    <form name="test_form" id="test" method="post" action=""
          onsubmit="if (this.getAttribute('submitted')) return false; this.setAttribute('submitted','true');">
        <div id="fill_fields">
            <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['question']->picture_url != '') {?>
                <div class="col-xs-8"><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['question']->text_html;?>
</div>
                <input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['question']->text_html;?>
" name="question_text"/>
                <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['mmtype'] == "img") {?>
                    <div class="col-xs-9"><img src="media/<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['media_storage'];?>
/<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['question']->picture_url;?>
"
                                               style="float: right;" alt=""></div>
                <?php } else { ?>
                    <div class="col-xs-3">
                        <embed src="media/<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['media_storage'];?>
/<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['question']->picture_url;?>
"
                               type="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['mmtype'];?>
" align=right>
                    </div>
                <?php }?>
            <?php } else { ?>
                <div class="col-xs-12"><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['question']->text_html;?>
</div>
            <?php }?>
        </div>

    <?php ob_start();
echo $_smarty_tpl->tpl_vars['WEB_APP']->value['test_is_random_answers'];
$_prefixVariable1 = ob_get_clean();
if ($_prefixVariable1 == TRUE) {?>
        <?php echo '<script'; ?>
>
            <?php $_smarty_tpl->_subTemplateRender("file:js/shuffle.js", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
            <?php
$_smarty_tpl->tpl_vars['i'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int) ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? $_smarty_tpl->tpl_vars['WEB_APP']->value['question_fields_count']+1 - (0) : 0-($_smarty_tpl->tpl_vars['WEB_APP']->value['question_fields_count'])+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0) {
for ($_smarty_tpl->tpl_vars['i']->value = 0, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++) {
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration === 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration === $_smarty_tpl->tpl_vars['i']->total;?>
                $('select[name="inline[<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
]"] option').shuffle();
            <?php }
}
?>
        <?php echo '</script'; ?>
>
    <?php }?>
        <div>&nbsp;</div>

        <a id="answ"></a>
        
        <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['option_count'][0] > 0 || $_smarty_tpl->tpl_vars['WEB_APP']->value['option_count'][1] > 0 || $_smarty_tpl->tpl_vars['WEB_APP']->value['option_count'][2] > 0 || $_smarty_tpl->tpl_vars['WEB_APP']->value['option_count'][3] > 0 || $_smarty_tpl->tpl_vars['WEB_APP']->value['option_count'][4] > 0) {?>
            <div class="col-xs-12">
                <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['question']->type == 2) {?>
                    <h3><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_one_answer'];?>
</h3>
                <?php } else { ?>
                    <h3><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_answers'];?>
</h3>
                <?php }?>
            </div>
        <?php }?>

        <input type="hidden" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['WEB_APP']->value['question']->id, ENT_QUOTES, 'UTF-8', true);?>
" name="current_question"/>


        
        <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['option_count'][0] > 0) {?>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['answers'], 'answer', false, NULL, 'loop', array (
  'index' => true,
));
$_smarty_tpl->tpl_vars['answer']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['answer']->value) {
$_smarty_tpl->tpl_vars['answer']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_loop']->value['index']++;
?>
                <?php if ($_smarty_tpl->tpl_vars['answer']->value->option_type == 0) {?>
                    <div class="col-xs-12">
                        <div class="col-xs-1 text-right">
                                                        <input name="answer" type="radio" value="<?php echo $_smarty_tpl->tpl_vars['answer']->value->number;?>
" id="answer<?php echo $_smarty_tpl->tpl_vars['answer']->value->number;?>
"
                                   onclick="NextVisible()"
                                   <?php if ($_smarty_tpl->tpl_vars['answer']->value->number == $_smarty_tpl->tpl_vars['WEB_APP']->value['results_single']) {?>checked="checked"<?php }?>>
                        </div>
                        <div class="col-xs-11">
                            <label style="cursor:pointer; display:block;"
                                   for="answer<?php echo $_smarty_tpl->tpl_vars['answer']->value->number;?>
"><?php echo $_smarty_tpl->tpl_vars['answer']->value->text_html;?>
</label>
                        </div>
                    </div>
                <?php }?>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        <?php }?>



        
        <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['option_count'][1] > 0) {?>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['answers'], 'answer', false, NULL, 'loop', array (
  'index' => true,
));
$_smarty_tpl->tpl_vars['answer']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['answer']->value) {
$_smarty_tpl->tpl_vars['answer']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_loop']->value['index']++;
?>
                <?php if ($_smarty_tpl->tpl_vars['answer']->value->option_type == 1) {?>
                    <div class="col-xs-12">
                        <div class="col-xs-1 text-right">
                                                        <input name="answers[<?php echo $_smarty_tpl->tpl_vars['answer']->value->number;?>
]" type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['answer']->value->number;?>
"
                                   id="answers<?php echo $_smarty_tpl->tpl_vars['answer']->value->number;?>
" onclick="NextVisible()"
                                    <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['results_multiple'])) == TRUE) {?>
                                <?php if (is_array($_smarty_tpl->tpl_vars['WEB_APP']->value['results_multiple']) == TRUE) {?>
                                    <?php if (in_array($_smarty_tpl->tpl_vars['answer']->value->number,$_smarty_tpl->tpl_vars['WEB_APP']->value['results_multiple'],FALSE) == TRUE) {?>checked<?php }?>
                                <?php }?>
                                    <?php }?>>
                        </div>
                        <div class="col-xs-11">
                            <label style="cursor:pointer; display:block;"
                                   for="answers<?php echo $_smarty_tpl->tpl_vars['answer']->value->number;?>
"><?php echo $_smarty_tpl->tpl_vars['answer']->value->text_html;?>
</label>
                        </div>
                    </div>
                <?php }?>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        <?php }?>


        
        <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['option_count'][3] > 0) {?>
            <div class="row">
                <div class="col-xs-12">
                    <ul id="orderedList">
                        <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['results_ordered'])) == TRUE) {?>
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['results_ordered'], 'answer_number', false, NULL, 'answer_numbers', array (
));
$_smarty_tpl->tpl_vars['answer_number']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['answer_number']->value) {
$_smarty_tpl->tpl_vars['answer_number']->do_else = false;
?>
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['answers'], 'answer', false, NULL, 'loop', array (
  'index' => true,
));
$_smarty_tpl->tpl_vars['answer']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['answer']->value) {
$_smarty_tpl->tpl_vars['answer']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_loop']->value['index']++;
?>
                                    <?php if (($_smarty_tpl->tpl_vars['answer']->value->option_type == 3) && ($_smarty_tpl->tpl_vars['answer']->value->number == $_smarty_tpl->tpl_vars['answer_number']->value)) {?>
                                        <li id="<?php echo $_smarty_tpl->tpl_vars['answer']->value->number;?>
" class="ui-state-default"><?php echo $_smarty_tpl->tpl_vars['answer']->value->text_html;?>
</li>
                                    <?php }?>
                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                        <?php } else { ?>
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['answers'], 'answer', false, NULL, 'loop', array (
  'index' => true,
));
$_smarty_tpl->tpl_vars['answer']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['answer']->value) {
$_smarty_tpl->tpl_vars['answer']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_loop']->value['index']++;
?>
                                <?php if ($_smarty_tpl->tpl_vars['answer']->value->option_type == 3) {?>
                                    <li id="<?php echo $_smarty_tpl->tpl_vars['answer']->value->number;?>
" class="ui-state-default"><?php echo $_smarty_tpl->tpl_vars['answer']->value->text_html;?>
</li>
                                <?php }?>
                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                        <?php }?>
                    </ul>
                </div>
            </div>
            <input type="hidden" name="sequence" id="sequence"
                    <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['results_ordered_sequence'])) == TRUE) {?>
                value="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['results_ordered_sequence'];?>
"
                    <?php } else { ?>
                value="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['init_sequence'];?>
"
                    <?php }?>>
        <?php }?>


                <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['option_count'][4] > 0) {?>
        <div class="row">

                        <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['question']->show_basket1 == 1) {?>
            <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['question']->show_basket2 == 1) {?>
            <div class="col-xs-3">
                <?php } else { ?>
                <div class="col-xs-4">
                    <?php }?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title text-center">
                                <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['question']->matched_list1_caption;?>
&nbsp;&nbsp;<span
                                        class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <ul id="basketLeft">
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['answers'], 'answer', false, NULL, 'loop', array (
  'index' => true,
));
$_smarty_tpl->tpl_vars['answer']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['answer']->value) {
$_smarty_tpl->tpl_vars['answer']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_loop']->value['index']++;
?>
                                    <?php if ($_smarty_tpl->tpl_vars['answer']->value->option_type == 4) {?>
                                        <li id="<?php echo $_smarty_tpl->tpl_vars['answer']->value->number;?>
" class="ui-state-default"><?php echo $_smarty_tpl->tpl_vars['answer']->value->text_html;?>
</li>
                                    <?php }?>
                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php }?>

                
                <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['question']->show_basket2 == 1 && $_smarty_tpl->tpl_vars['WEB_APP']->value['question']->show_basket1 == 1) {?>
                <div class="col-xs-3">
                    <?php } elseif ($_smarty_tpl->tpl_vars['WEB_APP']->value['question']->show_basket1 == 1 || $_smarty_tpl->tpl_vars['WEB_APP']->value['question']->show_basket2 == 1) {?>
                    <div class="col-xs-4">
                        <?php } else { ?>
                        <div class="col-xs-6">
                            <?php }?>
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title text-center"><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['question']->matched_list1_caption;?>
</h3>
                                </div>
                                <div class="panel-body">
                                    <ul id="listLeft" <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['question']->show_basket1 == 1) {
}?>>
                                        <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['results_matched_left'])) == TRUE) {?>
                                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['results_matched_left'], 'answer_number', false, NULL, 'answer_numbers', array (
));
$_smarty_tpl->tpl_vars['answer_number']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['answer_number']->value) {
$_smarty_tpl->tpl_vars['answer_number']->do_else = false;
?>
                                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['answers'], 'answer', false, NULL, 'loop', array (
  'index' => true,
));
$_smarty_tpl->tpl_vars['answer']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['answer']->value) {
$_smarty_tpl->tpl_vars['answer']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_loop']->value['index']++;
?>
                                                    <?php if (($_smarty_tpl->tpl_vars['answer']->value->option_type == 4) && ($_smarty_tpl->tpl_vars['answer']->value->number == $_smarty_tpl->tpl_vars['answer_number']->value)) {?>
                                                        <li id="<?php echo $_smarty_tpl->tpl_vars['answer']->value->number;?>
"
                                                            class="ui-state-default"><?php echo $_smarty_tpl->tpl_vars['answer']->value->text_html;?>
</li>
                                                    <?php }?>
                                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                        <?php } else { ?>
                                            <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['question']->show_basket1 != 1) {?>
                                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['answers'], 'answer', false, NULL, 'loop', array (
  'index' => true,
));
$_smarty_tpl->tpl_vars['answer']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['answer']->value) {
$_smarty_tpl->tpl_vars['answer']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_loop']->value['index']++;
?>
                                                    <?php if ($_smarty_tpl->tpl_vars['answer']->value->option_type == 4) {?>
                                                        <li id="<?php echo $_smarty_tpl->tpl_vars['answer']->value->number;?>
"
                                                            class="ui-state-default"><?php echo $_smarty_tpl->tpl_vars['answer']->value->text_html;?>
</li>
                                                    <?php }?>
                                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                            <?php }?>
                                        <?php }?>
                                    </ul>
                                </div>
                            </div>
                            <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['question']->show_basket1 == 1) {?>
                                <button class="btn btn-default btn-block btn-sm clear_list_left" type="button"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_clear'];?>

                                </button>
                            <?php }?>
                        </div>


                                                <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['question']->show_basket2 == 1 && $_smarty_tpl->tpl_vars['WEB_APP']->value['question']->show_basket1 == 1) {?>
                        <div class="col-xs-3">
                            <?php } elseif ($_smarty_tpl->tpl_vars['WEB_APP']->value['question']->show_basket1 == 1 || $_smarty_tpl->tpl_vars['WEB_APP']->value['question']->show_basket2 == 1) {?>
                            <div class="col-xs-4">
                                <?php } else { ?>
                                <div class="col-xs-6">
                                    <?php }?>
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title text-center"><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['question']->matched_list2_caption;?>
</h3>
                                        </div>
                                        <div class="panel-body">
                                            <ul id="listRight" <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['question']->show_basket2 == 1) {
}?>>
                                                <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['results_matched_right'])) == TRUE) {?>
                                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['results_matched_right'], 'answer_number', false, NULL, 'answer_numbers', array (
));
$_smarty_tpl->tpl_vars['answer_number']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['answer_number']->value) {
$_smarty_tpl->tpl_vars['answer_number']->do_else = false;
?>
                                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['answers'], 'answer', false, NULL, 'loop', array (
  'index' => true,
));
$_smarty_tpl->tpl_vars['answer']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['answer']->value) {
$_smarty_tpl->tpl_vars['answer']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_loop']->value['index']++;
?>
                                                            <?php if (($_smarty_tpl->tpl_vars['answer']->value->option_type == 5) && ($_smarty_tpl->tpl_vars['answer']->value->number == $_smarty_tpl->tpl_vars['answer_number']->value)) {?>
                                                                <li id="<?php echo $_smarty_tpl->tpl_vars['answer']->value->number;?>
"
                                                                    class="ui-state-default"><?php echo $_smarty_tpl->tpl_vars['answer']->value->text_html;?>
</li>
                                                            <?php }?>
                                                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                                <?php } else { ?>
                                                    <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['question']->show_basket2 != 1) {?>
                                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['answers'], 'answer', false, NULL, 'loop', array (
  'index' => true,
));
$_smarty_tpl->tpl_vars['answer']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['answer']->value) {
$_smarty_tpl->tpl_vars['answer']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_loop']->value['index']++;
?>
                                                            <?php if ($_smarty_tpl->tpl_vars['answer']->value->option_type == 5) {?>
                                                                <li id="<?php echo $_smarty_tpl->tpl_vars['answer']->value->number;?>
"
                                                                    class="ui-state-default"><?php echo $_smarty_tpl->tpl_vars['answer']->value->text_html;?>
</li>
                                                            <?php }?>
                                                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                                    <?php }?>
                                                <?php }?>
                                            </ul>
                                        </div>
                                    </div>
                                    <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['question']->show_basket2 == 1) {?>
                                        <button class="btn btn-default btn-block btn-sm delete clear_list_right"
                                                type="button"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_clear'];?>

                                        </button>
                                    <?php }?>
                                </div>

                                                                <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['question']->show_basket2 == 1) {?>
                                <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['question']->show_basket1 == 1) {?>
                                <div class="col-xs-3">
                                    <?php } else { ?>
                                    <div class="col-xs-4">
                                        <?php }?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title text-center">
                                                    <span class="glyphicon glyphicon-chevron-left"
                                                          aria-hidden="true"></span>&nbsp;&nbsp;
                                                    <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['question']->matched_list2_caption;?>

                                                </h3>
                                            </div>
                                            <div class="panel-body">
                                                <ul id="basketRight">
                                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['answers'], 'answer', false, NULL, 'loop', array (
  'index' => true,
));
$_smarty_tpl->tpl_vars['answer']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['answer']->value) {
$_smarty_tpl->tpl_vars['answer']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_loop']->value['index']++;
?>
                                                        <?php if ($_smarty_tpl->tpl_vars['answer']->value->option_type == 5) {?>
                                                            <li id="<?php echo $_smarty_tpl->tpl_vars['answer']->value->number;?>
"
                                                                class="ui-state-default"><?php echo $_smarty_tpl->tpl_vars['answer']->value->text_html;?>
</li>
                                                        <?php }?>
                                                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }?>
                                </div>

                                <input type="hidden" name="sequence_left" id="sequence_left"
                                        <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['results_matched_left_init'])) == TRUE) {?>
                                    value="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['results_matched_left_init'];?>
"
                                        <?php } else { ?>
                                    <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['question']->show_basket1 != 1) {?>
                                        value="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['init_sequence_left'];?>
"
                                    <?php }?>
                                        <?php }?>>

                                <input type="hidden" name="sequence_right" id="sequence_right"
                                        <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['results_matched_right_init'])) == TRUE) {?>
                                    value="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['results_matched_right_init'];?>
"
                                        <?php } else { ?>
                                    <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['question']->show_basket2 != 1) {?>
                                        value="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['init_sequence_right'];?>
"
                                    <?php }?>
                                        <?php }?>>
                                <input type="hidden" name="added_left" id="added_left" value="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['added_left'];?>
">
                                <input type="hidden" name="added_right" id="added_right" value="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['added_right'];?>
">

                                <?php }?>



                                
                                <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['option_count'][2] > 0) {?>
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['answers'], 'answer', false, NULL, 'loop', array (
  'index' => true,
));
$_smarty_tpl->tpl_vars['answer']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['answer']->value) {
$_smarty_tpl->tpl_vars['answer']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_loop']->value['index']++;
?>
                                        <?php if ($_smarty_tpl->tpl_vars['answer']->value->option_type == 2) {?>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="sr-only"
                                                           for="open_answers[<?php echo $_smarty_tpl->tpl_vars['answer']->value->number;?>
]"><?php echo $_smarty_tpl->tpl_vars['answer']->value->number+1;?>
</label>
                                                    <?php if ($_smarty_tpl->tpl_vars['answer']->value->rows > 1) {?>
                                                        <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['results_open'])) == TRUE) {?>
                                                            <textarea <?php echo $_smarty_tpl->tpl_vars['answer']->value->css;?>
 name="open_answers[<?php echo $_smarty_tpl->tpl_vars['answer']->value->number;?>
]" id="open_answers[<?php echo $_smarty_tpl->tpl_vars['answer']->value->number;?>
]" class="form-control" rows="<?php echo $_smarty_tpl->tpl_vars['answer']->value->rows;?>
" spellcheck="false" autocomplete="off" <?php if ($_smarty_tpl->tpl_vars['answer']->value->bidi == 1) {?> dir="rtl" <?php }?>><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['results_open'][(isset($_smarty_tpl->tpl_vars['__smarty_foreach_loop']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_loop']->value['index'] : null)];?>
</textarea>
                                                        <?php } else { ?>
                                                            <textarea <?php echo $_smarty_tpl->tpl_vars['answer']->value->css;?>
 name="open_answers[<?php echo $_smarty_tpl->tpl_vars['answer']->value->number;?>
]" id="open_answers[<?php echo $_smarty_tpl->tpl_vars['answer']->value->number;?>
]" class="form-control" rows="<?php echo $_smarty_tpl->tpl_vars['answer']->value->rows;?>
" spellcheck="false" autocomplete="off" <?php if ($_smarty_tpl->tpl_vars['answer']->value->bidi == 1) {?> dir="rtl" <?php }?>></textarea>
                                                        <?php }?>
                                                        <p id="area-count" class="text-right"></p>
                                                    <?php } else { ?>
                                                        <input <?php echo $_smarty_tpl->tpl_vars['answer']->value->css;?>
 name="open_answers[<?php echo $_smarty_tpl->tpl_vars['answer']->value->number;?>
]"
                                                               type="text" <?php if ($_smarty_tpl->tpl_vars['answer']->value->max_length > 0) {?> maxlength="<?php echo $_smarty_tpl->tpl_vars['answer']->value->max_length;?>
" <?php }?>
                                                                <?php if ((isset($_smarty_tpl->tpl_vars['WEB_APP']->value['results_open'])) == TRUE) {?>
                                                                    value="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['results_open'][(isset($_smarty_tpl->tpl_vars['__smarty_foreach_loop']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_loop']->value['index'] : null)];?>
"
                                                                <?php }?>
                                                                <?php if ($_smarty_tpl->tpl_vars['answer']->value->bidi == 1) {?> dir="rtl" <?php }?>
                                                               id="open_answers[<?php echo $_smarty_tpl->tpl_vars['answer']->value->number;?>
]" class="form-control"
                                                               autocomplete="off" spellcheck="false">
                                                        <p id="input-count" class="text-right"></p>
                                                    <?php }?>
                                                </div>
                                            </div>
                                        <?php }?>
                                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                <?php }?>


                                <div>&nbsp;</div>
                                <div class="row">
                                    <div class="col-xs-8">
                                        <div class="btn-group">
                                            <?php if (($_smarty_tpl->tpl_vars['WEB_APP']->value['is_back'] == TRUE) && ($_smarty_tpl->tpl_vars['WEB_APP']->value['current_question'] > 1)) {?>
                                                <input type="button" class="btn btn-default" value="< <?php echo $_smarty_tpl->tpl_vars['text']->value['txt_back'];?>
"
                                                       name="back_button"
                                                       onclick="return back_question()">
                                            <?php }?>
                                            <input type="submit" class="btn btn-primary" value="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['submit_title'];?>
"
                                                   name="submit_button"
                                                   id="submit_button"
                                                    <?php if (($_smarty_tpl->tpl_vars['WEB_APP']->value['results'] == NULL) && ($_smarty_tpl->tpl_vars['WEB_APP']->value['user_must_answer'] == TRUE)) {?>disabled<?php }?>
                                            >
                                            <?php ob_start();
echo $_smarty_tpl->tpl_vars['WEB_APP']->value['submit_title'];
$_prefixVariable2 = ob_get_clean();
ob_start();
echo $_smarty_tpl->tpl_vars['text']->value['txt_answer'];
$_prefixVariable3 = ob_get_clean();
if (($_smarty_tpl->tpl_vars['WEB_APP']->value['settings']['tst_allowstoskip'] == 1) && ($_prefixVariable2 == $_prefixVariable3)) {?>
                                                <input type="button" class="btn btn-default" value="<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_skip'];?>
 >"
                                                       name="skip_button"
                                                       onclick="return skip_question()">
                                            <?php }?>
                                        </div>
                                    </div>
                                    <?php ob_start();
echo $_smarty_tpl->tpl_vars['WEB_APP']->value['show_break_button'];
$_prefixVariable4 = ob_get_clean();
if ($_prefixVariable4 == TRUE) {?>
                                    <div class="col-xs-4 text-right">
                                        <input type="button" class="btn btn-sm" value="<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_testing_break'];?>
"
                                               name="break_testing_button"
                                               onclick="if (confirm('<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_confirm_break_testing'];?>
')) return break_testing();">
                                    </div>
                                    <?php }?>
                                </div>
    </form>
    <div>&nbsp;</div>
    <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['question']->type == 2) {?>
        <?php echo '<script'; ?>
>document.getElementById('open_answers[0]').focus();<?php echo '</script'; ?>
>
    <?php }?>
    <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['question']->voice_record == 1) {?>
        <div id="record_buttons">
            <button id="btnRecord" onclick="startRecording(
            <?php echo $_SESSION['user_id'];?>
, <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['result_id'];?>
, <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['question_number'];?>
, <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['max_record_time'];?>
)"
                    class="btn btn-danger"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_record'];?>
</button>
            <button id="btnStop" onclick="stopRecording()" class="btn btn-warning" disabled><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_stop'];?>
</button>
            <?php ob_start();
echo $_smarty_tpl->tpl_vars['WEB_APP']->value['question']->voice_record_time_limited;
$_prefixVariable5 = ob_get_clean();
if ($_prefixVariable5 == 1) {?>
                <div><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_voice_record_max_time'];?>
: <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['question']->voice_record_max_time;?>
</div>
            <?php }?>
        </div>
        <pre id="log" style="margin-top: 5px"></pre>
        <div id="recordingsList"></div>
    <?php }?>


    <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['tst_showstats']) {?>
        <div class="col-sm-12">
            <div class="alert alert-info">
                <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['stat_total'] == TRUE) {?>
                    <p><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_total_questions'];?>
: <strong><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['total_questions'];?>
</strong></p>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['stat_current'] == TRUE) {?>
                    <p><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_current_question'];?>
: <strong><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['current_question'];?>
</strong></p>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['stat_rights'] == TRUE) {?>
                    <p><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_correct_answers'];?>
: <strong><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['correct_answers'];?>
</strong></p>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['stat_percent_of_rights'] == TRUE) {?>
                    <p><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_percentage_of_correct_answers'];?>
: <strong><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['percent_right'];?>
%</strong></p>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['stat_time'] == TRUE) {?>
                    <p id="time_left" data-time="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['full_time_left'];?>
"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_time_left'];?>
: <strong><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['time_left'];?>
</strong></p>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['stat_max_time'] == TRUE) {?>
                    <p><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_time_limit'];?>
: <strong><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['time_limit'];?>
</strong></p>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['stat_guid'] == TRUE) {?>
                    <p><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_guid'];?>
: <strong><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['test_guid'];?>
</strong></p>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['stat_test_version'] == TRUE) {?>
                    <p><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_version'];?>
: <strong><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['test_guid'];?>
</strong></p>
                <?php }?>
            </div>
        </div>
    <?php }
}
}
}
