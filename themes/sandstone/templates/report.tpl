<h1 class="text-info h2">{$WEB_APP.title}</h1>
{include file="errors.tpl"}

{if count($WEB_APP.titles) neq 0}
    <div id="fill_fields">
        <table class="table table-striped table-bordered table-condensed">
            <tr>
                <th colspan=2>{$title}</th>
            </tr>
            {assign var="tmp" value="2"}
            {foreach from = $WEB_APP.titles item = item name=foreach_row}

            {if $item.name eq "header"}
            {*<table class="table table-striped table-bordered table-condensed">*}
            {*<tr>*}
            {*<th colspan = 2>{$item.value|escape}</th>*}
            {*</tr>*}
        </table>
        <h3>{$item.value|escape}</h3>
        <table class="table table-striped table-bordered table-condensed">
            {else}
            <tr>
                <td style="width:20%">{$item.name|escape}:</td>
                <td style="width:80%">{$item.value}</td>
            </tr>
            {/if}
            {/foreach}
        </table>
    </div>
{/if}
{if $WEB_APP.rows_count > 0}
    <h2>{$text.txt_results}</h2>
    {include file="table_header.tpl" columns=$WEB_APP.columns}
    {include file="table_rows.tpl" index='id' items=$WEB_APP.rows columns=$WEB_APP.columns}
    {include file="table_footer.tpl" columns_count=$WEB_APP.columns_count}
{/if}

{if $WEB_APP.themes_count > 0}
    {include file="table_header.tpl" columns=$WEB_APP.themes_columns}
    {include file="table_rows.tpl" index='id' items=$WEB_APP.themes columns=$WEB_APP.themes_columns}
    {include file="table_footer.tpl" columns_count=$WEB_APP.themes_columns_count}
{/if}

{if isset($smarty.session.bbid)}
    <p class="text-center">
        {if $smarty.session.bcid}
            <a class="btn btn-info"
               href="{$WEB_APP.cfg_url}?module=view_books&action=show&bid={$smarty.session.bbid}&cidx={$smarty.session.bcid}">{$text.txt_back_to_view_book}</a>
        {else}
            <a class="btn btn-info"
               href="{$WEB_APP.cfg_url}?module=view_books&action=show&bid={$smarty.session.bbid}">{$text.txt_back_to_view_book}</a>
        {/if}
    </p>
{/if}
