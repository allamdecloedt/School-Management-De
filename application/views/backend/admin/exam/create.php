<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/edit-design-button.css">
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
            <button class="btn btn-primary btn-l px-4" id="update-btn" type="submit"><i class="mdi mdi-plus"></i><?php echo get_phrase('create_exam'); ?></button>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
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