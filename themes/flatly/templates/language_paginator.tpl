<ul class="pagination">
{if $WEB_APP.language eq ''}
	<li class="active"><a href="#">{$text.txt_all}</a></li>
{else}
	<li><a href="{$WEB_APP.script_name}&amp;language=all" title="{$text.txt_all_translations}">{$text.txt_all}</a></li>
{/if}
{foreach from=$WEB_APP.languages item=item}
	{if $item.short_name eq $WEB_APP.language}
    <li class="active"><a href="#">{$item.name}</a></li>
	{else}
		<li><a href="{$WEB_APP.script_name}&amp;language={$item.short_name}"
		   title="{$item.name} {$text.txt_lower_translations}">{$item.name}</a></li>
	{/if}
{/foreach}
</ul>
