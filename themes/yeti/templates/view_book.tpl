{if ($WEB_APP.book->engine_version < 5) && ($WEB_APP.book->contents!=='')}
    <!--suppress HtmlDeprecatedAttribute, HtmlDeprecatedTag -->
<html lang="{$WEB_APP.language_code}">
<head>
    <meta charset="utf-8">
    <title>{$WEB_APP.book->title}</title>
    <meta name=description content="КАОС 54 кафедра">
</head>
<frameset cols=25%,* rows=*>
    <FRAME name=menu src="{$WEB_APP.cfg_url}?module=view_books&action=menu&id={$WEB_APP.id}" frameborder="1" scrolling="auto">
    <FRAME name=content src="{$WEB_APP.cfg_url}?module=view_books&action=content">
</frameset>
</html>
{else}
    {include file="errors.tpl"}
    {if ($WEB_APP.book->contents eq "") or ($WEB_APP.book->contents eq "0")}
    <div class="row" style="margin-top: 25px">
        <div class="col-sm-12">
            {$WEB_APP.chapter->text}
        </div>
    </div>
{else}
    <div style="width: 100%; overflow: hidden; ">
        <div style="width: {$WEB_APP.book->toc_width}{$WEB_APP.book->toc_width_measure}; float: left; display: block;margin-top: 22px">
            <div id="jstree" style="width: 100%;"></div>
        </div>
        <div style="margin-left: {$WEB_APP.book->toc_width}{$WEB_APP.book->toc_width_measure}; margin-right:5px; padding-left:30px; ">
            {$WEB_APP.chapter->text}
        </div>
    </div>
    <script src="{$WEB_APP.cfg_url}js/jquery.min.js"></script>
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