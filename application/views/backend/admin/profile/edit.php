<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/schoolSettings.css">
<?php
$profile_data = $this->user_model->get_profile_data();
?>
<div class="row justify-content-md-center">
    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title"><?php echo get_phrase('update_profile') ; ?></h4>
                <form method="POST" class="col-12 profileAjaxForm" action="<?php echo route('profile/update_profile') ; ?>" id = "profileAjaxForm" enctype="multipart/form-data">
                    <!-- Champ caché pour le jeton CSRF -->
                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

                    <div class="col-12">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="name"> <?php echo get_phrase('name') ; ?></label>
                            <div class="col-md-9">
                                <input type="text" id="name" name="name" class="form-control"  value="<?php echo $profile_data['name']; ?>" required>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="email"><?php echo get_phrase('email') ; ?></label>
                            <div class="col-md-9">
                                <input type="email" id="email" name="email" class="form-control"  value="<?php echo $profile_data['email']; ?>" required>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="phone"> <?php echo get_phrase('phone') ; ?></label>
                            <div class="col-md-9">
                                <input type="text" id="phone" name="phone" class="form-control"  value="<?php echo $profile_data['phone']; ?>">
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="address"> <?php echo get_phrase('address') ; ?></label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="address" name = "address" rows="5"><?php echo $profile_data['address']; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="example-fileinput"><?php echo get_phrase('profile_image'); ?></label>
                                <div class="col-md-5 logo-upload-container">
                                        <div class="logo-card">
                                            <div class="logo-header">
                                                <h5><?php echo get_phrase('profile_image'); ?></h5>
                                            </div>
                                            <div class="logo-preview" id="profile-image-preview">
                                                    <img src="<?php echo $this->user_model->get_user_image($this->session->userdata('user_id')). '?v=' . time(); ?>" alt="SupperAdmin Profile Image" class="preview-image">
                                                    <div class="logo-overlay">
                                                        <i class="fas fa-camera"></i>
                                                    </div>
                                            </div>
                                            <div class="logo-upload-btn">
                                                <label for="profile_image">
                                                <i class="mdi mdi-cloud-upload"></i> <?php echo get_phrase('upload_an_image'); ?>
                                                </label>
                                                <input id="profile_image" type="file" class="image-upload" name="profile_image" accept="image/*" data-preview="profile-image-preview">
                                            </div>
                                        </div>
                                </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-secondary col-xl-4 col-lg-4 col-md-12 col-sm-12" onclick="updateProfileInfo()">
                            <i class="mdi mdi-account-check"></i>
                                <?php echo get_phrase('update_profile') ; ?></button>
                        </div>
                    </div>
                </form>

            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div>

    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title"><?php echo get_phrase('change_password') ; ?></h4>
                <form method="POST" class="col-12 changePasswordAjaxForm" action="<?php echo route('profile/update_password') ; ?>" id = "changePasswordAjaxForm" enctype="multipart/form-data">
                    <!-- Champ caché pour le jeton CSRF -->
                     <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                    <div class="col-12">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="current_password"> <?php echo get_phrase('current_password') ; ?></label>
                            <div class="col-md-9">
                                <input type="password" id="current_password" name="current_password" class="form-control"  value="" required>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="new_password"> <?php echo get_phrase('new_password') ; ?></label>
                            <div class="col-md-9">
                                <input type="password" id="new_password" name="new_password" class="form-control"  value="" required>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="confirm_password"> <?php echo get_phrase('confirm_password') ; ?></label>
                            <div class="col-md-9">
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control"  value="" required>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-secondary col-xl-4 col-lg-4 col-md-12 col-sm-12" onclick="changePassword()"><?php echo get_phrase('change_password') ; ?></button>
                        </div>
                    </div>
                </form>

            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div>
</div>
<script type="text/javascript">
$(document).ready(function () {

    // Gestionnaire de prévisualisation d'image
    // Image preview handlers
    $('.image-upload').each(function() {
    const input = $(this); // Sélectionne chaque champ d'upload d'image individuellement
    const previewId = input.data('preview'); // Récupère l'ID du conteneur de prévisualisation depuis l'attribut data-preview
    
    input.on('change', function() { // Ajoute un événement de changement lorsque l'utilisateur sélectionne un fichier
      const file = this.files[0]; // Récupère le premier fichier sélectionné
      if (file) {
        const reader = new FileReader(); // Crée un objet FileReader pour lire le fichier
        const previewContainer = $('#' + previewId); // Sélectionne le conteneur de prévisualisation
        const previewImage = previewContainer.find('.preview-image'); // Sélectionne l'image de prévisualisation à l'intérieur du conteneur
        
        reader.onload = function(e) { // Exécute cette fonction lorsque le fichier est lu avec succès
          previewImage.attr('src', e.target.result); // Met à jour la source de l'image avec l'URL du fichier chargé
          
          // Ajoute un effet d'animation visuelle pour signaler l'upload
          previewContainer.addClass('upload-highlight'); 
          setTimeout(function() {
            previewContainer.removeClass('upload-highlight'); // Supprime l'effet après 1,5 seconde
          }, 1500);
        };
        
        reader.readAsDataURL(file); // Lit le fichier sous forme d'URL de données (base64)
      }
    });
});

           // Fonction pour récupérer et retourner le token CSRF
            
            function getCsrfToken() {
                var csrfName = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').attr('name');
                var csrfHash = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();
                return { csrfName: csrfName, csrfHash: csrfHash };
            }
          

    // Soumission AJAX du formulaire
    $('#profileAjaxForm').submit(function(e) {
        e.preventDefault();
        // Obtenez le texte de mise à jour traduit
        var updating_text = "<?php echo get_phrase('updating'); ?>";

          // Afficher un indicateur de chargement
          $('button[type="submit"]').prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i>'+updating_text);

         // Récupérer le token CSRF avant l'envoi
         var csrf = getCsrfToken(); // Appel de la fonction pour obtenir le token
         const formData = new FormData(this);// Crée une nouvelle instance de FormData en passant l'élément du formulaire courant
            
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            dataType: 'json',
           
            success: function (response) {
                if (response.status) {
                    // Mise à jour du token CSRF
                    $('input[name="' + response.csrf.name + '"]').val(response.csrf.hash);

                 // Mise à jour dynamique des images avec cache-buster (pour forcer le rechargement des images)
                 $('.preview-image').each(function() {
                    var originalSrc = $(this).attr('src').split('?')[0];// Récupère l'URL d'origine de l'image sans la chaîne de requête
                   // $(this).attr('src', originalSrc + '?v=' + Date.now()); // Ajoute un timestamp pour éviter la mise en cache
                });

                // Rafraîchissement de la page après un léger délai pour s'assurer que les modifications sont appliquées
                 setTimeout(function() {
                  location.reload();
                }, 3500);// Attendre 3500ms avant de recharger la page
            } else {
              error_notify('<?php echo get_phrase('action_not_allowed'); ?>')
                
            }
        },
        error: function () {
          error_notify('<?php echo get_phrase('an_error_occurred_during_submission'); ?>')
            }
        });
    });
});
</script>