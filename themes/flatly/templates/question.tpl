{if $WEB_APP.errorstext neq ""}
    <div class="alert alert-danger" xmlns="http://www.w3.org/1999/html">{$WEB_APP.errorstext}</div>
{/if}

{include file="test_message.tpl"}

{******************************

*  Show question hint         *

******************************}

{if $WEB_APP.hint->text neq "" OR $WEB_APP.hint->html_text neq ""}
    <div class="alert alert-info alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        {$WEB_APP.hint->text}{$WEB_APP.hint->html_text}
    </div>
{/if}

{******************************

*  Show question text         *

******************************}

{if isset($WEB_APP.question)}
    {if $WEB_APP.settings.tst_showquestionnumber eq 1}
        <h3>{$WEB_APP.title}</h3>
    {/if}
    <form name="test_form" id="test" method="post" action=""
          onsubmit="if (this.getAttribute('submitted')) return false; this.setAttribute('submitted','true');">
        <div id="fill_fields">
            {if $WEB_APP.question->picture_url neq ""}
                <div class="col-xs-8">{$WEB_APP.question->text_html}</div>
                <input type="hidden" value="{$WEB_APP.question->text_html}" name="question_text"/>
                {if $WEB_APP.mmtype eq "img"}
                    <div class="col-xs-9"><img src="media/{$WEB_APP.media_storage}/{$WEB_APP.question->picture_url}"
                                               style="float: right;" alt=""></div>
                {else}
                    <div class="col-xs-3">
                        <embed src="media/{$WEB_APP.media_storage}/{$WEB_APP.question->picture_url}"
                               type="{$WEB_APP.mmtype}" align=right>
                    </div>
                {/if}
            {else}
                <div class="col-xs-12">{$WEB_APP.question->text_html}</div>
            {/if}
        </div>

    {if {$WEB_APP.test_is_random_answers} eq TRUE}
        <script>
            {include file="js/shuffle.js"}
            {for $i= 0 to $WEB_APP.question_fields_count}
                $('select[name="inline[{$i}]"] option').shuffle();
            {/for}
        </script>
    {/if}
        <div>&nbsp;</div>

        <a id="answ"></a>
        {**********************************
        ****  Show answer options list ****
        **********************************}

        {if $WEB_APP.option_count[0] gt 0 OR $WEB_APP.option_count[1] gt 0 OR
        $WEB_APP.option_count[2] gt 0 OR $WEB_APP.option_count[3] gt 0 OR $WEB_APP.option_count[4] gt 0}
            <div class="col-xs-12">
                {if $WEB_APP.question->type eq 2}
                    <h3>{$text.txt_one_answer}</h3>
                {else}
                    <h3>{$text.txt_answers}</h3>
                {/if}
            </div>
        {/if}

        <input type="hidden" value="{$WEB_APP.question->id|escape}" name="current_question"/>


        {**********************************
        ****   Single answer options   ****
        **********************************}

        {if $WEB_APP.option_count[0] gt 0}
            {foreach from = $WEB_APP.answers item = answer name=loop}
                {if $answer->option_type eq 0}
                    <div class="col-xs-12">
                        <div class="col-xs-1 text-right">
                            {* <span class="hidden-xs"> {$smarty.foreach.loop.index+1}&nbsp;</span> *}
                            <input name="answer" type="radio" value="{$answer->number}" id="answer{$answer->number}"
                                   onclick="NextVisible()"
                                   {if $answer->number eq $WEB_APP.results_single}checked="checked"{/if}>
                        </div>
                        <div class="col-xs-11">
                            <label style="cursor:pointer; display:block;"
                                   for="answer{$answer->number}">{$answer->text_html}</label>
                        </div>
                    </div>
                {/if}
            {/foreach}
        {/if}



        {**********************************
        ****  Multiple answer options  ****
        **********************************}

        {if $WEB_APP.option_count[1] gt 0}
            {foreach from = $WEB_APP.answers item = answer name=loop}
                {if $answer->option_type eq 1}
                    <div class="col-xs-12">
                        <div class="col-xs-1 text-right">
                            {* <span class="hidden-xs ">{$smarty.foreach.loop.index+1}&nbsp;</span> *}
                            <input name="answers[{$answer->number}]" type="checkbox" value="{$answer->number}"
                                   id="answers{$answer->number}" onclick="NextVisible()"
                                    {if isset($WEB_APP.results_multiple) eq TRUE}
                                {if is_array($WEB_APP.results_multiple) eq TRUE}
                                    {if in_array($answer->number,$WEB_APP.results_multiple, FALSE) eq TRUE}checked{/if}
                                {/if}
                                    {/if}>
                        </div>
                        <div class="col-xs-11">
                            <label style="cursor:pointer; display:block;"
                                   for="answers{$answer->number}">{$answer->text_html}</label>
                        </div>
                    </div>
                {/if}
            {/foreach}
        {/if}


        {**********************************
        ****  Ordered answer options   ****
        **********************************}

        {if $WEB_APP.option_count[3] gt 0}
            <div class="row">
                <div class="col-xs-12">
                    <ul id="orderedList">
                        {if isset($WEB_APP.results_ordered) eq TRUE}
                            {foreach from = $WEB_APP.results_ordered item = answer_number name=answer_numbers}
                                {foreach from = $WEB_APP.answers item = answer name=loop}
                                    {if ($answer->option_type eq 3) AND ($answer->number eq $answer_number)}
                                        <li id="{$answer->number}" class="ui-state-default">{$answer->text_html}</li>
                                    {/if}
                                {/foreach}
                            {/foreach}
                        {else}
                            {foreach from = $WEB_APP.answers item = answer name=loop}
                                {if $answer->option_type eq 3}
                                    <li id="{$answer->number}" class="ui-state-default">{$answer->text_html}</li>
                                {/if}
                            {/foreach}
                        {/if}
                    </ul>
                </div>
            </div>
            <input type="hidden" name="sequence" id="sequence"
                    {if isset($WEB_APP.results_ordered_sequence) eq TRUE}
                value="{$WEB_APP.results_ordered_sequence}"
                    {else}
                value="{$WEB_APP.init_sequence}"
                    {/if}>
        {/if}


        {**********************************
        ****  Matched answer options   ****
        **********************************}
        {if $WEB_APP.option_count[4] gt 0}
        <div class="row">

            {**********************************
            ****      Left basket          ****
            **********************************}
            {if $WEB_APP.question->show_basket1 eq 1}
            {if $WEB_APP.question->show_basket2 eq 1}
            <div class="col-xs-3">
                {else}
                <div class="col-xs-4">
                    {/if}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title text-center">
                                {$WEB_APP.question->matched_list1_caption}&nbsp;&nbsp;<span
                                        class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <ul id="basketLeft">
                                {foreach from = $WEB_APP.answers item = answer name=loop}
                                    {if $answer->option_type eq 4}
                                        <li id="{$answer->number}" class="ui-state-default">{$answer->text_html}</li>
                                    {/if}
                                {/foreach}
                            </ul>
                        </div>
                    </div>
                </div>
                {/if}

                {**********************************
                ****      Left list            ****
                **********************************}

                {if $WEB_APP.question->show_basket2 eq 1 and $WEB_APP.question->show_basket1 eq 1}
                <div class="col-xs-3">
                    {elseif $WEB_APP.question->show_basket1 eq 1 or $WEB_APP.question->show_basket2 eq 1}
                    <div class="col-xs-4">
                        {else}
                        <div class="col-xs-6">
                            {/if}
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title text-center">{$WEB_APP.question->matched_list1_caption}</h3>
                                </div>
                                <div class="panel-body">
                                    <ul id="listLeft" {if $WEB_APP.question->show_basket1 eq 1}{*data-max-count="{$WEB_APP.left_max_count}"*}{/if}>
                                        {if isset($WEB_APP.results_matched_left) eq TRUE}
                                            {foreach from = $WEB_APP.results_matched_left item = answer_number name=answer_numbers}
                                                {foreach from = $WEB_APP.answers item = answer name=loop}
                                                    {if ($answer->option_type eq 4) AND ($answer->number eq $answer_number)}
                                                        <li id="{$answer->number}"
                                                            class="ui-state-default">{$answer->text_html}</li>
                                                    {/if}
                                                {/foreach}
                                            {/foreach}
                                        {else}
                                            {if $WEB_APP.question->show_basket1 neq 1}
                                                {foreach from = $WEB_APP.answers item = answer name=loop}
                                                    {if $answer->option_type eq 4}
                                                        <li id="{$answer->number}"
                                                            class="ui-state-default">{$answer->text_html}</li>
                                                    {/if}
                                                {/foreach}
                                            {/if}
                                        {/if}
                                    </ul>
                                </div>
                            </div>
                            {if $WEB_APP.question->show_basket1 eq 1}
                                <button class="btn btn-default btn-block btn-sm clear_list_left" type="button">{$text.txt_clear}
                                </button>
                            {/if}
                        </div>


                        {**********************************
                        ****      Right list           ****
                        **********************************}
                        {if $WEB_APP.question->show_basket2 eq 1 and $WEB_APP.question->show_basket1 eq 1}
                        <div class="col-xs-3">
                            {elseif $WEB_APP.question->show_basket1 eq 1 or $WEB_APP.question->show_basket2 eq 1}
                            <div class="col-xs-4">
                                {else}
                                <div class="col-xs-6">
                                    {/if}
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title text-center">{$WEB_APP.question->matched_list2_caption}</h3>
                                        </div>
                                        <div class="panel-body">
                                            <ul id="listRight" {if $WEB_APP.question->show_basket2 eq 1}{*data-max-count="{$WEB_APP.right_max_count}"*}{/if}>
                                                {if isset($WEB_APP.results_matched_right) eq TRUE}
                                                    {foreach from = $WEB_APP.results_matched_right item = answer_number name=answer_numbers}
                                                        {foreach from = $WEB_APP.answers item = answer name=loop}
                                                            {if ($answer->option_type eq 5) AND ($answer->number eq $answer_number)}
                                                                <li id="{$answer->number}"
                                                                    class="ui-state-default">{$answer->text_html}</li>
                                                            {/if}
                                                        {/foreach}
                                                    {/foreach}
                                                {else}
                                                    {if $WEB_APP.question->show_basket2 neq 1}
                                                        {foreach from = $WEB_APP.answers item = answer name=loop}
                                                            {if $answer->option_type eq 5}
                                                                <li id="{$answer->number}"
                                                                    class="ui-state-default">{$answer->text_html}</li>
                                                            {/if}
                                                        {/foreach}
                                                    {/if}
                                                {/if}
                                            </ul>
                                        </div>
                                    </div>
                                    {if $WEB_APP.question->show_basket2 eq 1}
                                        <button class="btn btn-default btn-block btn-sm delete clear_list_right"
                                                type="button">{$text.txt_clear}
                                        </button>
                                    {/if}
                                </div>

                                {**********************************
                                ****      Right basket         ****
                                **********************************}
                                {if $WEB_APP.question->show_basket2 eq 1}
                                {if $WEB_APP.question->show_basket1 eq 1}
                                <div class="col-xs-3">
                                    {else}
                                    <div class="col-xs-4">
                                        {/if}
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title text-center">
                                                    <span class="glyphicon glyphicon-chevron-left"
                                                          aria-hidden="true"></span>&nbsp;&nbsp;
                                                    {$WEB_APP.question->matched_list2_caption}
                                                </h3>
                                            </div>
                                            <div class="panel-body">
                                                <ul id="basketRight">
                                                    {foreach from = $WEB_APP.answers item = answer name=loop}
                                                        {if $answer->option_type eq 5}
                                                            <li id="{$answer->number}"
                                                                class="ui-state-default">{$answer->text_html}</li>
                                                        {/if}
                                                    {/foreach}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    {/if}
                                </div>

                                <input type="hidden" name="sequence_left" id="sequence_left"
                                        {if isset($WEB_APP.results_matched_left_init) eq TRUE}
                                    value="{$WEB_APP.results_matched_left_init}"
                                        {else}
                                    {if $WEB_APP.question->show_basket1 neq 1}
                                        value="{$WEB_APP.init_sequence_left}"
                                    {/if}
                                        {/if}>

                                <input type="hidden" name="sequence_right" id="sequence_right"
                                        {if isset($WEB_APP.results_matched_right_init) eq TRUE}
                                    value="{$WEB_APP.results_matched_right_init}"
                                        {else}
                                    {if $WEB_APP.question->show_basket2 neq 1}
                                        value="{$WEB_APP.init_sequence_right}"
                                    {/if}
                                        {/if}>
                                <input type="hidden" name="added_left" id="added_left" value="{$WEB_APP.added_left}">
                                <input type="hidden" name="added_right" id="added_right" value="{$WEB_APP.added_right}">

                                {/if}



                                {* Open answer options *}

                                {if $WEB_APP.option_count[2] gt 0}
                                    {foreach from = $WEB_APP.answers item = answer name=loop}
                                        {if $answer->option_type eq 2}
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label class="sr-only"
                                                           for="open_answers[{$answer->number}]">{$answer->number+1}</label>
                                                    {if $answer->rows gt 1}
                                                        {if isset($WEB_APP.results_open) eq TRUE}
                                                            <textarea {$answer->css} name="open_answers[{$answer->number}]" id="open_answers[{$answer->number}]" class="form-control" rows="{$answer->rows}" spellcheck="false" autocomplete="off" {if $answer->bidi eq 1} dir="rtl" {/if}>{$WEB_APP.results_open[$smarty.foreach.loop.index]}</textarea>
                                                        {else}
                                                            <textarea {$answer->css} name="open_answers[{$answer->number}]" id="open_answers[{$answer->number}]" class="form-control" rows="{$answer->rows}" spellcheck="false" autocomplete="off" {if $answer->bidi eq 1} dir="rtl" {/if}></textarea>
                                                        {/if}
                                                        <p id="area-count" class="text-right"></p>
                                                    {else}
                                                        <input {$answer->css} name="open_answers[{$answer->number}]"
                                                               type="text" {if $answer->max_length gt 0} maxlength="{$answer->max_length}" {/if}
                                                                {if isset($WEB_APP.results_open) eq TRUE}
                                                                    value="{$WEB_APP.results_open[$smarty.foreach.loop.index]}"
                                                                {/if}
                                                                {if $answer->bidi eq 1} dir="rtl" {/if}
                                                               id="open_answers[{$answer->number}]" class="form-control"
                                                               autocomplete="off" spellcheck="false">
                                                        <p id="input-count" class="text-right"></p>
                                                    {/if}
                                                </div>
                                            </div>
                                        {/if}
                                    {/foreach}
                                {/if}


                                <div>&nbsp;</div>
                                <div class="row">
                                    <div class="col-xs-8">
                                        <div class="btn-group">
                                            {if ($WEB_APP.is_back eq TRUE) and ($WEB_APP.current_question > 1)}
                                                <input type="button" class="btn btn-default" value="< {$text.txt_back}"
                                                       name="back_button"
                                                       onclick="return back_question()">
                                            {/if}
                                            <input type="submit" class="btn btn-primary" value="{$WEB_APP.submit_title}"
                                                   name="submit_button"
                                                   id="submit_button"
                                                    {if ($WEB_APP.results eq NULL) && ($WEB_APP.user_must_answer eq TRUE)}disabled{/if}
                                            >
                                            {if ($WEB_APP.settings.tst_allowstoskip eq 1) and ({$WEB_APP.submit_title} eq {$text.txt_answer})}
                                                <input type="button" class="btn btn-default" value="{$text.txt_skip} >"
                                                       name="skip_button"
                                                       onclick="return skip_question()">
                                            {/if}
                                        </div>
                                    </div>
                                    {if {$WEB_APP.show_break_button} eq TRUE}
                                    <div class="col-xs-4 text-right">
                                        <input type="button" class="btn btn-sm" value="{$text.txt_testing_break}"
                                               name="break_testing_button"
                                               onclick="if (confirm('{$text.txt_confirm_break_testing}')) return break_testing();">
                                    </div>
                                    {/if}
                                </div>
    </form>
    <div>&nbsp;</div>
    {if $WEB_APP.question->type eq 2}
        <script>document.getElementById('open_answers[0]').focus();</script>
    {/if}
    {if $WEB_APP.question->voice_record eq 1}
        <div id="record_buttons">
            <button id="btnRecord" onclick="startRecording(
            {$smarty.session.user_id}, {$WEB_APP.result_id}, {$WEB_APP.question_number}, {$WEB_APP.max_record_time})"
                    class="btn btn-danger">{$text.txt_record}</button>
            <button id="btnStop" onclick="stopRecording()" class="btn btn-warning" disabled>{$text.txt_stop}</button>
            {if {$WEB_APP.question->voice_record_time_limited} eq 1}
                <div>{$text.txt_voice_record_max_time}: {$WEB_APP.question->voice_record_max_time}</div>
            {/if}
        </div>
        <pre id="log" style="margin-top: 5px"></pre>
        <div id="recordingsList"></div>
    {/if}


    {if $WEB_APP.tst_showstats}
        <div class="col-sm-12">
            <div class="alert alert-info">
                {if $WEB_APP.stat_total eq TRUE}
                    <p>{$text.txt_total_questions}: <strong>{$WEB_APP.total_questions}</strong></p>
                {/if}
                {if $WEB_APP.stat_current eq TRUE}
                    <p>{$text.txt_current_question}: <strong>{$WEB_APP.current_question}</strong></p>
                {/if}
                {if $WEB_APP.stat_rights eq TRUE}
                    <p>{$text.txt_correct_answers}: <strong>{$WEB_APP.correct_answers}</strong></p>
                {/if}
                {if $WEB_APP.stat_percent_of_rights eq TRUE}
                    <p>{$text.txt_percentage_of_correct_answers}: <strong>{$WEB_APP.percent_right}%</strong></p>
                {/if}
                {if $WEB_APP.stat_time eq TRUE}
                    <p id="time_left" data-time="{$WEB_APP.full_time_left}">{$text.txt_time_left}: <strong>{$WEB_APP.time_left}</strong></p>
                {/if}
                {if $WEB_APP.stat_max_time eq TRUE}
                    <p>{$text.txt_time_limit}: <strong>{$WEB_APP.time_limit}</strong></p>
                {/if}
                {if $WEB_APP.stat_guid eq TRUE}
                    <p>{$text.txt_guid}: <strong>{$WEB_APP.test_guid}</strong></p>
                {/if}
                {if $WEB_APP.stat_test_version eq TRUE}
                    <p>{$text.txt_version}: <strong>{$WEB_APP.test_guid}</strong></p>
                {/if}
            </div>
        </div>
    {/if}
{/if}