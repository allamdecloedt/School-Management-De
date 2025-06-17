<form action="" method="" id="mcq_form">
    <input type="hidden" name="question_type" value="mcq">
    <!-- Add CSRF token -->
    <input type="hidden" name="csrf_token_name" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <!-- Hidden fields for JavaScript -->
    <input type="hidden" id="base_url" value="<?php echo site_url(); ?>">
    <div class="form-group mb-2">
        <label for="title"><?php echo get_phrase('question_title'); ?></label>
        <input class="form-control" type="text" name="title" id="title">
    </div>
    <div class="form-group mb-2" id="multiple_choice_question">
        <label for="number_of_options"><?php echo get_phrase('number_of_options'); ?></label>
        <div class="input-group">
            <input type="number" class="form-control" name="number_of_options" id="number_of_options" data-validate="required" data-message-required="Value Required" oninput="showOptions(jQuery(this).val(), 'exam')" min="0" max="20">
        </div>
    </div>
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
                url: '<?php echo site_url('addons/courses/exam_questions/'.$param1.'/add'); ?>',
                type: 'post',
                data: $('form#mcq_form').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.html == 1) {
                        success_notify('<?php echo get_phrase('question_has_been_added'); ?>');
                        $('#scrollable-modal').modal('hide');
                        // Update CSRF token
                        if (response.csrf) {
                            $('input[name="' + response.csrf.csrfName + '"]').val(response.csrf.csrfHash);
                            $('meta[name="csrf-token"]').attr('content', response.csrf.csrfHash);
                        }
                        // Reload question list
                        setTimeout(function() {
                            largeModal('<?php echo site_url('modal/popup/academy/exam_questions/'.$param1); ?>', '<?php echo get_phrase('manage_exam_questions'); ?>');
                        }, 500);
                    } else {
                        error_notify('<?php echo get_phrase('no_options_can_be_blank_and_there_has_to_be_atleast_one_answer'); ?>');
                    }
                },
                error: function(xhr, status, error) {
                    var errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : xhr.responseText;
                    error_notify('An error occurred while adding the question: ' + errorMsg);
                }
            });
        }
    });
</script>