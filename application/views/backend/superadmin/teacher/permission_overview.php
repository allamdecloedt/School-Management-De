<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/edit-design-button.css">

<div class="row" style="min-width: 300px;">
    <div class="col-md-12">
        <h5 class="text-center"><?php echo $this->db->get_where('users', array('id' => $param2))->row('name'); ?></h5>
        <?php $teacher_permissions = $this->db->get_where('teacher_permissions', array('teacher_id' => $param1))->result_array();
            $count = 0;
            foreach($teacher_permissions as $teacher_permission){
                $count++;
        ?>
                <table class="table table-hover table-centered table-bordered mb-0" style="margin-bottom: 50px !important; background-color: #FAFAFA;">
                    <tbody>
                        <tr>
                            <td><?php echo get_phrase('class'); ?></td>
                            <td>
                                <?php echo $this->db->get_where('classes', array('id' => $teacher_permission['class_id']))->row('name'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo get_phrase('section'); ?></td>
                            <td>
                                <?php echo $this->db->get_where('sections', array('id' => $teacher_permission['section_id']))->row('name'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo get_phrase('marks'); ?></td>
                            <td>
                                <i class="mdi mdi-circle text-<?php if($teacher_permission['marks'] == 1){echo 'success';}else{echo 'danger';} ?>"></i>
                            </td>
                        </tr>
                        <!-- <tr>
                            <td><?php echo get_phrase('assignment'); ?></td>
                            <td>
                                <i class="mdi mdi-circle text-<?php if($teacher_permission['assignment'] == 1){echo 'success';}else{echo 'danger';} ?>"></i>
                            </td>
                        </tr> -->
                        <tr>
                            <td><?php echo get_phrase('attendance'); ?></td>
                            <td>
                                <i class="mdi mdi-circle text-<?php if($teacher_permission['attendance'] == 1){echo 'success';}else{echo 'danger';} ?>"></i>
                            </td>
                        </tr>
                        <!-- <tr>
                            <td><?php echo get_phrase('online_exam'); ?></td>
                            <td>
                                <i class="mdi mdi-circle text-<?php if($teacher_permission['online_exam'] == 1){echo 'success';}else{echo 'danger';} ?>"></i>
                            </td>
                        </tr> -->
                    </tbody>
                </table>
        <?php } ?>
        <?php if($count == 0){ ?>
            <p class = "text-center"><?php echo get_phrase('no_permission_assigned_yet'); ?></p>
        <?php } ?>
        <a href="<?php echo route('permission'); ?>" class="btn btn-primary btn-l px-4" id="update-btn" type="submit"><i class="mdi mdi-account-check"></i><?php echo get_phrase('update_permissions'); ?></a>
    </div>
</div>

<script>
$(".ajaxForm").validate({}); // Jquery form validation initialization
  $(".ajaxForm").submit(function(e) {
      var form = $(this);
      ajaxSubmit(e, form, showAllSessions);
      function getCsrfToken() {
         // Récupérer le nom du token CSRF depuis le champ input caché
          var csrfName = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').attr('name');
         // Récupérer la valeur (hash) du token CSRF depuis le champ input caché
           var csrfHash = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();
         // Retourner un objet contenant le nom du token et sa valeur
         return { csrfName: csrfName, csrfHash: csrfHash };
      }


 // Soumission du formulaire de logo
 $(".ajaxForm").submit(function(e) {
    e.preventDefault();

           // Cible uniquement le bouton de ce formulaire
        var submitButton = $(this).find('button[type="submit"]');
        var updating_text = "<?php echo get_phrase('updating'); ?>...";
        
        // Désactive et met à jour uniquement ce bouton
        submitButton.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i>'+updating_text);
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
</script>
