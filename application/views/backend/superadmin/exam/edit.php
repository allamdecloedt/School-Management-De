

<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/edit-design-button.css">
<?php 
$exams = $this->db->get_where('exams', array('id' => $param1))->result_array(); 

$school_id = school_id();
$classes = $this->db->get_where('classes', array('school_id' => $school_id))->result_array();
?>

<?php foreach($exams as $exam): ?>
<form method="POST" class="d-block" action="<?php echo route('exam/update/'.$param1); ?>" id="examEditForm">

    <div class="form-row">

        <div class="form-group mb-1">
            <label for="exam_name"><?php echo get_phrase('exam_name'); ?><span class="required"> * </span></label>
            <input type="text" class="form-control" id="exam_name" name="exam_name" value="<?php echo html_escape($exam['name']); ?>" required>
            <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_exam_name'); ?></small>
        </div>
        <div class="form-group mb-1">
            <label for="starting_date"><?php echo get_phrase('date'); ?><span class="required"> * </span></label>
            <input type="datetime-local" class="form-control" id="starting_date" name="starting_date" value="<?php echo date('Y-m-d\TH:i', $exam['starting_date']); ?>" required>
            <small id="date_help" class="form-text text-muted"><?php echo get_phrase('provide_date_and_time'); ?></small>
        </div>
        <div class="form-group mb-1">
            <label for="modal_class_id"><?php echo get_phrase('class'); ?><span class="required"> * </span></label>
            <select class="form-control" id="modal_class_id" name="class_id" onchange="getSections(this.value)" required>
                <option value=""><?php echo get_phrase('select_class'); ?></option>
                <?php foreach ($classes as $class): ?>
                    <option value="<?php echo html_escape($class['id']); ?>" <?php echo $class['id'] == $exam['class_id'] ? 'selected' : ''; ?>>
                        <?php echo html_escape($class['name']); ?>
                    </option>
                <?php endforeach; ?>
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
            <button class="btn btn-block btn-primary btn-l px-4 " id="update-btn" type="submit"><i class="mdi mdi-account-check"></i><?php echo get_phrase('update_exam'); ?></button>
        </div>

    </div>
</form>
<?php endforeach; ?>

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
// Function to load sections dynamically
function getSections(class_id) {
    if (!class_id) {
        $('#modal_section_id').html('<option value=""><?php echo get_phrase('select_section'); ?></option>');
        return;
    }
    $.ajax({
        url: '<?php echo site_url('superadmin/get_sections_by_class'); ?>',
        type: 'POST',
        data: { class_id: class_id },
        dataType: 'json',
        success: function(response) {
            $('#modal_section_id').html('<option value=""><?php echo get_phrase('select_section'); ?></option>');
            if (response.sections && response.sections.length > 0) {
                $.each(response.sections, function(index, section) {
                    $('#modal_section_id').append('<option value="' + section.id + '">' + section.name + '</option>');
                });
                var currentSectionId = '<?php echo $exam['section_id']; ?>';
                if (currentSectionId) {
                    $('#modal_section_id').val(currentSectionId);
                }
            } else {
                showNotification('warning', '<?php echo get_phrase('no_sections_found'); ?>');
            }
        },
        error: function(xhr, status, error) {
            console.error('getSections error:', status, error, xhr.responseText);
            showNotification('error', '<?php echo get_phrase('failed_to_fetch_sections'); ?>');
        }
    });
}


// Function to show notifications
function showNotification(type, message) {
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 5000,
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut'
    };
    if (type === 'success') {
        toastr.success(message);
    } else if (type === 'error') {
        toastr.error(message);
    } else if (type === 'warning') {
        toastr.warning(message);
    }
}

$(document).ready(function() {
    // Prevent duplicate event handlers
    $('#examEditForm').off('submit');
    $('#right-modal').off('shown.bs.modal.edit hidden.bs.modal.edit');

    // Load sections when modal is shown
    $('#right-modal').on('shown.bs.modal.edit', function() {
        const class_id = $('#modal_class_id').val();
        if (class_id) {
            getSections(class_id);
        }
    });

    // Clean up when modal is hidden
    $('#right-modal').on('hidden.bs.modal.edit', function() {
        const $form = $('#examEditForm');
        if ($form.length) {
            $form[0].reset();
        }
        const $sectionSelect = $('#modal_section_id');
        if ($sectionSelect.length) {
            $sectionSelect.html('<option value=""><?php echo get_phrase('select_section'); ?></option>');

        }
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        $('body').focus();
    });

    // Handle form submission
    let isSubmitting = false;
    $('#examEditForm').on('submit', function(e) {
        e.preventDefault();
        if (isSubmitting) {
            return;
        }
        isSubmitting = true;
        const $submitBtn = $(this).find('button[type="submit"]');
        $submitBtn.prop('disabled', true).text('<?php echo get_phrase('updating'); ?>...');

        const formData = $(this).serialize() + '&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>';
        $.ajax({
            url: '<?php echo route('exam/update/' . $param1); ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                isSubmitting = false;
                $submitBtn.prop('disabled', false).text('<?php echo get_phrase('update_exam'); ?>');
                if (response.status === true) {
                    showNotification('success', response.message || '<?php echo get_phrase('exam_updated_successfully'); ?>');
                    $('#right-modal').modal('hide');
                    // Update table and calendar after modal is fully hidden
                    $('#right-modal').one('hidden.bs.modal.edit', function() {
                        if (typeof window.updateExamTableAndCalendar === 'function') {
                            window.updateExamTableAndCalendar(); // Refresh with all exams
                        } else {
                            console.error('updateExamTableAndCalendar not found');
                            showNotification('error', '<?php echo get_phrase('update_function_not_found'); ?>');
                        }
                    });
                } else {
                    showNotification('error', response.message || '<?php echo get_phrase('failed_to_update_exam'); ?>');
                }
            },
            error: function(xhr, status, error) {
                isSubmitting = false;
                $submitBtn.prop('disabled', false).text('<?php echo get_phrase('update_exam'); ?>');
                console.error('Update AJAX error:', status, error, xhr.responseText);
                showNotification('error', '<?php echo get_phrase('failed_to_update_exam'); ?>');
            }
        });

    });
  
   // Mettre à jour le jeton CSRF après la soumission
       $.ajax({
        url: '<?= site_url('superadmin/get_csrf_token'); ?>',
        type: 'GET',
        success: function(raw) {
          var d = JSON.parse(raw);
          $('#csrf_token').val(d.csrf_hash);
        }
      });
    }
  // ——— Binding : on utilise ajaxSubmit avec showAllExams ———
  $(".ajaxForm").submit(function(e) {
    ajaxSubmit(e, $(this), showAllExams);
  });

    // Initialize jQuery validation
    $('#examEditForm').validate({
        rules: {
            exam_name: { required: true, minlength: 2 },
            starting_date: { required: true },
            class_id: { required: true },
            section_id: { required: true }
        },
        messages: {
            exam_name: {
                required: '<?php echo get_phrase('exam_name_is_required'); ?>',
                minlength: '<?php echo get_phrase('exam_name_must_be_at_least_2_characters'); ?>'
            },
            starting_date: { required: '<?php echo get_phrase('date_is_required'); ?>' },
            class_id: { required: '<?php echo get_phrase('class_is_required'); ?>' },
            section_id: { required: '<?php echo get_phrase('section_is_required'); ?>' }
        }
    });
});

</script>