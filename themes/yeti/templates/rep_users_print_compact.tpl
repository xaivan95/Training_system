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
{include file="print/users_compact_header.tpl"}
{if $WEB_APP.rows_count > 0}
    <div class="container-fluid">
        <table class="table table-bordered table-condensed print">
            <thead>
                {foreach from = $WEB_APP.columns item = column}
                    <th class="active text-center">{$column->name}</th>
                {/foreach}

            </thead>
            <tbody>
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
            </tbody>
            <tfoot>
            <tr>
                <td colspan="5">
                    {include file="print/users_compact_table_footer.tpl"}
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
{/if}
{include file="print/users_compact_footer.tpl"}
</body>
</html>