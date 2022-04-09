{if sizeof($WEB_APP.paginator->get_pages_array()) neq 1}
	<ul class="pagination">
		{foreach from = $WEB_APP.paginator->get_pages_array() item = item name = paginator}
			{if $smarty.foreach.paginator.last}
				{if $WEB_APP.paginator->current_page eq $item}
					<li class="active"><a href="#">{$WEB_APP.paginator->current_page}</a></li>
					<li><a href="#">{$text.txt_next} &#187;</a></li>
				{else}
					<li><a href = "{$WEB_APP.paginator->get_url_page($item)}" title = "{$text.txt_go_to_page} {$item}">{$item}</a></li>
					<li><a href = "{$WEB_APP.paginator->get_url_page($WEB_APP.paginator->get_next_page())}" title = "{$text.txt_go_to_next_page}">{$text.txt_next} &#187;</a></li>
				{/if}
			{elseif $item eq 1}
				{if $WEB_APP.paginator->current_page eq 1}
					<li><a href="#">&#171; {$text.txt_previous}</a></li>
					<li class="active"><a href="#">1</a></li>
				{else}
					{if $WEB_APP.paginator->current_page eq 2}
						<li><a href = "{$WEB_APP.paginator->get_url_page(1)}" class = "nextprev" title = "{$text.txt_go_to_previous_page}">&#171; {$text.txt_previous}</a></li>
					{else}
						<li><a href = "{$WEB_APP.paginator->get_url_page($WEB_APP.paginator->get_previous_page())}"  title = "{$text.txt_go_to_previous_page}">&#171; {$text.txt_previous}</a></li>
					{/if}
					<li><a href = "{$WEB_APP.paginator->get_url_page(1)}" title = "{$text.txt_go_to_page} 1">1</a></li>
				{/if}
			{elseif $item eq 0}
				<li><a href="#">&#8230;</a></li>
			{else} 
				{if $WEB_APP.paginator->current_page eq $item}
					<li class="active"><a href="#">{$item}</a></li>
				{else}
					<li><a href = "{$WEB_APP.paginator->get_url_page($item)}" title = "{$text.txt_go_to_page} {$item}">{$item}</a></li>
				{/if}
			{/if}
		{/foreach}
	</ul>
{/if}
