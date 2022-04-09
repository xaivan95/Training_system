<h1 class="text-info h2">{$WEB_APP.title}</h1>
{if count($WEB_APP.questions_text) neq 0}
    <table class="table table-striped table-bordered">
        <tr>
            <th>{$text.txt_view_test_questions} &laquo;{$WEB_APP.test->name}&raquo;</th>
        </tr>
        {foreach from = $WEB_APP.questions_text item = item name=foreach_row}
            <tr>
                <td>{$item.question_text_html}</td>
            </tr>
        {/foreach}
    </table>
    <br>
{/if}

