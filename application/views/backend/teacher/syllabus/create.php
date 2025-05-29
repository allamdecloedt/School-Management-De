<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/edit-design-button.css">

<form method="POST" class="d-block ajaxForm" action="<?php echo route('syllabus/create'); ?>" enctype="multipart/form-data">
    <!-- Champ caché pour le jeton CSRF -->
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    
    <div class="form-row">
        <?php $school_id = school_id(); ?>
        <input type="hidden" name="school_id" value="<?php echo $school_id; ?>">
        <input type="hidden" name="session_id" value="<?php echo active_session(); ?>">
        <div class="form-group col-md-12 mb-2">
            <label for="title"><?php echo get_phrase('tittle'); ?><span class="required"> * </span></label>
            <input type="text" class="form-control" id="title" name = "title" required>
        </div>
        <div class="form-group col-md-12 mb-2">
            <label for="class_id_on_create"><?php echo get_phrase('class'); ?><span class="required"> * </span></label>
            <select class="form-control select2" data-toggle = "select2" id="class_id_on_create" name="class_id" onchange="classWiseSectionOnCreate(this.value)" required>
                <option value=""><?php echo get_phrase('select_a_class'); ?></option>
                <?php $classes = $this->db->get_where('classes', array('school_id' => $school_id))->result_array(); ?>
                <?php foreach($classes as $class): ?>
                    <option value="<?php echo $class['id']; ?>"><?php echo $class['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group col-md-12 mb-2">
            <label for="section_id_on_create"><?php echo get_phrase('section'); ?><span class="required"> * </span></label>
            <select class="form-control select2" data-toggle = "select2" id="section_id_on_create" name="section_id" required>
                <option value=""><?php echo get_phrase('select_a_section'); ?></option>
            </select>
        </div>

        <div class="form-group col-md-12 mb-2">
            <label for="subject_id_on_create"><?php echo get_phrase('subject'); ?><span class="required"> * </span></label>
            <select class="form-control select2" data-toggle = "select2" id="subject_id_on_create" name="subject_id" requied>
                <option><?php echo get_phrase('select_a_subject'); ?></option>
            </select>
        </div>
        <div class="form-group col-md-12 mb-2">
            <label for="syllabus_file"><?php echo get_phrase('upload_syllabus'); ?><span class="required"> * </span></label>
            <div class="custom-file-upload d-inline-block">
                <input type="file" class="form-control" id="syllabus_file" name = "syllabus_file" required>
            </div>
        </div>
        <div class="form-group mb-1">
            <button class="btn btn-primary btn-l px-4" id="update-btn" type="submit"><i class="mdi mdi-plus"></i><?php echo get_phrase('create_syllabus'); ?></button>
        </div>
    </div>
</form>

<script>


$('document').ready(function(){
    $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); });
//     initSelect2(['#class_id_on_create',
//                 '#section_id_on_create',
//                 '#subject_id_on_create']);
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
        var adding_text = "<?php echo get_phrase('creating'); ?>...";
        
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

function classWiseSectionOnCreate(classId) {
    $.ajax({
        url: "<?php echo route('section/list/'); ?>"+classId,
        success: function(response){
            $('#section_id_on_create').html(response);
            classWiseSubjectOnCreate(classId);
        }
    });
}

function classWiseSubjectOnCreate(classId) {
    $.ajax({
        url: "<?php echo route('class_wise_subject/'); ?>"+classId,
        success: function(response){
            $('#subject_id_on_create').html(response);
        }
    });
}
</script>


<script type="text/javascript">
  initCustomFileUploader();
</script>
