{include file="form_page.tpl" form_title=$text.txt_import}


{if count($WEB_APP.log) neq 0}
	<h1 class="text-info h2">{$text.txt_log}</h1>
	<table>
	{assign var="tmp" value="1"}		
	{foreach from = $WEB_APP.log item = item name=foreach_row}
		{if $tmp eq 2}
				{assign var="tmp" value="1"}
			{else}
				{assign var="tmp" value="2"}
			{/if}
		<tr class = "line{$tmp}">
			<td>{$item|escape}</td>
		</tr>
	{/foreach}		
	</table>
	<br>		
{/if}
