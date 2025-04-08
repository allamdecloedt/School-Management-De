<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/imageProfile.css">

<!--title-->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
                <i class="mdi mdi-update title_icon"></i> <?php echo get_phrase('student_update_form'); ?>
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card pt-0">
            <div class="card-body">
                <?php $school_id = school_id(); ?>
                <?php $student = $this->db->get_where('students', array('id' => $student_id))->row_array(); ?>
                <?php 
                $enroll = $this->db->get_where('enrols', array('student_id' => $student_id))->row_array(); 
                $selected_classes = $this->db->get_where('enrols', array('student_id' => $student_id))->result_array();
                $selected_class_ids = array();
                foreach ($selected_classes as $selected_class) {
                    $selected_class_ids[] = $selected_class['class_id'];
                }             


                $selected_sections = $this->db->get_where('enrols', array('student_id' => $student_id))->result_array();
                $selected_section_ids = array();
                foreach ($selected_sections as $section) {
                    if (!isset($selected_section_ids[$section['class_id']])) {
                        $selected_section_ids[$section['class_id']] = array();
                    }
                    $selected_section_ids[$section['class_id']][] = $section['section_id'];
                }

                ?>
                <h4 class="text-center mx-0 py-2 mt-0 mb-3 px-0 text-white bg-primary"><?php echo get_phrase('update_student_information'); ?></h4>
                <form method="POST" class="col-12 d-block ajaxForm" action="<?php echo route('student/updated/'.$student_id.'/'.$student['user_id']); ?>" id = "student_update_form" enctype="multipart/form-data">
                    <!-- Champ caché pour le jeton CSRF -->
                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                   

                    <div class="col-md-12" style="margin-left: 1%;">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="name"><?php echo get_phrase('name'); ?><span class="required"> * </span></label>
                            <div class="col-md-9">
                                <input type="text" id="name" name="name" class="form-control"  value="<?php echo $this->user_model->get_user_details($student['user_id'], 'name'); ?>" placeholder="name" required>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="email"><?php echo get_phrase('email'); ?><span class="required"> * </span></label>
                            <div class="col-md-9">
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $this->user_model->get_user_details($student['user_id'], 'email'); ?>" placeholder="email" required>
                            </div>
                        </div>



                        <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="class_id"><?php echo get_phrase('class'); ?><span class="required"> * </span></label>
                                <div class="col-md-9">
                                    <select name="class_id[]" id="class_id" class=" form-control"  onchange="classWiseSectionOnStudentEdit(this.value)" multiple="multiple" required data-live-search="true">
                                        <option value=""><?php echo get_phrase('select_classes'); ?></option>
                                        <?php                                        
                                        $classes = $this->db->get_where('classes', array('school_id' => $school_id))->result_array(); 
                                        foreach($classes as $class){ ?>
                                            <option value="<?php echo $class['id']; ?>" data-class-name="<?php echo $class['name']; ?>"  <?php if (in_array($class['id'], $selected_class_ids)) echo 'selected'; ?>><?php echo $class['name']; ?></option>
                                        <?php } ?>


                                        
                                    </select>
                                </div>
                        </div>

                        <div class="form-group row mb-3">
                            <div id="section_selects_container">
                                <?php foreach($selected_class_ids as $class_id) {
                                    $class_name = $this->db->get_where('classes', array('id' => $class_id))->row()->name;

                                    ?>
                                    <div class="form-group row mb-3 section-select " id="section_select_<?php echo $class_id; ?>">
                                        <label class="col-md-3 col-form-label"><?php echo get_phrase('section_for_class') . ' ' . $class_name; ?><span class="required"> * </span></label>
                                        <div class="col-md-9">
                                            <select name="section_id_<?php echo $class_id; ?>"  id="section_id_<?php echo $class_id; ?>" class=" form-control" required>
                                                <option value=""><?php echo get_phrase('select_a_section'); ?></option>
                                                <?php 
                                                $sections = $this->db->get_where('sections', array('class_id' => $class_id))->result_array(); 
                                                foreach($sections as $section) { ?>
                                                    <option value="<?php echo $section['id']; ?>" <?php if (isset($selected_section_ids[$class_id]) && in_array($section['id'], $selected_section_ids[$class_id])) echo 'selected'; ?>>
                                                        <?php echo $section['name']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="birthdatepicker"><?php echo get_phrase('birthday'); ?><span class="required"> * </span></label>
                            <div class="col-md-9">
                                <div class=" position-relative" id="datepicker4">
                                  <!-- <input type="text" class="form-control" data-provide="datepicker"  placeholder="<?php //echo get_phrase('birthday'); ?>" data-date-autoclose="true" data-date-container="#datepicker4" name = "birthday"   value="<?php //if($this->user_model->get_user_details($student['user_id'], 'birthday') != "") echo date('m/d/Y', strtotime($this->user_model->get_user_details($student['user_id'], 'birthday'))); ?>" > -->
                                <?php 
                                    $birthday = $this->user_model->get_user_details($student['user_id'], 'birthday');
                                    $formattedBirthday = $birthday ? $birthday : ''; // Pas de conversion nécessaire
                                    ?>
                                    <input type="date" class="form-control" id="birthdatepicker" name="birthday" value="<?php echo htmlspecialchars($formattedBirthday); ?>" required>

                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="gender"><?php echo get_phrase('gender'); ?><span class="required"> * </span></label>
                            <div class="col-md-9">
                                <select name="gender" id="gender" class="form-control" required>
                                    <option value=""><?php echo get_phrase('select_gender'); ?></option>
                                    <option value="Male" <?php if($this->user_model->get_user_details($student['user_id'], 'gender') == 'Male') echo 'selected'; ?>><?php echo get_phrase('male'); ?></option>
                                    <option value="Female" <?php if($this->user_model->get_user_details($student['user_id'], 'gender') == 'Female') echo 'selected'; ?>><?php echo get_phrase('female'); ?></option>
                                    <option value="Others" <?php if($this->user_model->get_user_details($student['user_id'], 'gender') == 'Others') echo 'selected'; ?>><?php echo get_phrase('others'); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="example-textarea"><?php echo get_phrase('address'); ?><span class="required"> * </span></label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="example-textarea" rows="5" name = "address" placeholder="address" required><?php echo $this->user_model->get_user_details($student['user_id'], 'address'); ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="phone"><?php echo get_phrase('phone'); ?><span class="required"> * </span></label>
                            <div class="col-md-9">
                                <input type="text" id="phone" name="phone" class="form-control" value="<?php echo $this->user_model->get_user_details($student['user_id'], 'phone'); ?>" placeholder="phone" required>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="example-fileinput"><?php echo get_phrase('student_profile_image'); ?></label>
                                <div class="col-md-5 logo-upload-container">
                                        <div class="logo-card">
                                            <div class="logo-header">
                                                <h5><?php echo get_phrase('student_profile_image'); ?></h5>
                                            </div>
                                            <div class="logo-preview" id="student-image-preview">
                                                    <img src="<?php echo $this->user_model->get_user_image($student['user_id']). '?v=' . time(); ?>" alt="Student Profile Image" class="preview-image">
                                                    <div class="logo-overlay">
                                                        <i class="fas fa-camera"></i>
                                                    </div>
                                            </div>
                                            <div class="logo-upload-btn">
                                                <label for="student_image">
                                                    <i class="mdi mdi-cloud-upload"></i>
                                                    <?php echo get_phrase('upload_an_image'); ?>
                                                </label>
                                                <input id="student_image" type="file" class="image-upload" name="student_image" accept="image/*" data-preview="student-image-preview">
                                            </div>
                                        </div>
                                </div>
                        </div>


                        <div class="button-container">
                            <a href="<?php echo site_url("superadmin/student"); ?>" class="action-btn btn-back col-md-4 col-sm-12">
                                <i class="mdi mdi-arrow-left-bold"></i>
                                    <span class="btn-text"><?php echo get_phrase('back_to_student_list'); ?></span>
                            </a>
                            
                            <button type="submit" class="action-btn btn-update col-md-4 col-sm-12">
                                <i class="mdi mdi-account-check"></i>
                                    <span class="btn-text"><?php echo get_phrase('update_student_information'); ?></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
// var form;
// $(".ajaxForm").submit(function(e) {
//     form = $(this);
//     ajaxSubmit(e, form, refreshForm);
// });
// var refreshForm = function () {

// }

function classWiseSectionOnStudentEdit() {
        var classIds = $('#class_id').val();
        var sectionContainer = $('#section_selects_container');
        sectionContainer.empty();

        var csrf = getCsrfToken();

        if (classIds.length > 0) {
            classIds.forEach(function(classId) {
                var className = $('#class_id option[value="' + classId + '"]').data('class-name');

                $.ajax({
                    url: "<?php echo site_url('superadmin/get_sections_by_class'); ?>",
                    type: 'POST',
                    data: { class_ids: [classId], [csrf.csrfName]: csrf.csrfHash },
                    dataType: 'json',
                    success: function(response) {
                        var sections = response.sections;
                        var selectedSections = <?php echo json_encode($selected_section_ids); ?>;
                        var sectionOptions = '<option value=""><?php echo get_phrase('select_a_section'); ?></option>';
                        
                        csrf = getCsrfToken();
                        $('input[name="' + csrf.csrfName + '"]').val(csrf.csrfHash);

                        if (sections.length > 0) {
                            sections.forEach(function(section) {
                                var selected = (selectedSections[classId] && selectedSections[classId].includes(section.id)) ? 'selected' : '';
                                sectionOptions += '<option value="' + section.id + '" ' + selected + '>' + section.name + '</option>';
                            });
                        } else {
                            sectionOptions += '<option value="" disabled><?php echo get_phrase('no_section_found'); ?></option>';
                        }

                        var sectionSelect = `
                            <div class="form-group row mb-3 section-select" id="${classId}">
                                <label class="col-md-3 col-form-label"><?php echo get_phrase('section_for_class'); ?> ${className}<span class="required"> * </span></label>
                                <div class="col-md-9">
                                    <select name="section_id_${classId}" id="section_id_${classId}" class="form-control" required>
                                        ${sectionOptions}
                                    </select>
                                </div>
                            </div>
                        `;
                        sectionContainer.append(sectionSelect);
                    },
                    error: function(xhr, status, error) {
                        console.error("Erreur pour la classe ", classId, ": ", error);
                    }
                });
            });
        }
    }
// Fonction pour récupérer et retourner le token CSRF
function getCsrfToken() {
         // Récupérer le nom du token CSRF depuis le champ input caché
          var csrfName = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').attr('name');
         // Récupérer la valeur (hash) du token CSRF depuis le champ input caché
           var csrfHash = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();
         // Retourner un objet contenant le nom du token et sa valeur
         return { csrfName: csrfName, csrfHash: csrfHash };
      }

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

    $(".ajaxForm").submit(function(e) {
        e.preventDefault();
       var form = $(this);
        // Obtenez le texte de mise à jour traduit
        var updating_text = "<?php echo get_phrase('updating'); ?>";
         // Afficher un indicateur de chargement
         $('button[type="submit"]').prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i>'+updating_text);
         // Récupérer le token CSRF avant l'envoi
         var csrf = getCsrfToken(); // Appel de la fonction pour obtenir le token
         const formData = new FormData(this);// Crée une nouvelle instance de FormData en passant l'élément du formulaire courant


        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                //console.log('AJAX Response:', response); // Log the entire response object
                if (response.status){ // Vérifie si la mise à jour a réussi
                    success_notify(response.notification);
                // Met à jour le token CSRF
                $('input[name="' + response.csrf.name + '"]').val(response.csrf.hash);
                
                // Rafraîchit la page après 3 secondes (le temps de voir la notification)
                setTimeout(function() {
                                location.reload();
                            }, 3500);
                // Mise à jour dynamique des images avec cache-buster (pour forcer le rechargement des images)
                 $('.preview-image').each(function() {
                    var originalSrc = $(this).attr('src').split('?')[0];// Récupère l'URL d'origine de l'image sans la chaîne de requête
                    // $(this).attr('src', originalSrc + '?v=' + Date.now());// Ajoute un timestamp pour éviter la mise en cache cette ligne affiche alt du l'image avant affichage du l'image apres update
                
                });
              
            } else {
                error_notify(response.notification);
        
                // Re-enable submit button
                $('button[type="submit"]').prop('disabled', false)
                .html('<i class="mdi mdi-account-check"></i> <?= get_phrase("update_student_information"); ?>'); 
            }
                    $('input[name="' + response.csrf.name + '"]').val(response.csrf.hash);
                },
             error: function(xhr) {
                error_notify('<?= get_phrase("request_failed"); ?>: ' + xhr.statusText);
                $('button[type="submit"]').prop('disabled', false)
                .html('<i class="mdi mdi-account-check"></i> <?= get_phrase("update_student_information"); ?>'); 
            }
        });
    });

   
});

</script>

