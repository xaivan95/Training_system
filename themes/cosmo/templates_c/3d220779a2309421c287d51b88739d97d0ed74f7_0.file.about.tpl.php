<?php
/* Smarty version 3.1.39, created on 2022-04-03 16:57:11
  from 'C:\OpenServer\domains\localhost\themes\cosmo\templates\about.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6249a7b7571ec5_21605341',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3d220779a2309421c287d51b88739d97d0ed74f7' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\cosmo\\templates\\about.tpl',
      1 => 1648922049,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6249a7b7571ec5_21605341 (Smarty_Internal_Template $_smarty_tpl) {
?><h1 class="text-info h2"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_about'];?>
</h1>

<div class="row">
    <div class="col-sm-5 col-md-5">
        <img src="<?php echo (defined('CFG_IMG_DIR') ? constant('CFG_IMG_DIR') : null);?>
about.png" height="434" width="308" alt="КАОС 54 кафедра">
    </div>
    <div class="col-sm-7 col-md-7">
        <blockquote>
            <p>КАОС 54 кафедра s v. <strong><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['information_items']['Version'];?>
</strong>
                (<em><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['information_items']['VersionDate'];?>
</em>)</p>
            <p><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_db_version'];?>
: <strong><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['settings']['db_version'];?>
</strong></p>
            <p><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_about_text'];?>
</p>
        </blockquote>
    </div>
</div>
<?php }
}
