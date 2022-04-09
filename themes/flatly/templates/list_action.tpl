<h1 class="text-info h2">{$WEB_APP.title}</h1>
{include file="errors.tpl"}

{include file = "form.tpl" submit_title=$WEB_APP.submit_title form_title=$title show_buttons=0}
<br>
{include file="table_header.tpl" columns=$WEB_APP.columns}
{include file="table_rows.tpl" index='ID' items=$WEB_APP.items columns=$WEB_APP.columns}
{include file="table_footer.tpl" columns_count=$WEB_APP.columns_count}
{foreach from = $WEB_APP.items item = row name=foreach_row}
    <input type="hidden" name="selected_row[{$smarty.foreach.foreach_row.iteration}]" value="{$row.id}"
           id="checkbox_row_{$row.id}"/>
{/foreach}
<input type="hidden" name="list_action" value="confirm_{$WEB_APP.list_action}">
<div class="text-right">
    {if $WEB_APP.list_action_extra_checkbox eq TRUE}
    <input type="checkbox" name="extra_checkbox" value="checked" id="extra_checkbox"/>
    <label for="extra_checkbox">{$WEB_APP.list_action_extra_checkbox_title}</label>&nbsp;&nbsp;&nbsp;&nbsp;
    {/if}
    <input type="submit" {if $WEB_APP.list_action eq "delete"}class="btn btn btn-danger"
           {else}class="btn btn-primary"{/if}" value="{$WEB_APP.submit_title}"/>&nbsp;
    <input type="button" class="btn btn-info" value="{$text.txt_help}"
           onclick="window.open('{$WEB_APP.help_url}', 'help_window')">
</div>
</form>
