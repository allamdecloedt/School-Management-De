<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/edit-design-button.css">

<?php $event_calendars = $this->db->get_where('event_calendars', array('id' => $param1))->result_array(); ?>
<?php foreach($event_calendars as $event_calendar){ ?>
    <form method="POST" class="d-block ajaxForm" action="<?php echo route('event_calendar/update/'.$param1); ?>">
        <!-- Champ caché pour le jeton CSRF -->
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    
        <div class="form-row">
            <div class="form-group mb-1">
                <label for="title"><?php echo get_phrase('event_title'); ?><span class="required"> * </span></label>
                <input type="text" class="form-control" value="<?php echo $event_calendar['title']; ?>" id="title" name = "title" required>
                <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_title_name'); ?></small>
            </div>
            <div class="form-group mb-1">
                <label for="starting_date"><?php echo get_phrase('event_starting_date'); ?><span class="required"> * </span></label>
                <input type="text" value="<?php echo date('m/d/Y', strtotime($event_calendar['starting_date'])); ?>" class="form-control" id="starting_date" name = "starting_date" data-provide = "datepicker" required>
                <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_starting_date'); ?></small>
            </div>

            <div class="form-group mb-1">
                <label for="ending_date"><?php echo get_phrase('event_ending_date'); ?><span class="required"> * </span></label>
                <input type="text" value="<?php echo date('m/d/Y', strtotime($event_calendar['ending_date'])); ?>" class="form-control" id="ending_date" name = "ending_date" data-provide = "datepicker" required>
                <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_ending_date'); ?></small>
            </div>

            <div class="form-group  col-md-12">
                <button class="btn btn-primary btn-l px-4" id="update-btn" type="submit"><i class="mdi mdi-account-check"></i><?php echo get_phrase('update_event'); ?></button>
            </div>
        </div>
    </form>
<?php } ?>
<script>
$(document).ready(function() {

});
$(".ajaxForm").validate({}); // Jquery form validation initialization
$(".ajaxForm").submit(function(e) {
  var form = $(this);
  ajaxSubmit(e, form, showAllEvents);
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

</script>
