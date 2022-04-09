<h1 class="text-info h2">{$WEB_APP.title}</h1>
{include file="errors.tpl"}

{if $WEB_APP.show_form}
    {include file = "form.tpl" submit_title=$submit_title form_title=$title_add}
{/if}

{if $WEB_APP.show_table}
    <p><a class="btn btn-default" href="{$script_name}?action=print"><span class="glyphicon glyphicon-print"></span>&nbsp;{$text.txt_print_report}
        </a></p>
    {include file="table_header.tpl"}
    {include file="table_rows.tpl" index='ID' items=$rows}
    {include file="table_footer.tpl"}
{/if}
