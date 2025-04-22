function showOptions(number_of_options, context = 'quiz') {
    var csrfName = $('#csrf_name').val();
    var csrfHash = $('#csrf_hash').val();

    // Vérifiez si les champs CSRF existent
    if (!csrfName || !csrfHash) {
        console.error('Champs CSRF manquants ou non initialisés');
        alert('Erreur : Les jetons CSRF ne sont pas disponibles. Veuillez recharger la page.');
        return;
    }

    var existingOptions = [];
    jQuery('.options').each(function(index) {
        var optionValue = jQuery(this).find('input[type="text"]').val();
        var isChecked = jQuery(this).find('input[type="checkbox"]').is(':checked');
        existingOptions.push({ value: optionValue, checked: isChecked });
    });

    var url = (context === 'exam') 
        ? $('#base_url').val() + 'addons/courses/manage_exam_multiple_choices_options'
        : $('#base_url').val() + 'addons/courses/manage_multiple_choices_options';

    $.ajax({
        type: "POST",
        url: url,
        data: { number_of_options: number_of_options, [csrfName]: csrfHash },
        dataType: 'json',
        success: function(response) {
            jQuery('.options').remove();
            jQuery('#multiple_choice_question').after(response.html);
            // Mise à jour du jeton CSRF
            var newCsrfName = response.csrf.csrfName;
            var newCsrfHash = response.csrf.csrfHash;
            $('#csrf_name').val(newCsrfName);
            $('#csrf_hash').val(newCsrfHash);
            $('#csrf_token_field').attr('name', newCsrfName).val(newCsrfHash);
            // Mettre à jour tous les formulaires avec le nouveau jeton CSRF
            $('input[name="' + newCsrfName + '"]').val(newCsrfHash);
            jQuery('.options').each(function(index) {
                if (existingOptions[index]) {
                    jQuery(this).find('input[type="text"]').val(existingOptions[index].value);
                    jQuery(this).find('input[type="checkbox"]').prop('checked', existingOptions[index].checked);
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Erreur lors de la génération des options : ', error);
            alert('Une erreur est survenue lors de la génération des options. Veuillez recharger la page.');
        }
    });
}