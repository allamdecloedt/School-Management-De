<?php
    // $param1 = question id and $param2 = exam id
    $question_details = $this->lms_model->get_exam_question_by_id($param1)->row_array();
    if ($question_details['options'] != "" && $question_details['options'] != null) {
        $options = json_decode($question_details['options']);
    } else {
        $options = array();
    }
    if ($question_details['correct_answers'] != "" && $question_details['correct_answers'] != null) {
        $correct_answers = json_decode($question_details['correct_answers']);
    } else {
        $correct_answers = array();
    }
?>
<form action="<?php echo site_url('addons/courses/exam_questions/'.$param2.'/edit/'.$param1); ?>" method="post" id="mcq_form">
    <input type="hidden" name="csrf_token_name" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <input type="hidden" id="base_url" value="<?php echo site_url(); ?>">
    <input type="hidden" name="question_type" value="mcq">
    <div class="form-group mb-2">
        <label for="title"><?php echo get_phrase('question_title'); ?></label>
        <input class="form-control" type="text" name="title" id="title" value="<?php echo $question_details['title']; ?>" required>
    </div>
    <div class="form-group mb-2" id="multiple_choice_question">
        <label for="number_of_options"><?php echo get_phrase('number_of_options'); ?></label>
        <div class="input-group">
            <input type="number" class="form-control" name="number_of_options" id="number_of_options" data-validate="required" data-message-required="Value Required" min="0" max="20" oninput="showOptions(jQuery(this).val(), 'exam')" value="<?php echo $question_details['number_of_options']; ?>">
        </div>
    </div>
    <?php for ($i = 0; $i < $question_details['number_of_options']; $i++): ?>
        <div class="form-group mb-2 options">
            <label><?php echo get_phrase('option').' '.($i+1); ?></label>
            <div class="input-group">
                <input type="text" class="form-control" name="options[]" id="option_<?php echo $i; ?>" placeholder="<?php echo get_phrase('option_').($i+1); ?>" required value="<?php echo $options[$i]; ?>">
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

<!-- Add CSRF meta tags as fallback -->
<meta name="csrf-name" content="csrf_token_name">
<meta name="csrf-token" content="<?php echo $this->security->get_csrf_hash(); ?>">

<script type="text/javascript">
    $('#submitButton').click(function(event) {
        var isValid = true;
        var messages = [];

        // Validate question title
        if ($('#title').val().trim() === "") {
            messages.push('<?php echo get_phrase('the_question_title_is_required'); ?>');
        }

        // Validate number of options
        if ($('#number_of_options').val() <= 0) {
            messages.push('<?php echo get_phrase('number_of_options_must_be_greater_than_zero'); ?>');
        }

        // Validate at least one correct answer
        let atLeastOneChecked = false;
        $('input[name="correct_answers[]"]').each(function() {
            if ($(this).is(':checked')) {
                atLeastOneChecked = true;
            }
        });
        if (!atLeastOneChecked) {
            messages.push('<?php echo get_phrase('You_must_select_at_least_one_correct_answer'); ?>');
        }

        // Display errors if any
        if (messages.length > 0) {
            confirmModal_alert(messages.join(' , '));
            return;
        }

        if (isValid) {
            $.ajax({
                url: '<?php echo site_url('addons/courses/exam_questions/'.$param2.'/edit/'.$param1); ?>',
                type: 'post',
                data: $('form#mcq_form').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.html == 1) {
                        success_notify('<?php echo get_phrase('question_has_been_updated'); ?>');
                        $('#scrollable-modal').modal('hide');
                        // Update CSRF token
                        if (response.csrf) {
                            $('input[name="' + response.csrf.csrfName + '"]').val(response.csrf.csrfHash);
                            $('meta[name="csrf-token"]').attr('content', response.csrf.csrfHash);
                        }
                        // Reload question list
                        setTimeout(function() {
                            largeModal('<?php echo site_url('modal/popup/academy/exam_questions/'.$param2); ?>', '<?php echo get_phrase('manage_exam_questions'); ?>');
                        }, 500);
                    } else {
                        error_notify('<?php echo get_phrase('no_options_can_be_blank_and_there_has_to_be_atleast_one_answer'); ?>');
                    }
                },
                error: function(xhr, status, error) {
                    var errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : xhr.responseText;
                    error_notify('An error occurred while updating the question: ' + errorMsg);
                }
            });
        }
    });
</script>