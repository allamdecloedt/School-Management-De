<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/single-student-admission.css">


<?php $school_id = school_id(); ?>

<form method="POST" class="col-12 d-block ajaxForm" action="<?php echo route('student/create_single_student'); ?>" id = "student_admission_form" enctype="multipart/form-data">
    <!-- Champ caché pour le jeton CSRF -->
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    
    <div class="col-md-12">
        <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="name"><?php echo get_phrase('name'); ?></label>
            <div class="col-md-9">
                <input type="text" id="name" name="name" class="form-control" placeholder="name" required>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="email"><?php echo get_phrase('email'); ?></label>
            <div class="col-md-9">
                <input type="email" class="form-control" id="email" name="email" placeholder="email" required>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="password"><?php echo get_phrase('password'); ?></label>
            <div class="col-md-9">
                <input type="password" class="form-control" id="password" name="password" placeholder="password" required>
            </div>
        </div>

       

        <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="class_id"><?php echo get_phrase('class'); ?></label>
            <div class="col-md-9">
                <select name="class_id" id="class_id" class="form-control select2" data-toggle = "select2" required onchange="classWiseSection(this.value)">
                    <option value=""><?php echo get_phrase('select_a_class'); ?></option>
                    <?php $classes = $this->db->get_where('classes', array('school_id' => $school_id))->result_array(); ?>
                    <?php foreach($classes as $class){ ?>
                        <option value="<?php echo $class['id']; ?>"><?php echo $class['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="section_id"><?php echo get_phrase('section'); ?></label>
            <div class="col-md-9" id = "section_content">
                <select name="section_id" id="section_id" class="form-control select2" data-toggle = "select2" required >
                    <option value=""><?php echo get_phrase('select_section'); ?></option>
                </select>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="birthdatepicker"><?php echo get_phrase('birthday'); ?></label>
            <div class="col-md-9">
                <input type="text" class="form-control date" id="birthdatepicker" data-bs-toggle="date-picker" data-single-date-picker="true" name = "birthday"   value="<?php echo date('m/d/Y'); ?>" required>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="gender"><?php echo get_phrase('gender'); ?></label>
            <div class="col-md-9">
                <select name="gender" id="gender" class="form-control select2" data-toggle = "select2"  required>
                    <option value=""><?php echo get_phrase('select_gender'); ?></option>
                    <option value="Male"><?php echo get_phrase('male'); ?></option>
                    <option value="Female"><?php echo get_phrase('female'); ?></option>
                    <option value="Others"><?php echo get_phrase('others'); ?></option>
                </select>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="example-textarea"><?php echo get_phrase('address'); ?></label>
            <div class="col-md-9">
                <textarea class="form-control" id="example-textarea" rows="5" name = "address" placeholder="address"></textarea>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="phone"><?php echo get_phrase('phone'); ?></label>
            <div class="col-md-9">
                <input type="text" id="phone" name="phone" class="form-control" placeholder="phone" required>
            </div>
        </div>

        <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="example-fileinput"><?php echo get_phrase('student_profile_image'); ?></label>
                                <div class="col-md-3 logo-upload-container">
                                        <div class="logo-card">
                                            <div class="logo-header">
                                                <h5><?php echo get_phrase('student_profile_image'); ?></h5>
                                                
                                            </div>
                                            <div class="logo-preview" id="student-image-preview">
                                                    <img src="<?php echo $this->user_model->get_user_image($student['id']). '?v=' . time(); ?>" alt="Student Profile Image" class="preview-image">
                                                    <div class="logo-overlay">
                                                        <i class="fas fa-camera"></i>
                                                    </div>
                                            </div>
                                            <div class="logo-upload-btn">
                                                <label for="student_image">
                                                    <i class="mdi mdi-cloud-upload"></i> <?php echo get_phrase('upload_an_image'); ?>
                                                </label>
                                                <input id="student_image" type="file" class="image-upload" name="student_image" accept="image/*" data-preview="student-image-preview">
                                            </div>
                                        </div>
                                </div>
                            </div>

        <div class="text-center">
            <button type="submit" class="btn btn-secondary col-md-4 col-sm-12 mb-4"><?php echo get_phrase('add_student'); ?></button>
        </div>
    </div>
</form>

<script type="text/javascript">
$(document).ready(function() {
    $(".ajaxForm").validate();

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
 // Fonction de réinitialisation du formulaire
 var refreshForm = function(form) {
        form.trigger("reset");
    };


 // Soumission du formulaire de logo
 $(".ajaxForm").submit(function(e) {
    e.preventDefault();
    var form = $(this);
        // Appel de la fonction check() pour valider les champs
        if (!check()) { // Si check() renvoie false, on arrête la soumission
                return;
            }

         // Obtenez le texte de mise à jour traduit
         var adding_text = "<?php echo get_phrase('adding'); ?>";
         // Afficher un indicateur de chargement
         $('button[type="submit"]').prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i>'+adding_text);

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
                        // Réinitialiser le formulaire
                        refreshForm(form);
                // Rafraîchissement de la page après un léger délai pour s'assurer que les modifications sont appliquées
              
            } else {
                alert("Erreur : " + (response.notification || 'Action non autorisée'));
            }
        },
        error: function () {
            alert("Une erreur est survenue lors de l'envoi.");
        }
      });
    });
  });

  function check() {
    var name = $("#name").val();
    var email = $("#email").val();
    var password = $("#password").val();
    var parent_id = $("#parent_id").val();
    var class_id = $("#class_id").val();
    var birthday = $("#birthday").val();
    var gender = $("#gender").val();
    var address = $("#address").val();
    var phone = $("#phone").val();
    if (name == "" || email == "" || password == "" || parent_id == "" || class_id == "" ||
        birthday == "" || gender == "" || address == "" || phone == "") {
        error_notify('<?php echo get_phrase('please_select_in_all_fields !'); ?>');
        return false;
    }
    return true;
}

</script>
