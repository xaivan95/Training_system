{if ($WEB_APP.book->engine_version < 5) && ($WEB_APP.book->contents!=='')}
    <html lang="{$WEB_APP.language_code}">
    <head>
        <meta charset="utf-8">
        <title>{$WEB_APP.chapter->title}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="{$WEB_APP.cfg_url}media/{$WEB_APP.book->mediastorage}/style.css" type="text/css">
        {if $WEB_APP.chapter->css neq ''}
            <style type="text/css">
                {$WEB_APP.chapter->css}
            </style>
        {/if}
    </head>
    <body>
        {$WEB_APP.chapter->text}
    </body>
    </html>
{else}
{if ($WEB_APP.book->contents eq "") or ($WEB_APP.book->contents eq "0")}
    <div class="row" style="margin-top: 25px">
        <div class="col-sm-12">
            {$WEB_APP.chapter->text}
        </div>
    </div>
{else}
    <div style="width: 100%; overflow: hidden;">
        <div style="margin-top: 30px; width: {$WEB_APP.book->toc_width}{$WEB_APP.book->toc_width_measure}; float: left; display: block;">
            <div id="jstree" style="width: 100%;"></div>
        </div>
        <div style="margin-left: {$WEB_APP.book->toc_width}{$WEB_APP.book->toc_width_measure}; margin-right:5px; padding-left:30px; ">
            {$WEB_APP.chapter->text}
        </div>
    </div>
    <script src="{$WEB_APP.cfg_url}js/jstree/jstree.min.js"></script>
    <script>
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
                "data" : {$WEB_APP.book->contents}
            }
        });
        tree.on('changed.jstree', function (e, data) {
            if (data.event) { window.location = data.instance.get_node(data.selected[0]).a_attr.href; }
        });
        tree.on('loaded.jstree', function () {
            tree.jstree('select_node', '{$WEB_APP.chapter->index}');
        });
    </script>
{/if}
{/if}