{include file="errors.tpl"}
{include file="test_message.tpl"}

<br><div class="alert alert-info"><strong>{$text.txt_the_testing_is_over}</strong><br>
    {$text.txt_test}: {$WEB_APP.test_name}</div>

{if $WEB_APP.is_show_results_message eq 1}
    {if $WEB_APP.settings.tst_showstats}
        <h2>{$text.txt_statistics}</h2>
        {include file="table_header.tpl" columns=$WEB_APP.stat_columns}
        {include file="table_rows.tpl" items=$WEB_APP.stat columns=$WEB_APP.stat_columns}
        {include file="table_footer.tpl" columns_count=$WEB_APP.stat_columns_count}
        <br>
    {/if}

    {if count($WEB_APP.user_result_themes) neq 0}
        {assign var="tmp" value="1"}
        {foreach from = $WEB_APP.user_result_themes item = item name=foreach_row}
            {if $item.name eq "header"}
                <table class="table table-striped table-bordered table-condensed">
                <thead>
                <tr>
                    <th colspan=2>{$item.value|escape}</th>
                </tr>
                </thead>
            {else}
                <tr>
                    <td style="width:30%">{$item.name|escape}:</td>
                    <td style="width:70%">{$item.value}</td>
                </tr>
            {/if}
        {/foreach}
        </table>
        <br>
    {/if}
    {if $WEB_APP.settings.tst_showrating}
        <h2>{$text.txt_rating}</h2>
        {include file="table_header.tpl" columns=$WEB_APP.columns}
        {include file="table_rows.tpl" items=$WEB_APP.rows columns=$WEB_APP.columns}
        {include file="table_footer.tpl" columns_count=$WEB_APP.columns_count}
    {/if}
{/if}

{if $WEB_APP.show_answers_log}
    <a class="btn btn-info"
       href="?module=view_results&action=view_result&id={$WEB_APP.result_id}">{$text.txt_view_results}</a>
{/if}

{if isset($smarty.session.bbid)}
    <p class="text-center">
        {if $smarty.session.bcid}
            {if $smarty.session.bm}
                <a class="btn btn-info"
                   href="{$WEB_APP.cfg_url}?module=view_books&action=show&bid={$smarty.session.bbid}&cidx={$smarty.session.bcid}#{$smarty.session.bm}">{$text.txt_back_to_view_book}</a>
            {else}
                <a class="btn btn-info"
                   href="{$WEB_APP.cfg_url}?module=view_books&action=show&bid={$smarty.session.bbid}&cidx={$smarty.session.bcid}">{$text.txt_back_to_view_book}</a>
            {/if}
        {else}
            <a class="btn btn-info"
               href="{$WEB_APP.cfg_url}?module=view_books&action=show&bid={$smarty.session.bbid}">{$text.txt_back_to_view_book}</a>
        {/if}
    </p>
{/if}