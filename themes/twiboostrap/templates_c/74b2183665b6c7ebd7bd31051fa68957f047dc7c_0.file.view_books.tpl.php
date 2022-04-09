<?php
/* Smarty version 3.1.39, created on 2022-04-02 17:17:07
  from 'C:\OpenServer\domains\localhost\themes\twiboostrap\templates\view_books.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_62485ae39dbaa9_05509246',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '74b2183665b6c7ebd7bd31051fa68957f047dc7c' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\twiboostrap\\templates\\view_books.tpl',
      1 => 1636779968,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:errors.tpl' => 1,
  ),
),false)) {
function content_62485ae39dbaa9_05509246 (Smarty_Internal_Template $_smarty_tpl) {
?><h1 class="text-info h2"><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['title'];?>
</h1>
<?php $_smarty_tpl->_subTemplateRender("file:errors.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['courses'], 'course', false, NULL, 'courses', array (
  'index' => true,
));
$_smarty_tpl->tpl_vars['course']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['course']->value) {
$_smarty_tpl->tpl_vars['course']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_courses']->value['index']++;
?>
        <?php $_smarty_tpl->_assignInScope('course_number', (isset($_smarty_tpl->tpl_vars['__smarty_foreach_courses']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_courses']->value['index'] : null));?>
        <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['courses_books_count'][$_smarty_tpl->tpl_vars['course_number']->value] > 0) {?>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading<?php echo $_smarty_tpl->tpl_vars['course_number']->value;?>
">
                    <h3 class="panel-title">
                        <a role="button" data-toggle="collapse"
                           data-parent="#accordion" href="#collapse<?php echo $_smarty_tpl->tpl_vars['course_number']->value;?>
"
                           aria-expanded="true" aria-controls="collapse<?php echo $_smarty_tpl->tpl_vars['course_number']->value;?>
">
                            <span class="glyphicon glyphicon-resize-vertical"
                                  aria-hidden="true"></span> <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['courses'][$_smarty_tpl->tpl_vars['course_number']->value];?>

                        </a>
                    </h3>
                </div>
                <div id="collapse<?php echo $_smarty_tpl->tpl_vars['course_number']->value;?>
" class="panel-collapse collapse"
                     role="tabpanel" aria-labelledby="heading<?php echo $_smarty_tpl->tpl_vars['course_number']->value;?>
">
                    <div class="panel-body">
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['books_title'], 'book', false, NULL, 'books', array (
  'index' => true,
));
$_smarty_tpl->tpl_vars['book']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['book']->value) {
$_smarty_tpl->tpl_vars['book']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_books']->value['index']++;
?>
                            <?php $_smarty_tpl->_assignInScope('book_number', (isset($_smarty_tpl->tpl_vars['__smarty_foreach_books']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_books']->value['index'] : null));?>
                            <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['books_course'][$_smarty_tpl->tpl_vars['book_number']->value] == $_smarty_tpl->tpl_vars['WEB_APP']->value['courses'][$_smarty_tpl->tpl_vars['course_number']->value]) {?>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a title="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['submit_title'];?>
 <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['books_title'][$_smarty_tpl->tpl_vars['book_number']->value];?>
"
                                               href="?module=view_books&action=show&bid=<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['books_id'][$_smarty_tpl->tpl_vars['book_number']->value];?>
"><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['books_title'][$_smarty_tpl->tpl_vars['book_number']->value];?>
</a>
                                        </h4>
                                    </div>
                                    <div class="panel-body">
                                        <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['books_description'][$_smarty_tpl->tpl_vars['book_number']->value];?>
<br>
                                        <p class="text-right">
                                            <a title="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['submit_title'];?>
 <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['books_title'][$_smarty_tpl->tpl_vars['book_number']->value];?>
"
                                               class="btn btn-primary"
                                               href="?module=view_books&action=show&bid=<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['books_id'][$_smarty_tpl->tpl_vars['book_number']->value];?>
"><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['submit_title'];?>

                                                <span class="glyphicon glyphicon-chevron-right"
                                                      aria-hidden="true"></span></a>
                                        </p>
                                    </div>
                                </div>
                            <?php }?>
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
</div><?php }
}
