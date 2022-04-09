<!DOCTYPE html>
<html lang="{$WEB_APP.language_code}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {if $title neq ""}
        <title>{$title} - КАОС 54 кафедра</title>
    {else}
        <title>КАОС 54 кафедра </title>
    {/if}

    <link href="{$WEB_APP.css_dir}print.css" rel="stylesheet" type="text/css">
    <link href="{$WEB_APP.cfg_url}favicon.ico"  rel="SHORTCUT ICON">
    <meta name="description" content="КАОС 54 кафедра">
    <!-- Bootstrap core CSS -->
    <link href="{$WEB_APP.css_dir}bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
	<table class="table table-bordered">
		<tr>
			{foreach from = $WEB_APP.columns item = column}
				<th class="text-center"><strong>{$column->name}</strong></th>
			{/foreach}
		</tr>
		{foreach from = $WEB_APP.rows item = row name=foreach_row}
			<tr>
			{foreach from = $WEB_APP.columns item = item}
				{assign var="field" value=$item->title}
				{if !isset($WEB_APP.escape)}
					<td class="text-center">{$row[$field]|escape}</td>
				{else}
					<td class="text-center">{$row[$field]}</td>
				{/if}
			{/foreach}
			</tr>
		{/foreach}
	</table>
    </div>
</body>
</html>
