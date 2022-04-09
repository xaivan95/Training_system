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
    <link href="{$WEB_APP.cfg_url}favicon.ico" rel="SHORTCUT ICON">
    <meta name="description" content="КАОС 54 кафедра">
    <!-- Bootstrap core CSS -->
    <link href="{$WEB_APP.css_dir}bootstrap.min.css" rel="stylesheet">
</head>
<body>
{include file="print/users_themes_header.tpl"}
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
{include file="print/users_themes_footer.tpl"}
</body>
</html>