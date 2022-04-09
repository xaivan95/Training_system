<?php
/* Smarty version 3.1.39, created on 2022-04-02 20:47:01
  from 'C:\OpenServer\domains\localhost\themes\cosmo\templates\view_book_content.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_62488c154ca3f6_21167109',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a013aefdf108bb4ec513a94f80c73049b852dfe8' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\cosmo\\templates\\view_book_content.tpl',
      1 => 1642393767,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62488c154ca3f6_21167109 (Smarty_Internal_Template $_smarty_tpl) {
if (($_smarty_tpl->tpl_vars['WEB_APP']->value['book']->engine_version < 5) && ($_smarty_tpl->tpl_vars['WEB_APP']->value['book']->contents !== '')) {?>
    <html lang="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['language_code'];?>
">
    <head>
        <meta charset="utf-8">
        <title><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['chapter']->title;?>
</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
media/<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['book']->mediastorage;?>
/style.css" type="text/css">
        <?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['chapter']->css != '') {?>
            <style type="text/css">
                <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['chapter']->css;?>

            </style>
        <?php }?>
    </head>
    <body>
        <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['chapter']->text;?>

    </body>
    </html>
<?php } else {
if (($_smarty_tpl->tpl_vars['WEB_APP']->value['book']->contents == '') || ($_smarty_tpl->tpl_vars['WEB_APP']->value['book']->contents == "0")) {?>
    <div class="row" style="margin-top: 25px">
        <div class="col-sm-12">
            <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['chapter']->text;?>

        </div>
    </div>
<?php } else { ?>
    <div style="width: 100%; overflow: hidden;">
        <div style="margin-top: 30px; width: <?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['book']->toc_width;
echo $_smarty_tpl->tpl_vars['WEB_APP']->value['book']->toc_width_measure;?>
; float: left; display: block;">
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
