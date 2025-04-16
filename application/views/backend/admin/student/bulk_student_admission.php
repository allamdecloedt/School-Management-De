<?php $school_id = school_id(); ?>
<form method="POST" class="col-md-12 ajaxForm" action="<?php echo route('student/create_bulk_student'); ?>" id = "student_admission_form">
    <!-- Champ caché pour le jeton CSRF -->
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    
    <div class="row justify-content-md-center">
        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mb-3 mb-lg-0">
            <select name="class_id" id="class_id_bulk" class="form-control select2" data-toggle = "select2" onchange="classWiseSection(this.value)" required>
                <option value=""><?php echo get_phrase('select_a_class'); ?></option>
                <?php $classes = $this->db->get_where('classes', array('school_id' => $school_id))->result_array(); ?>
                <?php foreach($classes as $class){ ?>
                    <option value="<?php echo $class['id']; ?>"><?php echo $class['name']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mb-3 mb-lg-0" id = "section_content">
            <select name="section_id" id="section_id" class="form-control select2" data-toggle = "select2" required >
                <option value=""><?php echo get_phrase('select_section'); ?></option>
            </select>
        </div>
    </div>
    <br>
    <div id = "first-row">
        <div class="row">
            <div class="col-xl-11 col-lg-11 col-md-12 col-sm-12 mb-3 mb-lg-0">
                <div class="row justify-content-md-center">
                    <div class="form-group col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-1 mb-lg-0">
                        <input type="text" name="name[]" class="form-control"  value="" placeholder="Name" required>
                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-1 mb-lg-0">
                        <input type="email" name="email[]" class="form-control"  value="" placeholder="Email" required>
                    </div>
                 
                    <div class="form-group col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-1 mb-lg-0">
                        <select name="gender[]" class="form-control" required>
                            <option value=""><?php echo get_phrase('select_gender'); ?></option>
                            <option value="Male"><?php echo get_phrase('male'); ?></option>
                            <option value="Female"><?php echo get_phrase('female'); ?></option>
                            <option value="Others"><?php echo get_phrase('others'); ?></option>
                        </select>
                    </div>
                                   
                </div>
            </div>
            <div class="col-xl-1 col-lg-1 col-md-12 col-sm-12 mb-3 mb-lg-0">
                <div class="row justify-content-md-center">
                    <div class="form-group col">
                        <button type="button" class="btn btn-icon btn-success" onclick="appendRow()"> <i class="mdi mdi-plus"></i> </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-secondary col-md-4 col-sm-12 mb-4 mt-2"><?php echo get_phrase('add_students'); ?></button>
    </div>
</form>

<div id = "blank-row" style="display: none;">
    <div class="row student-row mt-1">
        <div class="col-xl-11 col-lg-11 col-md-12 col-sm-12 mb-3 mb-lg-0">
            <div class="row justify-content-md-center">
                <div class="form-group col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-1 mb-lg-0">
                    <input type="text" name="name[]" class="form-control"  value="" placeholder="Name">
                </div>
                <div class="form-group col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-1 mb-lg-0">
                    <input type="email" name="email[]" class="form-control"  value="" placeholder="Email">
                </div>
          
                <div class="form-group col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-1 mb-lg-0">
                    <select name="gender[]" class="form-control">
                        <option value=""><?php echo get_phrase('select_gender'); ?></option>
                        <option value="Male"><?php echo get_phrase('male'); ?></option>
                        <option value="Female"><?php echo get_phrase('female'); ?></option>
                        <option value="Others"><?php echo get_phrase('others'); ?></option>
                    </select>
                </div>

          
            </div>
        </div>
        <div class="col-xl-1 col-lg-1 col-md-12 col-sm-12 mb-3 mb-lg-0">
            <div class="row justify-content-md-center">
                <div class="form-group col">
                    <button type="button" class="btn btn-icon btn-danger" onclick="removeRow(this)"> <i class="mdi mdi-window-close"></i> </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var blank_field = $('#blank-row').html();

function appendRow() {
    $('#first-row').append(blank_field);
}

function removeRow(elem) {
    $(elem).closest('.student-row').remove();
}

$(".ajaxForm").submit(function(e) {
    e.preventDefault();
    var form = $(this);
    
    // Afficher le spinner et désactiver le bouton
    var adding_text = "<?php echo get_phrase('adding'); ?>";
    var submitBtn = $('button[type="submit"]', form);
    var original_text = submitBtn.html();
    
    submitBtn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> ' + adding_text);
    
    // Soumettre le formulaire en AJAX
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        dataType: 'json',
        success: function(response) {
            form.trigger("reset");
            
            // Afficher la notification appropriée
            if(response.type === 'error') {
                error_notify(response.notification);
            } else {
                success_notify(response.notification);
            }
            
            // Réactiver le bouton après délai
            setTimeout(function() {
                submitBtn.prop('disabled', false).html(original_text);
            }, 3300);
        },
        error: function(xhr, status, error) {
            error_notify("<?php echo get_phrase('an_error_occurred_please_try_again'); ?>");
            submitBtn.prop('disabled', false).html(original_text);
            
            // Debug: afficher l'erreur dans la console
            // console.error(xhr.responseText);
        }
        
    });
});
</script>
