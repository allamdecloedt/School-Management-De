<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/edit-design-button.css">

<form method="POST" class="d-block ajaxForm" action="<?php echo route('routine/create'); ?>" style="min-width: 300px;">
    <!-- Champ caché pour le jeton CSRF -->
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    
    <?php $school_id = school_id(); ?>
    <div class="form-group row mb-2">
        <label for="class_id_on_routine_creation" class="col-md-3 col-form-label"><?php echo get_phrase('class'); ?><span class="required"> * </span></label>
        <div class="col-md-9">
            <select name="class_id" id="class_id_on_routine_creation" class="form-control select2" data-bs-toggle="select2"  required onchange="classWiseSectionForRoutineCreate(this.value)">
                <option value=""><?php echo get_phrase('select_a_class'); ?></option>
                <?php $classes = $this->db->get_where('classes', array('school_id' => $school_id))->result_array(); ?>
                <?php foreach($classes as $class): ?>
                    <option value="<?php echo $class['id']; ?>"><?php echo $class['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-group row mb-2">
        <label for="section_id_on_routine_creation" class="col-md-3 col-form-label"><?php echo get_phrase('section'); ?><span class="required"> * </span></label>
        <div class="col-md-9">
            <select name="section_id" id = "section_id_on_routine_creation" class="form-control select2" data-bs-toggle="select2"  required>
                <option value=""><?php echo get_phrase('select_section'); ?></option>
            </select>
        </div>
    </div>


    <div class="form-group row mb-2">
        <label for="teacher" class="col-md-3 col-form-label"><?php echo get_phrase('teacher'); ?><span class="required"> * </span></label>
        <div class="col-md-9">
            <select name="teacher_id" id = "teacher_on_routine_creation" class="form-control select2" data-bs-toggle="select2"  required>
                <option value=""><?php echo get_phrase('assign_a_teacher'); ?></option>
                <?php $teachers = $this->db->get_where('teachers', array('school_id' => $school_id))->result_array(); ?>
                <?php foreach($teachers as $teacher): ?>
                    <option value="<?php echo $teacher['id']; ?>"><?php echo $this->user_model->get_user_details($teacher['user_id'], 'name'); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-group row mb-2">
        <label for="class_room_id" class="col-md-3 col-form-label"><?php echo get_phrase('class_room'); ?><span class="required"> * </span></label>
        <div class="col-md-9">
            <select name="class_room_id" id = "class_room_id_on_routine_creation" class="form-control select2" data-bs-toggle="select2"  required>
                <option value=""><?php echo get_phrase('select_a_class_room'); ?></option>
                <?php $class_rooms = $this->db->get_where('class_rooms', array('school_id' => $school_id))->result_array(); ?>
                <?php foreach($class_rooms as $class_room): ?>
                    <option value="<?php echo $class_room['id']; ?>"><?php echo $class_room['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-group row mb-2">
        <label for="day" class="col-md-3 col-form-label"><?php echo get_phrase('day'); ?><span class="required"> * </span></label>
        <div class="col-md-9">
            <select name="day" id = "day_on_routine_creation" class="form-control select2" data-bs-toggle="select2"  required>
                <option value=""><?php echo get_phrase('select_a_day'); ?></option>
                <option value="saturday"><?php echo get_phrase('saturday'); ?></option>
                <option value="sunday"><?php echo get_phrase('sunday'); ?></option>
                <option value="monday"><?php echo get_phrase('monday'); ?></option>
                <option value="tuesday"><?php echo get_phrase('tuesday'); ?></option>
                <option value="wednesday"><?php echo get_phrase('wednesday'); ?></option>
                <option value="thursday"><?php echo get_phrase('thursday'); ?></option>
                <option value="friday"><?php echo get_phrase('friday'); ?></option>
            </select>
        </div>
    </div>

    <div class="form-group row mb-2">
        <label for="starting_hour" class="col-md-3 col-form-label"><?php echo get_phrase('starting_hour'); ?><span class="required"> * </span></label>
        <div class="col-md-9">
            <select name="starting_hour" id = "starting_hour_on_routine_creation" class="form-control select2" data-bs-toggle="select2"  required>
                <option value=""><?php echo get_phrase('starting_hour'); ?></option>
                <?php for($i = 0; $i <= 23; $i++){
                    if ($i < 12){
                        if ($i == 0){ ?>
                            <option value="<?php echo $i; ?>">12 AM</option>
                        <?php }else{ ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?> AM</option>
                        <?php } ?>
                    <?php }else{ ?>
                        <?php $j = $i - 12; ?>

                        <?php if ($j == 0){ ?>
                            <option value="<?php echo $i; ?>">12 PM</option>
                        <?php }else{ ?>
                            <option value="<?php echo $i; ?>"><?php echo $j; ?> PM</option>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group row mb-2">
        <label for="starting_minute" class="col-md-3 col-form-label"><?php echo get_phrase('starting_minute'); ?><span class="required"> * </span></label>
        <div class="col-md-9">
            <select name="starting_minute" id = "starting_minute_on_routine_creation" class="form-control select2" data-bs-toggle="select2"  required>
                <option value=""><?php echo get_phrase('starting_minute'); ?></option>
                <?php for($i = 0; $i <= 55; $i = $i+5){ ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group row mb-2">
        <label for="ending_hour" class="col-md-3 col-form-label"><?php echo get_phrase('ending_hour'); ?><span class="required"> * </span></label>
        <div class="col-md-9">
            <select name="ending_hour" id = "ending_hour_on_routine_creation" class="form-control select2" data-bs-toggle="select2"  required>
                <option value=""><?php echo get_phrase('ending_hour'); ?></option>
                <?php for($i = 0; $i <= 23; $i++){
                    if ($i < 12){
                        if ($i == 0){ ?>
                            <option value="<?php echo $i; ?>">12 AM</option>
                        <?php }else{ ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?> AM</option>
                        <?php } ?>
                    <?php }else{ ?>
                        <?php $j = $i - 12; ?>

                        <?php if ($j == 0){ ?>
                            <option value="<?php echo $i; ?>">12 PM</option>
                        <?php }else{ ?>
                            <option value="<?php echo $i; ?>"><?php echo $j; ?> PM</option>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group row mb-2">
        <label for="ending_minute" class="col-md-3 col-form-label"><?php echo get_phrase('ending_minute'); ?><span class="required"> * </span></label>
        <div class="col-md-9">
            <select name="ending_minute" id = "ending_minute_on_routine_creation" class="form-control select2" data-bs-toggle="select2"  required>
                <option value=""><?php echo get_phrase('ending_minute'); ?></option>
                <?php for($i = 0; $i <= 55; $i = $i+5){ ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group  col-md-12">
        <button class="btn btn-primary btn-l px-4" id="update-btn" type="submit"><i class="mdi mdi-plus"></i><?php echo get_phrase('add_class_routine'); ?></button>
    </div>
</form>


<script>
$(document).ready(function () {

    $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); }); 

    $(".ajaxForm").validate({}); // Jquery form validation initialization
    $(".ajaxForm").submit(function(e) {
        
        e.preventDefault(); // Bloque le comportement normal
        var form = $(this);
        //ajaxSubmit(e, form, showAllGrades);
        function getCsrfToken() {
         // Récupérer le nom du token CSRF depuis le champ input caché
          var csrfName = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').attr('name');
         // Récupérer la valeur (hash) du token CSRF depuis le champ input caché
           var csrfHash = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();
         // Retourner un objet contenant le nom du token et sa valeur
         return { csrfName: csrfName, csrfHash: csrfHash };
      }
           // Cible uniquement le bouton de ce formulaire
        var submitButton = $(this).find('button[type="submit"]');
        var adding_text = "<?php echo get_phrase('adding'); ?>...";
        
        // Désactive et met à jour uniquement ce bouton
        submitButton.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i>'+adding_text);
         // Récupérer le token CSRF avant l'envoi
         var csrf = getCsrfToken(); // Appel de la fonction pour obtenir le token
         const formData = new FormData(this);// Crée une nouvelle instance de FormData en passant l'élément du formulaire courant

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            if (response.status) { // Vérifie si la mise à jour a réussi
                success_notify(response.notification);
                // Met à jour le token CSRF
                $('input[name="' + response.csrf.name + '"]').val(response.csrf.hash);

                // Rafraîchissement de la page après un léger délai pour s'assurer que les modifications sont appliquées
                setTimeout(function() {
                  location.reload();
                }, 3500);// Attendre 3500ms avant de recharger la page
            } else {
              error_notify('<?= js_phrase(get_phrase('action_not_allowed')); ?>')
                
            }
        },
        error: function () {
          error_notify(<?= js_phrase(get_phrase('an_error_occurred_during_submission')); ?>)
        }
      });
    });
});

function classWiseSectionForRoutineCreate(classId) {
    $.ajax({
        url: "<?php echo route('section/list/'); ?>"+classId,
        success: function(response){
            $('#section_id_on_routine_creation').html(response);
            classWiseSubjectForRoutineCreate(classId);
        }
    });
}

function classWiseSubjectForRoutineCreate(classId) {
    $.ajax({
        url: "<?php echo route('class_wise_subject/'); ?>"+classId,
        success: function(response){
            $('#subject_id_on_routine_creation').html(response);
        }
    });
}
</script>
