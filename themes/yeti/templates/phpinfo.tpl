<h1 class="text-info h2">{$text.txt_about}</h1>

<div class="row">
    <div class="col-sm-5 col-md-5">
        <img src="{$smarty.const.CFG_IMG_DIR}about.png" height="400" width="289" alt="КАОС 54 кафедра">
    </div>
    <div>
        <blockquote>
        <p>КАОС 54 кафедра v. <strong>{$WEB_APP.information_items.Version}</strong> (<em>{$WEB_APP.information_items.VersionDate}</em>)</p>
        <p>{$text.txt_db_version}: <strong>{$WEB_APP.settings.db_version}</strong></p>
        <p>{$text.txt_about_text}</p>
        </blockquote>
    </div>
</div>
