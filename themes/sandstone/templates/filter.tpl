
<form class="form-inline" role="form">
    <div class="form-group">
        <input type="hidden" name="module" value="{$WEB_APP.module}">
        <label class="sr-only" for="combo">Field</label>
        <select name = "field" class="form-control input-sm" id="combo">
            {foreach from = $WEB_APP.columns item = column}
                <option value = "{$column->title|escape}" {if $WEB_APP.field eq $column->title}SELECTED{/if}>{$column->name|escape}</option>
            {/foreach}
        </select>
        </div>
    <div class="form-group">
        <label class="sr-only" for="text">Text</label>
        <input class="form-control input-sm" type = "text" name = "text" id="text" size=40 value="{$WEB_APP.text_field|escape}">
        <input type = "submit" class = "btn btn-default btn-sm" value = "{$text.txt_filter}">
    </div>
</form>

