<form method="POST" class="d-block ajaxForm" action="<?php echo route('exam/create'); ?>">
    <!-- Champ caché pour le jeton CSRF -->
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    
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
            <label for="class_id"><?php echo get_phrase('class'); ?><span class="required"> * </span></label>
            <select class="form-control" id="class_id" name="class_id" required>
                <option value=""><?php echo get_phrase('select_class'); ?></option>
                <?php 
                $school_id = school_id(); // Fonction hypothétique pour récupérer l'ID de l'école
                $classes = $this->crud_model->get_classes($school_id)->result_array();
                foreach ($classes as $class): 
                ?>
                    <option value="<?php echo $class['id']; ?>"><?php echo $class['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <small id="class_help" class="form-text text-muted"><?php echo get_phrase('select_a_class'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="section_id"><?php echo get_phrase('section'); ?><span class="required"> * </span></label>
            <select class="form-control" id="section_id" name="section_id" required>
                <option value=""><?php echo get_phrase('select_section'); ?></option>
            </select>
            <small id="section_help" class="form-text text-muted"><?php echo get_phrase('select_a_section'); ?></small>
        </div>

        <div class="form-group col-md-12">
            <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('create_exam'); ?></button>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    // Validation du formulaire
    $(".ajaxForm").validate({});

    // Soumission du formulaire via AJAX
    $(".ajaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, showAllExams);
    });

    // Charger les sections quand une classe est sélectionnée
    $('#class_id').change(function() {
        var class_id = $(this).val();
        if (class_id) {
            $.ajax({
                url: '<?php echo base_url('api/GetSectionsByClassId/'); ?>' + class_id,
                type: 'GET', // On essaie GET car l'ID est dans l'URL
                data: {
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                dataType: 'json',
                success: function(response) {
                    $('#section_id').html('<option value=""><?php echo get_phrase('select_section'); ?></option>');
                    if (response.sections && response.sections.length > 0) {
                        $.each(response.sections, function(index, section) {
                            $('#section_id').append('<option value="' + section.id + '">' + section.name + '</option>');
                        });
                    } else {
                        alert('<?php echo get_phrase('no_sections_found'); ?>');
                    }
                    // Mettre à jour le jeton CSRF
                    if (response.csrf) {
                        $('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').val(response.csrf.csrfHash);
                    }
                },
                error: function(xhr, status, error) {
                    $('#section_id').html('<option value=""><?php echo get_phrase('select_section'); ?></option>');
                    alert('<?php echo get_phrase('error_loading_sections'); ?>');
                }
            });
        } else {
            $('#section_id').html('<option value=""><?php echo get_phrase('select_section'); ?></option>');
        }
    });
});
</script>