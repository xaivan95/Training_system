{if $WEB_APP.errorstext neq ""}
<div class="alert alert-danger">{$WEB_APP.errorstext}</div>
{/if}

{if isset($WEB_APP.infotext)}
<div class="alert alert-info">{$WEB_APP.infotext}</div>
{/if}
