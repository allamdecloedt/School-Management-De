function showOptions(number_of_options, context = 'quiz') {
    var baseUrl = $('#base_url').val();

    if (number_of_options < 0 || number_of_options > 20) {
        showNotification('error', 'Number of options must be between 0 and 20.');
        return;
    }

    // Get CSRF token
    var csrfName = $('input[name="csrf_token_name"]').attr('name'); // Adjust to match your CSRF token name
    var csrfHash = $('input[name="csrf_token_name"]').val();

    // Fallback: Check for CSRF token in meta tag
    if (!csrfName || !csrfHash) {
        csrfName = $('meta[name="csrf-name"]').attr('content');
        csrfHash = $('meta[name="csrf-token"]').attr('content');
    }


    if (!csrfName || !csrfHash) {
        showNotification('error', 'CSRF token not found. Please refresh the page.');
        return;
    }

    var existingOptions = [];
    jQuery('.options').each(function(index) {
        var optionValue = jQuery(this).find('input[type="text"]').val();
        var isChecked = jQuery(this).find('input[type="checkbox"]').is(':checked');
        existingOptions.push({ value: optionValue, checked: isChecked });
    });

    var url = (context === 'exam') 
        ? baseUrl + 'addons/courses/manage_exam_multiple_choices_options'
        : baseUrl + 'addons/courses/manage_multiple_choices_options';

    $.ajax({
        type: "POST",
        url: url,
        data: {
            number_of_options: number_of_options,
            [csrfName]: csrfHash
        },
        dataType: 'json',
        success: function(response) {
            if (response.html) {
                try {
                    jQuery('.options').remove();
                    jQuery('#multiple_choice_question').after(response.html);
                    jQuery('.options').each(function(index) {
                        if (existingOptions[index]) {
                            jQuery(this).find('input[type="text"]').val(existingOptions[index].value);
                            jQuery(this).find('input[type="checkbox"]').prop('checked', existingOptions[index].checked);
                        }
                    });
                    // Update CSRF token
                    if (response.csrf) {
                        $('input[name="' + response.csrf.csrfName + '"]').val(response.csrf.csrfHash);
                        $('meta[name="csrf-name"]').attr('content', response.csrf.csrfName);
                        $('meta[name="csrf-token"]').attr('content', response.csrf.csrfHash);
                    }
                    showNotification('success', 'Options generated successfully');
                } catch (e) {
                    console.error('Error appending HTML:', e);
                    showNotification('error', 'Invalid HTML received from server');
                }
            } else {
                showNotification('error', 'Failed to generate options: No HTML received');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', xhr.status, xhr.responseText);
            showNotification('error', 'Failed to generate options: ' + (xhr.responseJSON?.message || xhr.responseText));
        }
    });
}

function showNotification(type, message) {
    // Configure Toastr options
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 3000,
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut'
    };

    let title;
    switch (type.toLowerCase()) {
        case 'success':
            title = 'Success';
            break;
        case 'error':
            title = 'Error';
            break;
        case 'warning':
            title = 'Warning';
            break;
        case 'info':
            title = 'Info';
            break;
        default:
            title = 'Notification';
    }
    toastr[type](message, title);
}

