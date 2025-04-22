<form action="" method="" id="mcq_form">
    <!-- Hidden field for CSRF token -->
    <input type="hidden" id="csrf_token_field" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    <input type="hidden" name="question_type" value="mcq">
    <!-- Hidden fields for JavaScript -->
    <input type="hidden" id="base_url" value="<?php echo site_url(); ?>">
    <input type="hidden" id="csrf_name" value="<?php echo $this->security->get_csrf_token_name(); ?>">
    <input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <div class="form-group mb-2">
        <label for="title"><?php echo get_phrase('question_title'); ?></label>
        <input class="form-control" type="text" name="title" id="title">
    </div>
    <div class="form-group mb-2" id="multiple_choice_question">
        <label for="number_of_options"><?php echo get_phrase('number_of_options'); ?></label>
        <div class="input-group">
            <input type="number" class="form-control" name="number_of_options" id="number_of_options" data-validate="required" data-message-required="Value Required" oninput="showOptions(jQuery(this).val(), 'quiz')" min="0" max="20">
        </div>
    </div>
    <div class="text-center">
        <button class="btn btn-success" id="submitButton" type="button" name="button"><?php echo get_phrase('submit'); ?></button>
    </div>
</form>

<script type="text/javascript">
    if (typeof jQuery === 'undefined') {
        console.error('jQuery n\'est pas chargé.');
    }

    $('#submitButton').click(function(event) {
    event.preventDefault(); // Prevent default form submission

    var isValid = true;
    var messages = [];

    // Validation du titre
    if ($('#title').val().trim() === "") {
        messages.push('<?php echo get_phrase('the_question_title_is_required'); ?>');
        isValid = false;
    }

    // Validation du nombre d'options
    if ($('#number_of_options').val() <= 0) {
        messages.push('<?php echo get_phrase('number_of_options_must_be_greater_than_zero'); ?>');
        isValid = false;
    }

    // Validation de la présence d'au moins une réponse correcte
    let atLeastOneChecked = false;
    $('input[name="correct_answers[]"]').each(function() {
        if ($(this).is(':checked')) {
            atLeastOneChecked = true;
        }
    });
    if (!atLeastOneChecked) {
        messages.push('<?php echo get_phrase('You_must_select_at_least_one_correct_answer'); ?>');
        isValid = false;
    }

    // Afficher les erreurs si présentes
    if (messages.length > 0) {
        confirmModal_alert(messages.join(' , '));
        return;
    }

    if (isValid) {
        // Récupérer le jeton CSRF dynamiquement avant la soumission
        $.ajax({
            url: '<?php echo site_url('addons/courses/get_csrf_token'); ?>',
            type: 'GET',
            dataType: 'json',
            success: function(csrfResponse) {
                // Mettre à jour les champs CSRF
                $('#csrf_name').val(csrfResponse.csrfName);
                $('#csrf_hash').val(csrfResponse.csrfHash);
                $('#csrf_token_field').attr('name', csrfResponse.csrfName).val(csrfResponse.csrfHash);
                $('input[name="' + csrfResponse.csrfName + '"]').val(csrfResponse.csrfHash);

                // Envoyer la requête d'ajout
                $.ajax({
                    url: '<?php echo site_url('addons/courses/quiz_questions/'.$param1.'/add'); ?>',
                    type: 'POST',
                    data: $('#mcq_form').serialize(),
                    dataType: 'json',
                    success: function(response) {
                        // Mettre à jour le jeton CSRF
                        if (response.csrf) {
                            $('#csrf_name').val(response.csrf.csrfName);
                            $('#csrf_hash').val(response.csrf.csrfHash);
                            $('#csrf_token_field').attr('name', response.csrf.csrfName).val(response.csrf.csrfHash);
                            $('input[name="' + response.csrf.csrfName + '"]').val(response.csrf.csrfHash);
                        }

                        if (response.html == 1) {
                            success_notify('<?php echo get_phrase('question_has_been_added'); ?>');
                            $('#scrollable-modal').modal('hide');
                            setTimeout(function() {
                                largeModal('<?php echo site_url('modal/popup/academy/quiz_questions/'.$param1); ?>', '<?php echo get_phrase('manage_quiz_questions'); ?>');
                            }, 500);
                        } else {
                            error_notify(response.message || '<?php echo get_phrase('no_options_can_be_blank_and_there_has_to_be_atleast_one_answer'); ?>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Erreur lors de l\'ajout de la question:', {
                            status: status,
                            error: error,
                            responseText: xhr.responseText
                        });
                        error_notify('Une erreur est survenue lors de l\'ajout de la question : ' + (xhr.responseJSON?.message || xhr.statusText));
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Erreur lors de la récupération du jeton CSRF:', {
                    status: status,
                    error: error,
                    responseText: xhr.responseText
                });
                error_notify('Impossible de récupérer le jeton CSRF. Veuillez recharger la page.');
            }
        });
    }
});
</script>