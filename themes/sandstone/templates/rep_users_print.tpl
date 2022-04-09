<!DOCTYPE html>
<html lang="{$WEB_APP.language_code}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {if $title neq ""}
        <title>{$title} - КАОС 54 кафедра</title>
    {else}
        <title>КАОС 54 кафедра</title>
    {/if}

    <link href="{$WEB_APP.css_dir}print.css" rel="stylesheet" type="text/css">
    <link href="{$WEB_APP.cfg_url}favicon.ico"  rel="SHORTCUT ICON">
    <meta name="description" content="КАОС 54 кафедра">
    <!-- Bootstrap core CSS -->
    <link href="{$WEB_APP.css_dir}bootstrap.min.css" rel="stylesheet">
</head>
<body>
{if sizeof($WEB_APP.titles) neq 0}
    <div class="container-fluid">
        <table class="table table-bordered table-condensed">
            {assign var="tmp" value="2"}
            {foreach from = $WEB_APP.titles item = item name=foreach_row}
            {if $tmp eq 2}
                {assign var="tmp" value="1"}
            {else}
                {assign var="tmp" value="2"}
            {/if}
            {if $item.name eq "header"}
        </table>
        <div>&nbsp;</div>{assign var="tmp" value="2"}
        <table class="table table-bordered table-condensed">
            <tr>
                <th colspan = 2>{$item.value}</th>
            </tr>
            {else}
            <tr>
                <th  style="width:20%">{$item.name|escape}:</th>
                <td  style="width:80%">{$item.value}</td>

            </tr>
            {/if}
            {/foreach}
        </table>
    </div>
{/if}
{if $WEB_APP.rows_count > 0}
<div class="container-fluid">
    <table class="table table-bordered table-condensed">
        <tr>
            {foreach from = $WEB_APP.columns item = column}
                <th class="text-center">{$column->name}</th>
            {/foreach}
        </tr>
        {foreach from = $WEB_APP.rows item = row name=foreach_row}
            <tr>
                {foreach from = $WEB_APP.columns item = item}
                    {assign var="field" value=$item->title}
                    {if !isset($WEB_APP.escape)}
                        <td>{$row[$field]|escape}</td>
                    {else}
                        <td>{$row[$field]}</td>
                    {/if}
                {/foreach}
            </tr>
        {/foreach}
    </table>
    </div>
{/if}
{if $WEB_APP.themes_count > 0}
<div class="container-fluid">
    <table class="table table-bordered table-condensed">
        <tr>
            {foreach from = $WEB_APP.themes_columns item = column}
                <th class="text-center">{$column->name}</th>
            {/foreach}
        </tr>
        {foreach from = $WEB_APP.themes item = row name=foreach_row}
            <tr>
                {foreach from = $WEB_APP.themes_columns item = item}
                    {assign var="field" value=$item->title}
                    {if !isset($WEB_APP.escape)}
                        <td>{$row[$field]|escape}</td>
                    {else}
                        <td>{$row[$field]}</td>
                    {/if}
                {/foreach}
            </tr>
        {/foreach}
    </table>
    </div>
{/if}
</body>
</html>
