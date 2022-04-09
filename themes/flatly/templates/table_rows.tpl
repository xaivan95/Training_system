{foreach from = $items item = row name=foreach_row}
	{cycle values="line1,line2" assign="line"}
	<tr>

	{if $WEB_APP.editform eq TRUE && $WEB_APP.list_actions_access}
		{if !isset($WEB_APP.hide_delete)}<td><input type="checkbox" name="selected_row[{$smarty.foreach.foreach_row.iteration}]" value="{$row.id}" id="checkbox_row_{$row.id}"  /></td>{/if}
	{/if}
	{foreach from = $columns item = item}
	{assign var="field" value=$item->title}
        {if (isset($WEB_APP.average)) && ($WEB_APP.average eq TRUE) && ($smarty.foreach.foreach_row.iteration eq $WEB_APP.rows_count)}
            <th>{$row[$field]}</th>
		{elseif isset($WEB_APP.test_max_score) && ($smarty.foreach.foreach_row.iteration eq 1)}
			<th>{$row[$field]}</th>
		{else}
		{if isset($WEB_APP.escape)}
			<td>{$row[$field]|escape|replace:"\r\n":"<br>"}</td>
		{else}
			<td>{$row[$field]}</td>
		{/if}
        {/if}
	{/foreach}
	{if $WEB_APP.editform eq TRUE && isset($WEB_APP.row_actions) && $WEB_APP.row_actions_access &&
	(!isset($WEB_APP.average) || !(($WEB_APP.average==TRUE) && ($smarty.foreach.foreach_row.iteration eq $WEB_APP.rows_count)))}
		<td>
				{foreach from = $WEB_APP.row_actions item = action}
					{if in_array($action->name, $WEB_APP.actions)}
                        <a href="{$WEB_APP.script_name}&amp;action={$action->name}&amp;id={$row.id}"><span class="glyphicon glyphicon-{$action->glyphicon}" title="{$action->title}"></span></a>&nbsp;&nbsp;
                    {/if}
				{/foreach}

		</td>
 	{/if}
	</tr>
{/foreach}