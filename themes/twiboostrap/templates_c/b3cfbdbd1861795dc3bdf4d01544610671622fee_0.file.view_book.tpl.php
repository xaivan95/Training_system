<?php
/* Smarty version 3.1.39, created on 2022-04-02 17:19:05
  from 'C:\OpenServer\domains\localhost\themes\twiboostrap\templates\view_book.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_62485b598125e4_69115586',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b3cfbdbd1861795dc3bdf4d01544610671622fee' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\twiboostrap\\templates\\view_book.tpl',
      1 => 1642393768,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:errors.tpl' => 1,
  ),
),false)) {
function content_62485b598125e4_69115586 (Smarty_Internal_Template $_smarty_tpl) {
if (($_smarty_tpl->tpl_vars['WEB_APP']->value['book']->engine_version < 5) && ($_smarty_tpl->tpl_vars['WEB_APP']->value['book']->contents !== '')) {?>
    <!--suppress HtmlDeprecatedAttribute, HtmlDeprecatedTag -->
<html lang="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['language_code'];?>
">
<head>
    <meta charset="utf-8">
    <title><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['book']->title;?>
</title>
    <meta name=description content="КАОС 54 кафедра">
</head>
<frameset cols=25%,* rows=*>
    <FRAME name=menu src="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
?module=view_books&action=menu&id=<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['id'];?>
" frameborder="1" scrolling="auto">
    <FRAME name=content src="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
?module=view_books&action=content">
</frameset>
</html>
<?php } else { ?>
    <?php $_smarty_tpl->_subTemplateRender("file:errors.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <?php if (($_smarty_tpl->tpl_vars['WEB_APP']->value['book']->contents == '') || ($_smarty_tpl->tpl_vars['WEB_APP']->value['book']->contents == "0")) {?>
    <div class="row" style="margin-top: 20px">
        <div class="col-sm-12">
            <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['chapter']->text;?>

        </div>
    </div>
<?php } else { ?>
    <div style="width: 100%; overflow: hidden; ">
        <div style="width: <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['book']->toc_width;
echo $_smarty_tpl->tpl_vars['WEB_APP']->value['book']->toc_width_measure;?>
; float: left; display: block;margin-top: 15px">
            <div id="jstree" style="width: 100%;"></div>
        </div>
        <div style="margin-left: <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['book']->toc_width;
echo $_smarty_tpl->tpl_vars['WEB_APP']->value['book']->toc_width_measure;?>
; margin-right:5px; padding-left:30px; ">
            <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['chapter']->text;?>

        </div>
    </div>
    <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
js/jquery.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
js/jstree/jstree.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
>
        let tree = $('#jstree').jstree({
            "plugins": ["types"],
            "types": {
                "folder": { "icon": "jstree-icon jstree-folder" },
                "file": { "icon": "jstree-icon jstree-file" }
            },
            "core" : {
                "themes": {
                    "dots" :false,"responsive" :true,"stripes":false,"icons":true
                },
                "data" : <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['book']->contents;?>

            }
        });
        tree.on('changed.jstree', function (e, data) {
            if (data.event) { window.location = data.instance.get_node(data.selected[0]).a_attr.href; }
        });
        tree.on('loaded.jstree', function () {
            tree.jstree('select_node', '<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['chapter']->index;?>
');
        });
    <?php echo '</script'; ?>
>
<?php }
}
}
}
