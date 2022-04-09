<h1 class="text-info h2">{$title}</h1>
{include file="errors.tpl"}

{if $WEB_APP.show_form} 
	{include file = "form.tpl" submit_title=$WEB_APP.submit_title form_title=$WEB_APP.title_add}	
{/if}

{if $WEB_APP.show_table}
	{if $WEB_APP.show_form}
		<br>
	{/if}
{if !$WEB_APP.show_table_only}
	<p><a class="btn btn-default" href="{$WEB_APP.print_url}"><span class="glyphicon glyphicon-print"></span>&nbsp;{$text.txt_print_report}</a></p>
{/if}
	{include file="table_header.tpl" columns=$WEB_APP.columns}
	{include file="table_rows.tpl" index='id' items=$WEB_APP.rows columns=$WEB_APP.columns}
	{include file="table_footer.tpl" columns_count=$WEB_APP.columns_count}
{/if}
