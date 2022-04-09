<ul class="pagination">
{foreach from=$WEB_APP.count_array item=count_value}
	{if $count_value eq $WEB_APP.count}
		<li  class="active"><a href="#">{if $count_value eq 0}{$text.txt_all}{else}{$count_value}</a></li>{/if}
	{else}
        <li><a href="{$WEB_APP.paginator->get_url_page(1)}&amp;count={$count_value}"
		   title="{if $count_value eq 0}{$text.txt_all_items_on_the_page}{else}{$count_value} {$text.txt_items_on_the_page}{/if}">{if $count_value eq 0}{$text.txt_all}{else}{$count_value}{/if}</a></li>
	{/if}
{/foreach}
	</ul>