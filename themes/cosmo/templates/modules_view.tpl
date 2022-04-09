<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand hidden-md" href="index.php">
                <img src="{$smarty.const.CFG_IMG_DIR}logo.png" title="КАОС 54 кафедра" alt="Logo" width="32"
                     height="32" style="margin-top: -4px;">
            </a>
            <a class="navbar-brand hidden-md" href="index.php">54 кафедра</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                {foreach from =$WEB_APP.categories item=category key=category_name}
                    {if count($category) > 0}
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">{$text[$category_name]}<span
                                        class="caret"></span></a>
                            <ul class="dropdown-menu">
                                {foreach from=$category item=module name=modules_loop}
                                    <li><a href="{$WEB_APP.cfg_url}?module={$module.module}">                                  
                                            {if basename($WEB_APP.module) eq $module.module}<strong>{/if} 
                                                {if isset($text[$module.module_name]) eq TRUE}{$text[$module.module_name]}{else}{$module.module_name}{/if}
                                                {if basename($WEB_APP.module) eq $module.module}</strong>{/if}
                                        </a></li>
                                {/foreach}
                            </ul>
                        </li>
                    {/if}
                {/foreach}
            </ul>
        </div>
    </div>
    {* User group/name *}
    {if $WEB_APP.user_info_title!==''}
        <div class="row navbar-right navbar-text">
            <div class="col-xs-12">
                {if isset($WEB_APP.book)}
                    <a href="?module=favorite_books&action=add" class="navbar-link">
                        <span class="glyphicon glyphicon-asterisk" title="{$text.txt_add_to_favorites}"></span>
                    </a>
                    &nbsp;
                {/if}
                <span class="glyphicon glyphicon-user"></span>&nbsp;
                {if $WEB_APP.user_info_login eq 'anonymous'}<a href="?module=login" class="navbar-link">{$text.txt_to_login}</a>
                {else}{$WEB_APP.user_info}&nbsp;
                {/if}
            </div>
        </div>
    {else}
        <div class="row text-right">
            <div class="col-xs-12">
                {if isset($WEB_APP.book)}
                    <a href="?module=favorite_books&action=add">
                        <span class="glyphicon glyphicon-asterisk" title="{$text.txt_add_to_favorites}"></span>
                    </a>
                    &nbsp;
                {/if}
            </div>
        </div>
    {/if}
    {**}
</nav>