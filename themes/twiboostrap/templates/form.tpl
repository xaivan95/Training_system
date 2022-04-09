{if isset($WEB_APP.fields)}{if !isset($WEB_APP.unshow_asterisk)}
    <div class="alert alert-info">{$text.txt_fields_marked_with_an_asterisk_are_required}</div>{/if}{/if}

<form action="{$WEB_APP.action_url}" method="POST"
      name="FORM" {if isset($WEB_APP.form_enctype) eq TRUE} enctype="multipart/form-data"{/if} role="form">
    {if isset($WEB_APP.fields)}

        <fieldset class="well well-sm">
        {assign var="tmp" value="2"}
        {foreach from = $WEB_APP.fields item = field name = foreach_item}
            {if $tmp eq 2}
                {assign var="tmp" value="1"}
            {else}
                {assign var="tmp" value="2"}
            {/if}

            {if $field->type eq 'submit_form'}
                <input type="submit" class="btn btn-primary" value="{$submit_title}" name="{$field->name}">
                &nbsp;
                <input type="button" class="btn btn-info" value="{$text.txt_help}"
                       onclick="window.open('{$WEB_APP.help_url}', 'mywindow')" name="help_button">
                {assign var="show_header_footer" value="0"}
                {*smarty3  {php} continue; {/php} *}
            {/if}

            {if $field->type eq "header"}
                </fieldset>
                {if !isset($show_header_footer)}{/if}
                {assign var="tmp" value="2"}
                <h3>{$field->title}:</h3>
                <fieldset class="well">
            {else}
                {if $tmp eq 1}
                    <div class="row-fluid">
                {/if}
                <div class="col-sm-6">
                    <div class="form-group">

                        {if $field->type eq "textarea"}
                            <label for="{$field->id}">{if $field->require}*{/if}{$field->title}</label>
                            <textarea {if $field->require} required{/if} class="form-control" name="{$field->name}"
                                                                         id="{$field->id}">{$field->value|escape}</textarea>
                        {elseif ($field->type eq "select") or ($field->type eq "multiple_select")}
                            <label for="{$field->name}">{if $field->require}*{/if}{$field->title}</label>
                            <select class="form-control {$field->add_class}" name="{$field->name}"
                                    id="{$field->name}" {$field->data_attribute}
                                    {if $field->require} required{/if}
                                    {if isset($field->on_change)}onchange="{$field->on_change}"{/if}
                                    {if $field->type eq "multiple_select"}multiple size="12"{/if}>
                                {if isset($WEB_APP.show_empty_value) eq FALSE}
                                    <option value="" {if $field->value eq ""}SELECTED{/if}></option>
                                {/if}
                                {assign var = "value" value = $field->option_value_field}
                                {assign var = "tmptext" value = $field->option_text_field}
                                {foreach from = $field->array item = item}
                                    {if $field->type eq "select"}
                                        <option value="{$item[$value]|escape}"
                                                {if $field->value eq $item[$tmptext]}SELECTED{/if}>{$item[$tmptext]|escape}</option>{/if}
                                    {if $field->type eq "multiple_select"}
                                        <option value="{$item[$value]|escape}"
                                                {if in_array($item[$tmptext], $field->value) }SELECTED{/if}>{$item[$tmptext]|escape}</option>{/if}
                                {/foreach}
                            </select>
                        {if isset($field->on_change) && isset($field->show_button)}&nbsp;<input type="submit"
                                                                                                class="btn"
                                                                                                name="select_button"
                                                                                                value="{$text.txt_select}">{/if}
                        {if $field->show_description}&nbsp;
                            <div id="{$field->name}_description_item"></div>{/if}


                        {elseif $field->type eq "checkbox"}
                            <input class="col-md-1" type="{$field->type}" name="{$field->name}"
                                   {if $field->value eq 1}CHECKED{else}UNCHECKED{/if}
                                    {if isset($field->on_change)}onclick='{$field->on_change}'{/if}
                                    {if $field->require} required{/if}
                                   id="{$field->id}">
                            <label for="{$field->id}">{if $field->require}*{/if}{$field->title}</label>
                        {elseif $field->type eq "radio"}
                            <input class="col-md-1" type="{$field->type}" name="{$field->name}"
                                   {if $field->value eq 1}CHECKED{else}UNCHECKED{/if}
                                    {if $field->require} required{/if}
                                   id="{$field->id}">
                            <label for="{$field->id}">{if $field->require}*{/if}{$field->title}</label>
                        {elseif $field->type eq "date"}
                            <label for="{$field->id}">{if $field->require}*{/if}{$field->title}</label>
                            <div class="input-group" id="{$field->name}">
                                <input type="{$field->type}" name="{$field->name}" id="{$field->name}"
                                       value="{$field->value|escape}"
                                       class="form-control"
                                        {if $field->type eq "file"} accept="{$field->accept}"{/if}
                                        {if $field->require} required{/if}>
                                <span class="input-group-addon"><span
                                            class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <script>
                                $(function () {
                                    $('#{$field->name}').datetimepicker({
                                        format: 'YYYY-MM-DD', locale: '{$WEB_APP['language_code']}'
                                    });
                                });
                            </script>
                        {else}
                            <label for="{$field->id}">{if $field->require}*{/if}{$field->title}</label>
                            <input type="{$field->type}" name="{$field->name}" value="{$field->value|escape}"
                                   class="form-control"
                                    {if $field->type eq "file"} accept="{$field->accept}"{/if}
                                    {if $field->require} required{/if}
                                   id="{$field->id}">
                        {/if}

                    </div>
                </div>
                {if $tmp eq 2}
                    </div>
                {/if}
            {/if}
        {/foreach}
        </fieldset>
    {/if}

    {if (!isset($show_buttons)) || (isset($show_buttons) && ($show_buttons neq 0))}
    <div style="text-align: right">
        <input type="submit" class="btn btn-primary" value="{$submit_title}" name="submit_button">&nbsp;
        <input type="button" class="btn btn-info" value="{$text.txt_help}"
               onclick="window.open('{$WEB_APP.help_url}', 'mywindow')" name="help_button">
    </div>
    <div>&nbsp;</div>
</form>
{/if}

