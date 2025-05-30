<?php
    //$param1 = question id and $param2 = quiz id
    $question_details = $this->lms_model->get_quiz_question_by_id($param1)->row_array();
    if ($question_details['options'] != "" || $question_details['options'] != null) {
        $options = json_decode($question_details['options']);
    } else {
        $options = array();
    }
    if ($question_details['correct_answers'] != "" || $question_details['correct_answers'] != null) {
        $correct_answers = json_decode($question_details['correct_answers']);
    } else {
        $correct_answers = array();
    }
?>
<form action="<?php echo site_url('addons/courses/quiz_questions/'.$param2.'/edit/'.$param1); ?>" method="post" id="mcq_form">
    <input type="hidden" name="question_type" value="mcq">
    <!-- Hidden field for CSRF token -->
    <input type="hidden" id="csrf_token_field" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    <!-- Hidden fields for JavaScript -->
    <input type="hidden" id="base_url" value="<?php echo site_url(); ?>">
    <input type="hidden" id="csrf_name" value="<?php echo $this->security->get_csrf_token_name(); ?>">
    <input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <div class="form-group mb-2">
        <label for="title"><?php echo get_phrase('question_title'); ?></label>
        <input class="form-control" type="text" name="title" id="title" value="<?php echo $question_details['title']; ?>" required>
    </div>
    <div class="form-group mb-2" id="multiple_choice_question">
        <label for="number_of_options"><?php echo get_phrase('number_of_options'); ?></label>
        <div class="input-group">
            <input type="number" class="form-control" name="number_of_options" id="number_of_options" data-validate="required" data-message-required="Value Required" min="0" oninput="showOptions(jQuery(this).val(), 'quiz')" value="<?php echo $question_details['number_of_options']; ?>">
        </div>
    </div>
    <?php for ($i = 0; $i < $question_details['number_of_options']; $i++): ?>
        <div class="form-group mb-2 options">
            <label><?php echo get_phrase('option').' '.($i+1); ?></label>
            <div class="input-group">
                <input type="text" class="form-control" name="options[]" id="option_<?php echo $i; ?>" placeholder="<?php echo get_phrase('option_').$i; ?>" required value="<?php echo $options[$i]; ?>">
                <div class="input-group-append">
                    <span class="input-group-text d-block">
                        <input type="checkbox" name="correct_answers[]" value="<?php echo ($i+1); ?>" <?php if (in_array(($i+1), $correct_answers)) echo 'checked'; ?>>
                    </span>
                </div>
            </div>
        </div>
    <?php endfor; ?>
    <div class="text-center">
        <button class="btn btn-success" id="submitButton" type="button" name="button" data-dismiss="modal"><?php echo get_phrase('submit'); ?></button>
    </div>
</form>

<script type="text/javascript">
    $('#submitButton').click(function(event) {
        var isValid = true;
        var messages = [];

        if ($('#title').val().trim() === "") {
            messages.push('<?php echo get_phrase('the_question_title_is_required'); ?>');
        }

        if ($('#number_of_options').val() <= 0) {
            messages.push('<?php echo get_phrase('number_of_options_must_be_greater_than_zero'); ?>');
        }

        let atLeastOneChecked = false;
        $('input[name="correct_answers[]"]').each(function() {
            if ($(this).is(':checked')) {
                atLeastOneChecked = true;
            }
        });
        if (!atLeastOneChecked) {
            messages.push('<?php echo get_phrase('You_must_select_at_least_one_correct_answer'); ?>');
        }

        if (messages.length > 0) {
            confirmModal_alert(messages.join(' , '));
            return;
        }

        if (isValid) {
            $.ajax({
                url: '<?php echo site_url('addons/courses/quiz_questions/'.$param2.'/edit/'.$param1); ?>',
                type: 'post',
                data: $('form#mcq_form').serialize(),
                dataType: 'json',
                success: function(response) {
                    var newCsrfName = response.csrf.csrfName;
                    var newCsrfHash = response.csrf.csrfHash;
                    $('#csrf_name').val(newCsrfName);
                    $('#csrf_hash').val(newCsrfHash);
                    $('#csrf_token_field').attr('name', newCsrfName).val(newCsrfHash);
                    $('input[name="' + newCsrfName + '"]').val(newCsrfHash);

                    if (response.html == 1) {
                        success_notify('<?php echo get_phrase('question_has_been_updated'); ?>');
                        $('#scrollable-modal').modal('hide');
                        setTimeout(function() {
                            largeModal('<?php echo site_url('modal/popup/academy/quiz_questions/'.$param2); ?>', '<?php echo get_phrase('manage_quiz_questions'); ?>');
                        }, 500);
                    } else {
                        error_notify('<?php echo get_phrase('no_options_can_be_blank_and_there_has_to_be_atleast_one_answer'); ?>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors de la mise à jour de la question : ', error, xhr.status, xhr.responseText);
                    alert('Une erreur est survenue lors de la mise à jour de la question.');
                }
            });
        }
    });
</script>