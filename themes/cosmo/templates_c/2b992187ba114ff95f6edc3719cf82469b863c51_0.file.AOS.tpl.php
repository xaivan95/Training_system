<?php
/* Smarty version 3.1.39, created on 2022-04-03 20:59:09
  from 'C:\OpenServer\domains\localhost\themes\cosmo\templates\AOS.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6249e06d936305_14483588',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2b992187ba114ff95f6edc3719cf82469b863c51' => 
    array (
      0 => 'C:\\OpenServer\\domains\\localhost\\themes\\cosmo\\templates\\AOS.tpl',
      1 => 1649008709,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6249e06d936305_14483588 (Smarty_Internal_Template $_smarty_tpl) {
?> <p class="header"><span>Unity WebGL Player | </span>Pre-Jump</p>
    <div class="template-wrap clear">
    <canvas class="emscripten" id="canvas" oncontextmenu="event.preventDefault()" height="600px" width="960px"></canvas>
      <div class="logo"></div>
      <div class="fullscreen"><img src="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
system/AOS/1/TemplateData/fullscreen.png" width="38" height="38" alt="Fullscreen" title="Fullscreen" onclick="SetFullscreen(1);" /></div>
      <div class="title">Pre-Jump – Jump higher and higher. Use the Mouse to move from platform to platform.</div>
    </div>
   
    <?php echo '<script'; ?>
 type='text/javascript'>
  // connect to canvas
  var Module = {
    TOTAL_MEMORY: 268435456,
    filePackagePrefixURL: "Release/",
    memoryInitializerPrefixURL: "Release/",
    preRun: [],
    postRun: [],
    print: (function() {
      return function(text) {
        console.log (text);
      };
    })(),
    printErr: function(text) {
      console.error (text);
    },
    canvas: document.getElementById('canvas'),
    progress: null,
    setStatus: function(text) {
      if (this.progress == null)
      {
        if (typeof UnityProgress != 'function')
          return;
        this.progress = new UnityProgress (canvas);
      }
      if (!Module.setStatus.last) Module.setStatus.last = { time: Date.now(), text: '' };
      if (text === Module.setStatus.text) return;
      this.progress.SetMessage (text);
      var m = text.match(/([^(]+)\((\d+(\.\d+)?)\/(\d+)\)/);
      if (m)
        this.progress.SetProgress (parseInt(m[2])/parseInt(m[4]));
      if (text === "")
        this.progress.Clear()
    },
    totalDependencies: 0,
    monitorRunDependencies: function(left) {
      this.totalDependencies = Math.max(this.totalDependencies, left);
      Module.setStatus(left ? 'Preparing... (' + (this.totalDependencies-left) + '/' + this.totalDependencies + ')' : 'All downloads complete.');
    }
  };
  Module.setStatus('Downloading (0.0/1)');
<?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
system/AOS/1/Release/UnityConfig.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
system/AOS/1/Release/fileloader.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>if (!(!Math.fround)) {
  var script = document.createElement('script');
  script.src = "<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
system/AOS/1/Release/PreJump.js";
  document.body.appendChild(script);
} else {
  var codeXHR = new XMLHttpRequest();
  codeXHR.open('GET', '<?php echo $_smarty_tpl->tpl_vars['WEB_APP']->value['cfg_url'];?>
system/AOS/1/Release/PreJump.js', true);
  codeXHR.onload = function() {
    var code = codeXHR.responseText;
    if (!Math.fround) {
try {
  console.log('optimizing out Math.fround calls');
  var m = /var ([^=]+)=global\.Math\.fround;/.exec(code);
  var minified = m[1];
  if (!minified) throw 'fail';
  var startAsm = code.indexOf('// EMSCRIPTEN_START_FUNCS');
  var endAsm = code.indexOf('// EMSCRIPTEN_END_FUNCS');
  var asm = code.substring(startAsm, endAsm);
  do {
    var moar = false; // we need to re-do, as x(x( will not be fixed
    asm = asm.replace(new RegExp('[^a-zA-Z0-9\\$\\_]' + minified + '\\(', 'g'), function(s) { moar = true; return s[0] + '(' });
  } while (moar);
  code = code.substring(0, startAsm) + asm + code.substring(endAsm);
  code = code.replace("'use asm'", "'almost asm'");
} catch(e) { console.log('failed to optimize out Math.fround calls ' + e) }
 }

    var blob = new Blob([code], { type: 'text/javascript' });
    codeXHR = null;
    var src = URL.createObjectURL(blob);
    var script = document.createElement('script');
    script.src = URL.createObjectURL(blob);
    script.onload = function() {
      URL.revokeObjectURL(script.src);
    };
    document.body.appendChild(script);
  };
  codeXHR.send(null);
}
<?php echo '</script'; ?>
><?php }
}
