<h1 class="text-info h2">{$WEB_APP.title}</h1>
{include file="errors.tpl"}

{if $WEB_APP.action eq "finish"}
    {include file="table_header.tpl" columns=$WEB_APP.columns}
    {include file="table_rows.tpl" index='ID' items=$WEB_APP.items columns=$WEB_APP.columns}
    {include file="table_footer.tpl" columns_count=$WEB_APP.columns_count}
    <form method="post" action="{$WEB_APP.script_name}">
        {foreach from = $WEB_APP.items item = row name=foreach_row}
            <input type="hidden" name="selected_row[{$smarty.foreach.foreach_row.iteration}]" value="{$row.id}"
                   id="checkbox_row_{$row.id}"/>
        {/foreach}
        <input type="hidden" name="action" value="confirm_finish">
        <div class="text-right"><input type="submit" class="btn" value="{$text.txt_finish}"/>&nbsp;
            <input type="button" class="btn btn-info" value="{$text.txt_help}"
                   onclick="window.open('{$WEB_APP.help_url}', 'help_window')"></div>
    </form>
{else}

    {if $WEB_APP.action eq "delete"}
        {include file="table_header.tpl" columns=$WEB_APP.columns}
        {include file="table_rows.tpl" index='ID' items=$WEB_APP.items columns=$WEB_APP.columns}
        {include file="table_footer.tpl" columns_count=$WEB_APP.columns_count}
        <form method="post" action="{$WEB_APP.script_name}">
            {foreach from = $WEB_APP.items item = row name=foreach_row}
                <input type="hidden" name="selected_row[{$smarty.foreach.foreach_row.iteration}]" value="{$row.id}"
                       id="checkbox_row_{$row.id}"/>
            {/foreach}
            <input type="hidden" name="action" value="confirm_delete">
            <div class="text-right">
                <input type="submit" class="btn btn-danger" value="{$text.txt_delete}"/>&nbsp;
                <input type="button" class="btn btn-info" value="{$text.txt_help}"
                       onclick="window.open('{$WEB_APP.help_url}', 'help_window')">
            </div>
        </form>
    {else}

        {if $WEB_APP.action eq "view"}
            {if isset($WEB_APP.language_paginator)}
                {include file = "language_paginator.tpl"}

            {/if}
            <br>
            {include file="filter.tpl"}
            <br>
            {include file="table_header.tpl" columns=$WEB_APP.columns}
            {include file="table_rows.tpl" index='ID' items=$WEB_APP.items columns=$WEB_APP.columns}
            {include file="table_footer.tpl" columns_count=$WEB_APP.columns_count}
            {if in_array('add', $WEB_APP.actions)}
                {if isset($WEB_APP.show_insert) eq TRUE}
                    {if $WEB_APP.show_insert eq TRUE}
                        <h2>{$WEB_APP.title_add}</h2>
                        {include file = "form.tpl" submit_title=$text.txt_add form_title=$WEB_APP.title_add}
                    {/if}
                {else}
                    <h2>{$WEB_APP.title_add}</h2>
                    {include file = "form.tpl" submit_title=$text.txt_add form_title=$WEB_APP.title_add}
                {/if}
            {/if}
        {else}

            {if $WEB_APP.action eq "edit"}
                {include file = "form.tpl"  submit_title=$text.txt_change form_title=$WEB_APP.title_edit}
            {else}
                {include file = "form.tpl" submit_title=$WEB_APP.submit_title form_title=$WEB_APP.form_title}
            {/if}
        {/if}
    {/if}

{/if}
