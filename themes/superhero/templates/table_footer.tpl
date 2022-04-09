
</table>
</div>
</div>

{if $WEB_APP.editform eq TRUE}
    {if !isset($WEB_APP.hide_delete) && $WEB_APP.list_actions_access}
    <div class="row-fluid">
        <form class="form-inline">
            <div class="form-group">
                <label>
                    <input type="checkbox"  name="all_items" value="all" id='all_items' onclick="MarkAllRows('fieldsForm')">&nbsp{$text.txt_check_all}&nbsp;
                </label>
            </div>
            <div class="form-group">
                <label class="sr-only" for="list_action">List</label>
                <select class="form-control input-sm" id="list_action" name="list_action">
                    {foreach from=$WEB_APP.list_actions item=action}{if in_array($action->name, $WEB_APP.actions)}
                            <option value="{$action->name}">{$action->title}</option>{/if}
                    {/foreach}
                 </select>&nbsp;
            </div>
            <div class="form-group">
                 <input type="submit" class="btn btn-default btn-sm" value="OK"/>
            </div>
        </form>
    </div>
        </form>
    {/if}
    <div class="row">
        <div class="col-md-6 col-sm-7">{include file="paginator.tpl" paginator=$WEB_APP.paginator url=$WEB_APP.script_name}</div>
        <div class="col-md-6 col-sm-5 text-right">{if $WEB_APP.settings.show_items_per_page}{include file = "count_paginator.tpl"}{/if}</div>
    </div>
{/if}

