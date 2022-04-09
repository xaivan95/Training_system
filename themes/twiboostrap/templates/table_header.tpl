{if $WEB_APP.editform eq TRUE}
{if !isset($hide_delete)}
<form method="post" class="form-inline" action="{$WEB_APP.action_url}" name="fieldsForm" id="fieldsForm"
      role="form">{/if}
    {/if}
    <div class="row-fluid">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed">
                <thead>
                <tr>
                    {if $WEB_APP.editform eq TRUE && $WEB_APP.list_actions_access}
                        {if !isset($WEB_APP.hide_delete)}
                            <th>#</th>
                        {/if}
                    {/if}
                    {foreach from = $columns item = column}
                        {if $WEB_APP.editform eq FALSE}
                            <th>{$column->name}</th>
                        {else}
                            {assign var="sort_order" value="`$WEB_APP.default_order`"}
                            {if isset($WEB_APP.sort_order)}
                                {assign var="sort_order" value="`$WEB_APP.sort_order`"}
                            {/if}
                            {if $sort_order eq 'DESC'}
                                {assign var="sort_order_glyph" value="glyphicon glyphicon-sort-by-attributes-alt"}
                                {assign var="sort_order_link" value="ASC"}
                            {else}
                                {assign var="sort_order_glyph" value="glyphicon glyphicon-sort-by-attributes"}
                                {assign var="sort_order_link" value="DESC"}
                            {/if}

                            {if $WEB_APP.sort_field eq $column->title}
                                <th>
                                    <a href="{$WEB_APP.script_name}&amp;action={$WEB_APP.action}&amp;sort={$column->title}&amp;order={$sort_order_link}{if $WEB_APP.field_field neq ''}&amp;field={$WEB_APP.field_field}{/if}{if $WEB_APP.text_field neq ''}&amp;text={$WEB_APP.text_field}{/if}{if $WEB_APP.page neq 1}&amp;page={$WEB_APP.page}{/if}"
                                       title="{$text.txt_sort}">{$column->name}</a>
                                    <span class="{$sort_order_glyph}"></span></th>
                            {else}
                                <th>
                                    <a href="{$WEB_APP.script_name}&amp;action={$WEB_APP.action}&amp;sort={$column->title}&amp;order={$sort_order_link}{if $WEB_APP.field_field neq ''}&amp;field={$WEB_APP.field_field}{/if}{if $WEB_APP.text_field neq ''}&amp;text={$WEB_APP.text_field}{/if}{if $WEB_APP.page neq 1}&amp;page={$WEB_APP.page}{/if}"
                                       title="{$text.txt_sort}">{$column->name}</a></th>
                            {/if}
                        {/if}
                    {/foreach}
                    {if $WEB_APP.editform eq TRUE && isset($WEB_APP.row_actions) && $WEB_APP.row_actions_access}
                        <th>{$text.txt_actions}</th>
                    {/if}
                </tr>
                </thead>