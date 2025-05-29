<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/edit-design-button.css">
<form method="POST" class="d-block" action="<?php echo route('exam/create'); ?>" id="examCreateForm">

    <div class="form-row">
        <div class="form-group mb-1">
            <label for="exam_name"><?php echo get_phrase('exam_name'); ?><span class="required"> * </span></label>
            <input type="text" class="form-control" id="exam_name" name="exam_name" placeholder="name" required>
            <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_exam_name'); ?></small>
        </div>
        <div class="form-group mb-1">
            <label for="starting_date"><?php echo get_phrase('date'); ?><span class="required"> * </span></label>
            <input type="datetime-local" class="form-control" id="starting_date" name="starting_date" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
            <small id="date_help" class="form-text text-muted"><?php echo get_phrase('provide_date_and_time'); ?></small>
        </div>
        <div class="form-group mb-1">
            <label for="modal_class_id"><?php echo get_phrase('class'); ?><span class="required"> * </span></label>
            <select class="form-control" id="modal_class_id" name="class_id" onchange="getSections(this.value)" required>
                <option value=""><?php echo get_phrase('select_class'); ?></option>
                <?php 
                $school_id = school_id();
                $this->db->where('school_id', $school_id);
                $classes = $this->crud_model->get_classes()->result_array();
                if (empty($classes)) {
                    echo '<option value="">' . get_phrase('no_classes_found') . '</option>';
                } else {
                    foreach ($classes as $class): 
                ?>
                    <option value="<?php echo html_escape($class['id']); ?>">
                        <?php echo html_escape($class['name']); ?>
                    </option>
                <?php 
                    endforeach; 
                }
                ?>
            </select>
            <small id="class_help" class="form-text text-muted"><?php echo get_phrase('select_a_class'); ?></small>
        </div>
        <div class="form-group mb-1">
            <label for="modal_section_id"><?php echo get_phrase('section'); ?><span class="required"> * </span></label>
            <select class="form-control" id="modal_section_id" name="section_id" required>
                <option value=""><?php echo get_phrase('select_section'); ?></option>
            </select>
            <small id="section_help" class="form-text text-muted"><?php echo get_phrase('select_a_section'); ?></small>
        </div>
        <div class="form-group col-md-12">
            <button class="btn btn-primary btn-l px-4" id="update-btn" type="submit"><i class="mdi mdi-plus"></i><?php echo get_phrase('create_exam'); ?></button>
        </div>
    </div>
</form>

<style>
/* Personnaliser la notification Toastr de succès */
#toast-container .toast-success {
    background-color: #28a745;
    opacity: 1 !important;
    color: #fff;
    border-radius: 5px;
    font-size: 14px;
}
#toast-container .toast-success .toast-message,
#toast-container .toast-success .toast-title {
    color: #fff;
}
</style>

<script>
$(document).ready(function() {
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 5000,
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut'
    };

    // Fonction spécifique pour charger les sections dans le modal de création
    window.getSections = function(class_id, selectedSectionId = '') {

        if (class_id) {
            $.ajax({
                url: '<?php echo site_url('superadmin/get_sections_by_class'); ?>',
                type: 'POST',
                data: { class_id: class_id },
                success: function(response) {
                    var data = JSON.parse(response);
                    var sectionSelect = $('#modal_section_id');
                    sectionSelect.html('<option value=""><?php echo get_phrase('select_section'); ?></option>');
                    
                    if (data.sections && data.sections.length > 0) {
                        $.each(data.sections, function(index, section) {
                            var isSelected = (section.id == selectedSectionId) ? 'selected' : '';
                            sectionSelect.append('<option value="' + section.id + '" ' + isSelected + '>' + section.name + '</option>');
                        });
                    } else {
                        console.warn('No sections found for create:', data.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Create Sections AJAX Error:', status, error);
                    console.error('Response Text:', xhr.responseText);
                    $('#modal_section_id').html('<option value=""><?php echo get_phrase('select_section'); ?></option>');
                }
            });
        } else {
            $('#modal_section_id').html('<option value=""><?php echo get_phrase('select_section'); ?></option>');
        }
    };

    let isSubmitting = false;

    // Gestionnaire pour le formulaire de création
    $('#examCreateForm').off('submit').on('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();

        if (isSubmitting) {
            return;
        }

        isSubmitting = true;
        const $submitBtn = $(this).find('button[type="submit"]');
        $submitBtn.prop('disabled', true).text('<?php echo get_phrase('creating'); ?>...');

        const formData = {
            exam_name: $('#exam_name').val(),
            starting_date: $('#starting_date').val(),
            class_id: $('#modal_class_id').val(),
            section_id: $('#modal_section_id').val(),
            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
        };

        $.ajax({
            url: '<?php echo route('exam/create'); ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                isSubmitting = false;
                $submitBtn.prop('disabled', false).text('<?php echo get_phrase('create_exam'); ?>');

                try {
                    if (response.status === true) {
                        showNotification('success', response.message || '<?php echo get_phrase('exam_created_successfully'); ?>');
                        $('#right-modal').modal('hide');
                        $('#examCreateForm')[0].reset();
                        $('#modal_section_id').html('<option value=""><?php echo get_phrase('select_section'); ?></option>');

                        // Mettre à jour la table et le calendrier avec tous les exams
                        if (typeof window.updateExamTableAndCalendar === 'function') {
                            window.updateExamTableAndCalendar(); // Appel sans class_id pour charger tous les exams
                        } else {
                            console.error('updateExamTableAndCalendar not found');
                            showNotification('error', '<?php echo get_phrase('update_function_not_found'); ?>');
                        }
                    } else {
                        showNotification('error', response.message || '<?php echo get_phrase('failed_to_create_exam'); ?>');
                    }
                } catch (e) {
                    console.error('Erreur de traitement de la réponse:', e, response);
                    showNotification('error', '<?php echo get_phrase('invalid_server_response'); ?>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Exam create AJAX error:', status, error, xhr.responseText);
                isSubmitting = false;
                $submitBtn.prop('disabled', false).text('<?php echo get_phrase('create_exam'); ?>');
                showNotification('error', '<?php echo get_phrase('failed_to_create_exam'); ?>: ' + xhr.status + ' ' + error);
            }
        });
    });

    // Gestionnaire pour l'ouverture du modal, spécifique au formulaire de création
    $('#right-modal').on('shown.bs.modal.create', function() {
        if ($('#examCreateForm').length === 0) {
            return; // Ne pas exécuter si ce n'est pas le formulaire de création
        }
        const class_id = $('#modal_class_id').val();
        if (class_id) {
            getSections(class_id);
        }
    });

    // Gestionnaire pour la fermeture du modal, spécifique au formulaire de création
    $('#right-modal').on('hidden.bs.modal.create', function() {
        if ($('#examCreateForm').length === 0) {
            return; // Ne pas exécuter si ce n'est pas le formulaire de création
        }
        const $form = $('#examCreateForm');
        if ($form.length > 0) {
            $form[0].reset();
            $('#modal_section_id').html('<option value=""><?php echo get_phrase('select_section'); ?></option>');
            $form.find('button[type="submit"]').blur();
        }
        $('body').focus();
    });
});
</script>