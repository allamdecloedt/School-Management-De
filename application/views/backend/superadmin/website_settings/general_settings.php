<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/generalSettings.css">

<div class="card">
  <div class="card-body">
    <h4 class="header-title"><?php echo get_phrase('general_settings') ;?></h4>
    <form method="POST" class="col-12 generalSettingsAjaxForm" action="<?php echo route('website_update/general_settings') ;?>" id = "general_settings">
      <!-- Champ caché pour le jeton CSRF -->
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    
      <div class="row justify-content-left">
        < class="col-12">
          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="website_title"> <?php echo get_phrase('website_title') ;?></label>
            <div class="col-md-9">
              <input type="text" id="website_title" name="website_title" class="form-control"  value="<?php echo get_frontend_settings('website_title') ;?>" required>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="social_links"> <?php echo get_phrase('social_links') ;?></label>
            <div class="col-md-9">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="mdi mdi-facebook"></i></span>
                </div>
                <input type="text" class="form-control" name="facebook_link" value="<?php echo get_frontend_settings('facebook'); ?>">
              </div>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for=""></label>
            <div class="col-md-9">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="mdi mdi-twitter"></i></span>
                </div>
                <input type="text" class="form-control" name="twitter_link" value="<?php echo get_frontend_settings('twitter'); ?>">
              </div>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for=""></label>
            <div class="col-md-9">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="mdi mdi-linkedin"></i></span>
                </div>
                <input type="text" class="form-control" name="linkedin_link" value="<?php echo get_frontend_settings('linkedin'); ?>">
              </div>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for=""></label>
            <div class="col-md-9">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="mdi mdi-google"></i></span>
                </div>
                <input type="text" class="form-control" name="google_link" value="<?php echo get_frontend_settings('google'); ?>">
              </div>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for=""></label>
            <div class="col-md-9">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="mdi mdi-youtube"></i></span>
                </div>
                <input type="text" class="form-control" name="youtube_link" value="<?php echo get_frontend_settings('youtube'); ?>">
              </div>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for=""></label>
            <div class="col-md-9">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="mdi mdi-instagram"></i></span>
                </div>
                <input type="text" class="form-control" name="instagram_link" value="<?php echo get_frontend_settings('instagram'); ?>">
              </div>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="homepage_note_title"> <?php echo get_phrase('homepage_note_title') ;?></label>
            <div class="col-md-9">
              <input type="text" id="homepage_note_title" name="homepage_note_title" class="form-control"  value="<?php echo get_frontend_settings('homepage_note_title') ;?>" required>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="homepage_note_description"> <?php echo get_phrase('homepage_note_description') ;?></label>
            <div class="col-md-9">
              <textarea name="homepage_note_description" id="homepage_note_description" class="form-control" rows="8" cols="80"><?php echo get_frontend_settings('homepage_note_description'); ?></textarea>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="copyright_text"> <?php echo get_phrase('copyright_text') ;?></label>
            <div class="col-md-9">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="mdi mdi-copyright"></i></span>
                </div>
                <input type="text" class="form-control" name="copyright_text" value="<?php echo get_frontend_settings('copyright_text'); ?>">
              </div>
            </div>
          </div>

              <!--Header Logo -->
          <div class="form-group row mb-3">
              <label class="col-md-3 col-form-label" for="example-fileinput"><?php echo get_phrase('header_logo'); ?></label>
                <div class="col-md-4 logo-upload-container">
                  <div class="logo-card">
                    <div class="logo-header">
                          <h5><?php echo get_phrase('header_logo'); ?></h5>
                          <span class="size-badge">80 × 80</span>
                    </div>

                    <div class="logo-preview small-preview" id="header-preview">
                      <img src="<?php echo $this->frontend_model->get_header_logo(). '?v=' . time();  ?>" alt="Header Logo" class="preview-image">
                      <div class="logo-overlay">
                        <i class="fas fa-camera"></i>
                      </div>
                    </div>
                      <div class="logo-upload-btn">
                            <label for="header">
                              <i class="mdi mdi-cloud-upload"></i> <?php echo get_phrase('upload_header_logo'); ?>
                            </label>
                            <input id="header" type="file" class="image-upload" name="header_logo" accept="image/*" data-preview="header-preview">
                      </div>
                  </div>
              </div>
          </div>
                  <!--Footer Logo -->
            <div class="form-group row mb-3">
                <label class="col-md-3 col-form-label" for="example-fileinput"><?php echo get_phrase('footer_logo'); ?></label>
                  <div class="col-md-4 logo-upload-container">
                    <div class="logo-card">
                      <div class="logo-footer">
                            <h5><?php echo get_phrase('footer_logo'); ?></h5>
                            <span class="size-badge">80 × 80</span>
                      </div>

                        <div class="logo-preview small-preview" id="footer-preview">
                          <img src="<?php echo $this->frontend_model->get_footer_logo(). '?v=' . time();  ?>" alt="Footer Logo" class="preview-image">
                          <div class="logo-overlay">
                            <i class="fas fa-camera"></i>
                          </div>
                        </div>
                          <div class="logo-upload-btn">
                                <label for="footer">
                                  <i class="mdi mdi-cloud-upload"></i> <?php echo get_phrase('upload_footer_logo'); ?>
                                </label>
                                <input id="footer" type="file" class="image-upload" name="footer_logo" accept="image/*" data-preview="footer-preview">
                          </div>
                    </div>
                 </div>
            </div>

            <div class="text-center">
              <button type="submit" class="btn btn-secondary col-xl-4 col-lg-4 col-md-12 col-sm-12" onclick="updateGeneralSettings()">
                  <i class="mdi mdi-account-check"></i>
                    <?php echo get_phrase('update_settings') ;?>
              </button>
            </div>
        </div>
      </div>
    </form>
  </div> <!-- end card body-->
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#homepage_note_description').summernote();


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
 $(".generalSettingsAjaxForm").submit(function(e) {
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
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            if (response.status) { // Vérifie si la mise à jour a réussi
               // Affichage du message de succès 
               // success_notify(response.notification);
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