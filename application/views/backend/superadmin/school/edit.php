<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/edit-design-button.css">

<?php
$schools = $this->db->get_where('schools', array('id' => $param1))->result_array();
foreach($schools as $school): ?>
<form method="POST" class="d-block ajaxForm" action="<?php echo route('school_crud/update/'.$param1); ?>">
  <!-- Champ caché pour le jeton CSRF -->
  <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
  
  <div class="form-row">
    <div class="form-group mb-1">
      <label for="name"><?php echo get_phrase('name'); ?><span class="required"> * </span></label>
      <input type="text" value="<?php echo $school['name']; ?>" class="form-control" id="name" name = "name" required>
      <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_name'); ?></small>
    </div>

    <div class="form-group mb-1">
            <label for="description"><?php echo get_phrase('description'); ?><span class="required"> * </span></label>
            <textarea class="form-control"  id="description"  name = "description" rows="5" required><?php echo $school['description']; ?></textarea>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_description'); ?></small>
      </div>




    <div class="form-group mb-1">
      <label for="phone"><?php echo get_phrase('phone_number'); ?><span class="required"> * </span></label>
      <input type="text" value="<?php echo $school['phone']; ?>" class="form-control" id="phone" name = "phone" required>
      <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_phone_number'); ?></small>
    </div>

    <div class="form-group mb-1">
            <label for="access"><?php echo get_phrase('Access'); ?><span class="required"> * </span></label>
            <select name="access" id="access" class="form-control select2" data-toggle = "select2" required>
                <option value=""><?php echo get_phrase('select_a_access'); ?></option>
                <option <?php if ($school['access'] == 1): ?> selected <?php endif; ?> value="1"><?php echo get_phrase('public'); ?></option>
                <option <?php if ($school['access'] == 0): ?> selected <?php endif; ?> value="0"><?php echo get_phrase('privé'); ?></option>
              
            </select>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_access'); ?></small>
        </div>


        <div class="form-group mb-1">
            <label for="access"><?php echo get_phrase('Category'); ?><span class="required"> * </span></label>
            <select name="category" id="category" class="form-control select2" data-toggle = "select2" required>
                <option value=""><?php echo get_phrase('select_a_category'); ?></option>
                <?php $categories = $this->db->get_where('categories', array())->result_array(); ?>
                <?php foreach ($categories as $categorie): ?>
                    <option <?php if ($school['category'] == $categorie['name']): ?> selected <?php endif; ?> value="<?php echo $categorie['name']; ?>"><?php echo $categorie['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_category'); ?></small>
        </div>


   



    

    <div class="form-group mb-1">
      <label for="phone"><?php echo get_phrase('address'); ?><span class="required"> * </span></label>
      <textarea class="form-control" id="address" name = "address" rows="5" required><?php echo $school['address']; ?></textarea>
      <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_address'); ?></small>
    </div>

    <div class="form-group mb-1">
         <div id="photo-preview" class="photo-preview">
                <!-- L'image sélectionnée apparaîtra ici -->
                <img class="rounded-circle" style="width: 30%;height: 50%;object-fit: cover;border-radius: 50%;"  id="default-avatar" src="<?php echo $this->user_model->get_school_image($param1); ?>">
          </div>
          <?php 
            // Vérifiez si l'image existe
            $image = $this->user_model->get_school_image($param1);
            $is_image_exists = !empty($image); 
            ?>
          <input id="school_image" type="file" class="form-control" name="school_image" accept=".jpg, .jpeg, .png" <?php echo $is_image_exists ? '' : 'required'; ?>>
    </div>

    <div class="form-group mt-2 col-md-12">
      <button class="btn btn-primary btn-l px-4" id="update-btn" type="submit"><i class="mdi mdi-account-check"></i><?php echo get_phrase('update_school'); ?></button>
    </div>
  </div>
</form>
<?php endforeach; ?>

<script>

  $(document).ready(function () {
    $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); });
  });
  $(".ajaxForm").validate({}); // Jquery form validation initialization
  $(".ajaxForm").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, showAllSchools);
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

  document.getElementById('school_image').addEventListener('change', function(event) {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const preview = document.getElementById('photo-preview');
      preview.innerHTML = '<img src="' + e.target.result + '" style="width: 30%;height: 50%;object-fit: cover;border-radius: 50%;" alt="Photo preview" />';
    };
    reader.readAsDataURL(file);
  }
});
</script>
