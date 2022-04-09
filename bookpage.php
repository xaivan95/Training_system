<?php

if (isset($_GET['cidx']) && is_numeric($_GET['cidx']))
{
	header('Location: index.php?module=view_books&action=content&id='.$_GET['cidx']);
}
else
{
	header('Location: index.php?module=404');
}
