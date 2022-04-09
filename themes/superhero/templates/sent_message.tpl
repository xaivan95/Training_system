<h1 class="text-info h2">{$title}</h1>
{include file="errors.tpl"}
<div class="row">
    <div class="col-xs-12">
        {if $WEB_APP.subtitle neq ''}
            {$WEB_APP.subtitle}
        {/if}
        <p>{$WEB_APP.info}</p>
    </div>
</div>
<h2 class="text-info h3">{$text.txt_messages_ststus}</h2>
{include file="table_header.tpl" columns=$WEB_APP.columns}
{include file="table_rows.tpl" index='id' items=$WEB_APP.rows columns=$WEB_APP.columns}
{include file="table_footer.tpl" columns_count=$WEB_APP.columns_count}