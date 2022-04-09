<div class="well">
    <h1 class="text-info text-center">КАОС 54 кафедра</h1>
    <hr>
    {if $WEB_APP.user_info_login eq 'anonymous'}
        <p>{$text.txt_welcome_message}</p>
        <p><a href="?module=login" class="btn btn-primary btn-large">{$text.txt_to_login} &raquo;</a></p>
    {else}
        <div class="row">
            {foreach from =$WEB_APP.categories item=category key=category_name}
                {if count($category) > 0}
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">{$text[$category_name]}</h3>
                            </div>
                            <div class="list-group">
                                {foreach from=$category item=module name=modules_loop}
                                    <a class="list-group-item" href="{$WEB_APP.cfg_url}?module={$module.module}">
                                        {if isset($text[$module.module_name]) eq TRUE}{$text[$module.module_name]}{else}{$module.module_name}{/if}
                                    </a>
                                {/foreach}
                            </div>
                        </div>
                    </div>
                {/if}
            {/foreach}
        </div>
    {/if}
</div>