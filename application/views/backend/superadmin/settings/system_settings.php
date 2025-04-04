<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/systemSettings.css">

<div class="row">
  <div class="col-xl-7 col-lg-12 col-md-12 col-sm-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="header-title d-inline-block"><?php echo get_phrase('system_settings') ;?><span class="required"> * </span></h4>
        <form method="POST" class="col-12 systemAjaxForm" action="<?php echo route('system_settings/update') ;?>" id = "system_settings">
          <!-- Champ caché pour le jeton CSRF -->
         <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
          <div class="col-12">
            <div class="form-group row mb-3">
              <label class="col-md-3 col-form-label" for="system_name"> <?php echo get_phrase('system_name') ;?><span class="required"> * </span></label>
              <div class="col-md-9">
                <input type="text" id="system_name" name="system_name" class="form-control"  value="<?php echo get_settings('system_name') ;?>" required>
              </div>
            </div>

            <div class="form-group row mb-3">
              <label class="col-md-3 col-form-label" for="system_title"><?php echo get_phrase('title') ;?><span class="required"> * </span></label>
              <div class="col-md-9">
                <input type="text" id="system_title" name="system_title" class="form-control"  value="<?php echo get_settings('system_title') ;?>" required>
              </div>
            </div>

            <div class="form-group row mb-3">
              <label class="col-md-3 col-form-label" for="system_email"> <?php echo get_phrase('school_email') ;?><span class="required"> * </span></label>
              <div class="col-md-9">
                <input type="email" id="system_email" name="system_email" class="form-control"  value="<?php echo get_settings('system_email') ;?>" required>
              </div>
            </div>
            <div class="form-group row mb-3">
              <label class="col-md-3 col-form-label" for="phone"> <?php echo get_phrase('phone') ;?><span class="required"> * </span></label>
              <div class="col-md-9">
                <input type="text" id="phone" name="phone" class="form-control"  value="<?php echo get_settings('phone') ;?>" required>
              </div>
            </div>

            <!-- <div class="form-group row mb-3">
              <label class="col-md-3 col-form-label" for="fax"> <?php //echo get_phrase('fax') ;?><span class="required"> * </span></label>
              <div class="col-md-9">
                <input type="text" id="fax" name="fax" class="form-control"  value="<?php //echo get_settings('fax') ;?>" required>
              </div>
            </div> -->

            <div class="form-group row mb-3">
              <label class="col-md-3 col-form-label" for="address"> <?php echo get_phrase('address') ;?><span class="required"> * </span></label>
              <div class="col-md-9">
                <textarea class="form-control" id="address" name = "address" rows="5" required><?php echo get_settings('address') ;?></textarea>
              </div>
            </div>
            <div class="form-group row mb-3">
              <label class="col-md-3 col-form-label" for="purchase_code"> <?php echo get_phrase('purchase_code') ;?><span class="required"> * </span></label>
              <div class="col-md-9">
                <input type="text" id="purchase_code" name="purchase_code" class="form-control"  value="<?php echo get_settings('purchase_code') ;?>" required>
              </div>
            </div>
            <div class="form-group row mb-3">
              <label class="col-md-3 col-form-label" for="timezone"> <?php echo get_phrase('timezone') ;?></label>

              <div class="col-md-9">
                <select class="form-control select2" data-bs-toggle="select2" id="timezone" name="timezone">
                  <?php $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL); ?>
                  <?php foreach ($tzlist as $tz): ?>
                    <option value="<?php echo $tz ;?>" <?php if(get_settings('timezone') == $tz) echo 'selected'; ?>><?php echo $tz ;?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="form-group row mb-3">
              <label class="col-md-3 col-form-label" for="footer_text"> <?php echo get_phrase('footer_text') ;?><span class="required"> * </span></label>
              <div class="col-md-9">
                <input type="text" id="footer_text" name="footer_text" class="form-control"  value="<?php echo get_settings('footer_text') ;?>" required>
              </div>
            </div>

            <div class="form-group row mb-3">
              <label class="col-md-3 col-form-label" for="footer_link"><?php echo get_phrase('footer_link') ;?><span class="required"> * </span></label>
              <div class="col-md-9">
                <input type="text" id="footer_link" name="footer_link" class="form-control"  value="<?php echo get_settings('footer_link') ;?>" required>
              </div>
            </div>

            <?php if(addon_status('online_courses')): ?>
              <div class="form-group row mb-3">
                <label class="col-md-3 col-form-label" for="youtube_api_key"><?php echo get_phrase('youtube_api_key') ;?></label>
                <div class="col-md-9">
                  <input type="text" id="youtube_api_key" placeholder="<?php echo get_phrase('youtube_api_key') ;?>" name="youtube_api_key" class="form-control"  value="<?php echo get_settings('youtube_api_key') ;?>">
                </div>
              </div>

              <div class="form-group row mb-3">
                <label class="col-md-3 col-form-label" for="vimeo_api_key"><?php echo get_phrase('vimeo_api_key') ;?></label>
                <div class="col-md-9">
                  <input type="text" id="vimeo_api_key" placeholder="<?php echo get_phrase('vimeo_api_key') ;?>" name="vimeo_api_key" class="form-control"  value="<?php echo get_settings('vimeo_api_key') ;?>">
                </div>
              </div>
            <?php endif; ?>

            <div class="text-center">
              <button type="submit" class="btn btn-secondary col-xl-4 col-lg-4 col-md-12 col-sm-12" onclick="updateSystemInfo($('#system_name').val())"><?php echo get_phrase('update_settings') ;?></button>
            </div>
          </div>
        </form>

      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div>
  <div class="col-xl-5 col-lg-12 col-md-12 col-sm-12">
          <div class="card">
            <div class="card-body">
              <h4 class="header-title"><?php echo get_phrase('product_update') ;?></h4>
              <form action="<?php echo site_url('updater/update'); ?>" method="post" enctype="multipart/form-data">
                    <!-- Champ caché pour le jeton CSRF -->
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <label for="file_name"><?php echo get_phrase('file'); ?></label>
                <input type="file" class="form-control" name="file_name" id="file_name">
                <button class="btn btn-secondary mt-3 float-end"><?php echo get_phrase('update'); ?></button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-12">
        <div class="card">
        <div class="card-body">
          <h4 class="header-title"><?php echo get_phrase('system_logo') ;?></h4>
          <form method="POST" class="col-12 systemLogoAjaxForm" action="<?php echo route('system_settings/logo_update') ;?>" id="system_settings" enctype="multipart/form-data">
            <!-- CSRF token field -->
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
            
            <div class="logo-upload-container">
              <div class="row">
                <!-- Regular Logo -->
                <div class="col-lg-3 col-md-6 mb-4">
                  <div class="logo-card">
                    <div class="logo-header">
                      <h5><?php echo get_phrase('regular_logo'); ?></h5>
                      <span class="size-badge">600 × 150</span>
                    </div>
                    <div class="logo-preview" id="dark-logo-preview">
                      <img src="<?php echo $this->settings_model->get_logo_dark(). '?v=' . time(); ?>" alt="Dark Logo" class="preview-image">
                      <div class="logo-overlay">
                        <i class="fas fa-camera"></i>
                      </div>
                    </div>
                    <div class="logo-upload-btn">
                      <label for="dark_logo">
                        <i class="mdi mdi-cloud-upload"></i> <?php echo get_phrase('upload_logo'); ?>
                      </label>
                      <input id="dark_logo" type="file" class="image-upload" name="dark_logo" accept="image/*" data-preview="dark-logo-preview">
                    </div>
                  </div>
                </div>

                <!-- Light Logo -->
                <div class="col-lg-3 col-md-6 mb-4">
                  <div class="logo-card">
                    <div class="logo-header">
                      <h5><?php echo get_phrase('light_logo'); ?></h5>
                      <span class="size-badge">600 × 150</span>
                    </div>
                    <div class="logo-preview" id="light-logo-preview">
                      <img src="<?php echo $this->settings_model->get_logo_light(). '?v=' . time();  ?>" alt="Light Logo" class="preview-image">
                      <div class="logo-overlay">
                        <i class="fas fa-camera"></i>
                      </div>
                    </div>
                    <div class="logo-upload-btn">
                      <label for="light_logo">
                        <i class="mdi mdi-cloud-upload"></i> <?php echo get_phrase('upload_logo'); ?>
                      </label>
                      <input id="light_logo" type="file" class="image-upload" name="light_logo" accept="image/*" data-preview="light-logo-preview">
                    </div>
                  </div>
                </div>

                <!-- Small Logo -->
                <div class="col-lg-3 col-md-6 mb-4">
                  <div class="logo-card">
                    <div class="logo-header">
                      <h5><?php echo get_phrase('small_logo'); ?></h5>
                      <span class="size-badge">80 × 80</span>
                    </div>
                    <div class="logo-preview small-preview" id="small-logo-preview">
                      <img src="<?php echo $this->settings_model->get_logo_light('small'). '?v=' . time();  ?>" alt="Small Logo" class="preview-image">
                      <div class="logo-overlay">
                        <i class="fas fa-camera"></i>
                      </div>
                    </div>
                    <div class="logo-upload-btn">
                      <label for="small_logo">
                        <i class="mdi mdi-cloud-upload"></i> <?php echo get_phrase('upload_small_logo'); ?>
                      </label>
                      <input id="small_logo" type="file" class="image-upload" name="small_logo" accept="image/*" data-preview="small-logo-preview">
                    </div>
                  </div>
                </div>

                <!-- Favicon -->
                <div class="col-lg-3 col-md-6 mb-4">
                  <div class="logo-card">
                    <div class="logo-header">
                      <h5><?php echo get_phrase('favicon'); ?></h5>
                      <span class="size-badge">80 × 80</span>
                    </div>
                    <div class="logo-preview small-preview" id="favicon-preview">
                      <img src="<?php echo $this->settings_model->get_favicon(). '?v=' . time();  ?>" alt="Favicon" class="preview-image">
                      <div class="logo-overlay">
                        <i class="fas fa-camera"></i>
                      </div>
                    </div>
                    <div class="logo-upload-btn">
                      <label for="favicon">
                        <i class="mdi mdi-cloud-upload"></i> <?php echo get_phrase('upload_favicon'); ?>
                      </label>
                      <input id="favicon" type="file" class="image-upload" name="favicon" accept="image/*" data-preview="favicon-preview">
                    </div>
                  </div>
                </div>
              </div>
             
              <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-5" id="update-logos-btn" onclick="updateSystemLogo()">
                  <i class="mdi mdi-account-check"></i> <?php echo get_phrase('update_logo') ;?>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
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
         // Récupérer le nom du token CSRF depuis le champ input caché
          var csrfName = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').attr('name');
         // Récupérer la valeur (hash) du token CSRF depuis le champ input caché
           var csrfHash = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();
         // Retourner un objet contenant le nom du token et sa valeur
         return { csrfName: csrfName, csrfHash: csrfHash };
      }


 // Soumission du formulaire de logo
 $(".systemLogoAjaxForm").submit(function(e) {
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
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            if (response.status) { // Vérifie si la mise à jour a réussi
                // Met à jour le token CSRF
                $('input[name="' + response.csrf.name + '"]').val(response.csrf.hash);

                // Mise à jour dynamique des images avec cache-buster (pour forcer le rechargement des images)
                 $('.preview-image').each(function() {
                    var originalSrc = $(this).attr('src').split('?')[0];// Récupère l'URL d'origine de l'image sans la chaîne de requête
                    // $(this).attr('src', originalSrc + '?v=' + Date.now());// Ajoute un timestamp pour éviter la mise en cache cette ligne affiche alt du l'image avant affichage du l'image apres update
                
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
