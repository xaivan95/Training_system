<h1 class="text-info h2">{$WEB_APP.title}</h1>
{include file="errors.tpl"}
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    {foreach from = $WEB_APP.courses item = course name = courses}
        {assign var="course_number" value=$smarty.foreach.courses.index}
        {if $WEB_APP.courses_books_count[$course_number] gt 0}
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading{$course_number}">
                    <h3 class="panel-title">
                        <a role="button" data-toggle="collapse"
                           data-parent="#accordion" href="#collapse{$course_number}"
                           aria-expanded="true" aria-controls="collapse{$course_number}">
                            <span class="glyphicon glyphicon-resize-vertical"
                                  aria-hidden="true"></span> {$WEB_APP.courses[$course_number]}
                        </a>
                    </h3>
                </div>
                <div id="collapse{$course_number}" class="panel-collapse collapse"
                     role="tabpanel" aria-labelledby="heading{$course_number}">
                    <div class="panel-body">
                        {foreach from = $WEB_APP.books_title item = book name = books}
                            {assign var="book_number" value=$smarty.foreach.books.index}
                            {if $WEB_APP.books_course[$book_number] eq $WEB_APP.courses[$course_number]}
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a title="{$WEB_APP.submit_title} {$WEB_APP.books_title[$book_number]}"
                                               href="?module=view_books&action=show&bid={$WEB_APP.books_id[$book_number]}">{$WEB_APP.books_title[$book_number]}</a>
                                        </h4>
                                    </div>
                                    <div class="panel-body">
                                        {$WEB_APP.books_description[$book_number]}<br>
                                        <p class="text-right">
                                            <a title="{$WEB_APP.submit_title} {$WEB_APP.books_title[$book_number]}"
                                               class="btn btn-primary"
                                               href="?module=view_books&action=show&bid={$WEB_APP.books_id[$book_number]}">{$WEB_APP.submit_title}
                                                <span class="glyphicon glyphicon-chevron-right"
                                                      aria-hidden="true"></span></a>
                                        </p>
                                    </div>
                                </div>
                            {/if}
                        {/foreach}
                    </div>
                </div>
            </div>
        {/if}
    {/foreach}
</div>