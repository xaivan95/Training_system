<?php
/* Smarty version 3.1.39, created on 2022-04-02 17:05:49
  from 'C:\OpenServer\domains\localhost\themes\twiboostrap\templates\paginator.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6248583d20c4e3_95344746',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ca167fa186a087b79b069e47f5b2b0d676a76021' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\twiboostrap\\templates\\paginator.tpl',
      1 => 1636779968,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6248583d20c4e3_95344746 (Smarty_Internal_Template $_smarty_tpl) {
if (sizeof($_smarty_tpl->tpl_vars['WEB_APP']->value['paginator']->get_pages_array()) != 1) {?>
	<ul class="pagination">
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WEB_APP']->value['paginator']->get_pages_array(), 'item', false, NULL, 'paginator', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_paginator']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_paginator']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_paginator']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_paginator']->value['total'];
?>
			<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_paginator']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_paginator']->value['last'] : null)) {?>
				<?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['paginator']->current_page == $_smarty_tpl->tpl_vars['item']->value) {?>
					<li class="active"><a href="#"><?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['paginator']->current_page;?>
</a></li>
					<li><a href="#"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_next'];?>
 &#187;</a></li>
				<?php } else { ?>
					<li><a href = "<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['paginator']->get_url_page($_smarty_tpl->tpl_vars['item']->value);?>
" title = "<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_go_to_page'];?>
 <?php echo $_smarty_tpl->tpl_vars['item']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value;?>
</a></li>
					<li><a href = "<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['paginator']->get_url_page($_smarty_tpl->tpl_vars['WEB_APP']->value['paginator']->get_next_page());?>
" title = "<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_go_to_next_page'];?>
"><?php echo $_smarty_tpl->tpl_vars['text']->value['txt_next'];?>
 &#187;</a></li>
				<?php }?>
			<?php } elseif ($_smarty_tpl->tpl_vars['item']->value == 1) {?>
				<?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['paginator']->current_page == 1) {?>
					<li><a href="#">&#171; <?php echo $_smarty_tpl->tpl_vars['text']->value['txt_previous'];?>
</a></li>
					<li class="active"><a href="#">1</a></li>
				<?php } else { ?>
					<?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['paginator']->current_page == 2) {?>
						<li><a href = "<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['paginator']->get_url_page(1);?>
" class = "nextprev" title = "<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_go_to_previous_page'];?>
">&#171; <?php echo $_smarty_tpl->tpl_vars['text']->value['txt_previous'];?>
</a></li>
					<?php } else { ?>
						<li><a href = "<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['paginator']->get_url_page($_smarty_tpl->tpl_vars['WEB_APP']->value['paginator']->get_previous_page());?>
"  title = "<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_go_to_previous_page'];?>
">&#171; <?php echo $_smarty_tpl->tpl_vars['text']->value['txt_previous'];?>
</a></li>
					<?php }?>
					<li><a href = "<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['paginator']->get_url_page(1);?>
" title = "<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_go_to_page'];?>
 1">1</a></li>
				<?php }?>
			<?php } elseif ($_smarty_tpl->tpl_vars['item']->value == 0) {?>
				<li><a href="#">&#8230;</a></li>
			<?php } else { ?> 
				<?php if ($_smarty_tpl->tpl_vars['WEB_APP']->value['paginator']->current_page == $_smarty_tpl->tpl_vars['item']->value) {?>
					<li class="active"><a href="#"><?php echo $_smarty_tpl->tpl_vars['item']->value;?>
</a></li>
				<?php } else { ?>
					<li><a href = "<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['paginator']->get_url_page($_smarty_tpl->tpl_vars['item']->value);?>
" title = "<?php echo $_smarty_tpl->tpl_vars['text']->value['txt_go_to_page'];?>
 <?php echo $_smarty_tpl->tpl_vars['item']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value;?>
</a></li>
				<?php }?>
			<?php }?>
		<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	</ul>
<?php }
}
}
