<html lang="{$WEB_APP.language_code}">
<head>
    <title>{$WEB_APP.book->title}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel=stylesheet href="{$WEB_APP.cfg_url}book_themes/{$WEB_APP.book->theme}/theme.css" type=text/css>
    <script src="{$WEB_APP.cfg_url}book_themes/{$WEB_APP.book->theme}/theme.js"></script>
    <script src="{$WEB_APP.cfg_url}js/jscooktree.js"></script>
    <script>
        let theme = get_theme('{$WEB_APP.cfg_url}book_themes/{$WEB_APP.book->theme}');
        let menu = [{$WEB_APP.book->contents}];
    </script>
    <base target="content">
</head>
<body>
<div ID=Menu class=Menu onMouseDown="return false"></div>
<HR>
<a href="{$WEB_APP.cfg_url}?module=view_books" target="_top" class="SelBook">{$WEB_APP['select_book']}</a>

<script>
    drawTree("Menu", '<img src={$WEB_APP.cfg_url}book_themes/{$WEB_APP.book->theme}/home.gif border="0">{$WEB_APP.book->title}', menu, theme);
</script>
</body>
</html>