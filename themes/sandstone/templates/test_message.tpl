{if isset($WEB_APP.wrong_message_title) && ($WEB_APP.wrong_message_title  neq "")}
    <div id="wrongAnswerMessage" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header  alert alert-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{$WEB_APP.wrong_message_title}</h4>
                </div>
                {if isset($WEB_APP.explanation) && ($WEB_APP.explanation  neq "")}
                    <div class="modal-body">
                        <div>{$WEB_APP.explanation}</div>
                    </div>
                    <hr>
                {/if}
                <div class="modal-header" id="btn_moreinfo">
                    <button type="button" class="btn btn-info" id="btn_show"
                            onclick="document.getElementById('moreinfo').style.display='block';
                                     document.getElementById('btn_moreinfo').style.display='none'">{$text.txt_my_answer}
                    </button>
                </div>
                <div id="moreinfo" style="display : none">
                    <div class="modal-header">
                        <h3>{$text.txt_question}</h3>
                    </div>
                    <div class="modal-body">
                        <div>{$WEB_APP.user_question}</div>
                    </div>
                    {if isset($WEB_APP.user_answer) && ($WEB_APP.user_answer neq "")}
                    <hr>
                    <div class="modal-header">
                        <h3>{$text.txt_rep_answer}</h3>
                    </div>
                    <div class="modal-body">
                        <div>{$WEB_APP.user_answer}</div>
                    </div>
                    {/if}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="btn_close">{$text.txt_close}</button>
                </div>
            </div>
        </div>
    </div>
    <script>$('#wrongAnswerMessage').on('shown.bs.modal', function () {
            $('#btn_close').focus();
        })  </script>
{/if}

{if isset($WEB_APP.right_message_title) && ($WEB_APP.right_message_title neq "")}
    <div id="rightAnswerMessage" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header alert alert-success">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{$WEB_APP.right_message_title}</h4>
                </div>
                <div class="modal-body">
                    <div>{$WEB_APP.explanation}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="btn_close">{$text.txt_close}</button>
                </div>
            </div>
        </div>
    </div>
    <script>$('#rightAnswerMessage').on('shown.bs.modal', function () {
            $('#btn_close').focus();
        })  </script>
{/if}