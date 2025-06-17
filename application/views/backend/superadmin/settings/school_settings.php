<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/schoolSettings.css">


<?php $school_data = $this->settings_model->get_current_school_data(); ?>
<div class="row justify-content-md-center">
        <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title"><?php echo get_phrase('school_settings') ;?></h4>
                    <form method="POST" class="col-12 schoolForm" action="<?php echo route('school_settings/update') ;?>" id = "schoolForm">
                        <!-- Champ caché pour le jeton CSRF -->
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                        <div class="col-12">
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="school_name"> <?php echo get_phrase('school_name') ;?><span class="required"> * </span></label>
                                <div class="col-md-9">
                                    <input type="text" id="school_name" name="school_name" class="form-control"  value="<?php echo $school_data['name'] ;?>" required>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="description"><?php echo get_phrase('description'); ?><span class="required"> * </span></label>
                                <div class="col-md-9">
                                <textarea class="form-control"  id="description"  name = "description" rows="5" required><?php echo $school_data['description']; ?></textarea>
                                <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_description'); ?></small>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="phone"><?php echo get_phrase('phone') ;?><span class="required"> * </span></label>
                                <div class="col-md-9">
                                    <input type="text" id="phone" name="phone" class="form-control"  value="<?php echo $school_data['phone'] ;?>" required>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="access"><?php echo get_phrase('Access'); ?><span class="required"> * </span></label>
                                <div class="col-md-9">
                                <select name="access" id="access" class="form-control select2" data-toggle = "select2" required>
                                    <option value=""><?php echo get_phrase('select_a_access'); ?></option>
                                    <option <?php if ($school_data['access'] == 1): ?> selected <?php endif; ?> value="1"><?php echo get_phrase('public'); ?></option>
                                    <option <?php if ($school_data['access'] == 0): ?> selected <?php endif; ?> value="0"><?php echo get_phrase('privé'); ?></option>
                                
                                </select>
                                <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_access'); ?></small>
                                </div>
                           </div>

                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="address"> <?php echo get_phrase('address') ;?></label>
                                <div class="col-md-9">
                                    <textarea class="form-control" id="address" name = "address" rows="5" required><?php echo $school_data['address'] ;?></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label"  for="access"><?php echo get_phrase('Category'); ?><span class="required"> * </span></label>
                                <div class="col-md-9">
                                    <select name="category" id="category" class="form-control select2" data-toggle = "select2" required>
                                        <option value=""><?php echo get_phrase('select_a_category'); ?></option>
                                        <?php $categories = $this->db->get_where('categories', array())->result_array(); ?>
                                        <?php foreach ($categories as $categorie): ?>
                                            <option <?php if ($school_data['category'] == $categorie['name']): ?> selected <?php endif; ?> value="<?php echo $categorie['name']; ?>"><?php echo $categorie['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_category'); ?></small>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="example-fileinput"><?php echo get_phrase('school_profile_image'); ?></label>
                                <div class="col-md-5 logo-upload-container">
                                        <div class="logo-card">
                                            <div class="logo-header">
                                                <h5><?php echo get_phrase('school_profile_image'); ?></h5>
                                            </div>
                                            <div class="logo-preview" id="school-image-preview">
                                                    <img src="<?php echo $this->user_model->get_school_image($school_data['id']). '?v=' . time(); ?>" alt="School Profile Image" class="preview-image">
                                                    <div class="logo-overlay">
                                                        <i class="fas fa-camera"></i>
                                                    </div>
                                                    
                                            </div>
                                            <div class="logo-upload-btn">
                                                <label for="school_image">
                                                    <i class="mdi mdi-cloud-upload"></i> <?php echo get_phrase('upload_an_image'); ?>
                                                </label>
                                                <input id="school_image" type="file" class="image-upload" name="school_image" accept="image/*" data-preview="school-image-preview">
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-l px-4"id="update-logos-btn"id="update-logos-btn" onclick="updateSchoolInfo()">
                                    <i class="mdi mdi-account-check"></i>
                                    <?php echo get_phrase('update_settings') ;?>
                                </button>
                             </div>
                                    
                </form>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div>
    </div>

    
<script type="text/javascript">
$(document).ready(function () {
    // Initialisation Select2
    $('select.select2:not(.normal)').each(function () { 
        $(this).select2({ dropdownParent: '#right-modal' }); 
    });

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
    $('#schoolForm').submit(function(e) {
        e.preventDefault();

        // Obtenez le texte de mise à jour traduit
         var updating_text = "<?php echo get_phrase('updating'); ?>...";
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
                    //success_notify(response.notification);
                    // Mise à jour du token CSRF
                    $('input[name="' + response.csrf.name + '"]').val(response.csrf.hash);

                 // Mise à jour dynamique des images avec cache-buster (pour forcer le rechargement des images)
                 $('.preview-image').each(function() {
                    var originalSrc = $(this).attr('src').split('?')[0];// Récupère l'URL d'origine de l'image sans la chaîne de requête
                   // $(this).attr('src', originalSrc + '?v=' + Date.now()); // Ajoute un timestamp pour éviter la mise en cache cette ligne affiche alt du l'image avant affichage du l'image apres update
                });

                // Rafraîchissement de la page après un léger délai pour s'assurer que les modifications sont appliquées
                setTimeout(function() {
                  location.reload();
                }, 3500);// Attendre 3500ms avant de recharger la page
            } else {
              error_notify('<?=js_phrase(get_phrase('action_not_allowed')); ?>')
                
            }
        },
        error: function () {
            error_notify(<?= js_phrase('an_error_occurred_during_submission'); ?>)
        }
        });
    });
});
</script>