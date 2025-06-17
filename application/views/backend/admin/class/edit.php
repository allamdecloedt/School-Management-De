<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/edit-design-button.css">

<?php $classes = $this->db->get_where('classes', array('id' => $param1))->result_array(); ?>
<?php foreach($classes as $class){ ?>
<form method="POST" class="d-block ajaxForm" action="<?php echo route('manage_class/update/'.$param1); ?>">
    <!-- Champ caché pour le jeton CSRF -->
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    
<div class="form-row">
    <div class="form-group mb-1 col-md-12">
            <label for="name"><?php echo get_phrase('class_name'); ?><span class="required"> * </span></label>
            <input type="text" class="form-control" value="<?php echo $class['name']; ?>" id="name" name = "name" required>
            <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_class_name'); ?></small>
        </div>
        <div class="form-group mb-1 col-md-12">
            <label for="price"><?php echo get_phrase('class_price'); ?><span class="required"> * </span></label>
            <?php $currencies = $this->db->get_where('settings_school', array('school_id' => school_id()))->row('system_currency'); ?>

            <div class="form-inline">
            <input type="text" class="form-control col-md-10" value="<?php echo $class['price']; ?>" id="price" name = "price" required>
            <input type="text" class="form-control currency-input col-md-2" value="<?php echo $currencies; ?>" disabled>
            <input type="hidden"  id = "currency" name="currency" value="<?php echo $currencies; ?>" >
        </div>
           
            <small id="price_help" class="form-text text-muted"><?php echo get_phrase('provide_class_price'); ?></small>
        </div>


        <div class="form-group  col-md-12">
            <button class="btn btn-primary btn-l px-4" id="update-btn" type="submit"><i class="mdi mdi-account-check"></i><?php echo get_phrase('update_class'); ?></button>
        </div>
    </div>
</form>
<?php } ?>

<script>
  $(".ajaxForm").validate({}); // Jquery form validation initialization
  $(".ajaxForm").submit(function(e) {
      var form = $(this);
      ajaxSubmit(e, form, showAllClasses);
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


<style>
    .form-inline .form-control {
        display: inline-block;
        width: 75%;
        vertical-align: middle;
    }
    .form-inline .currency-input {
        max-width: 80px;
    }
</style>