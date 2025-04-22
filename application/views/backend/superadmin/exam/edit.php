
<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/edit-design-button.css">

<?php $exams = $this->db->get_where('exams', array('id' => $param1))->result_array(); ?>
<?php foreach($exams as $exam){ ?>
  <form method="POST" class="d-block ajaxForm" action="<?php echo route('exam/update/'.$param1); ?>">

<?php 
$exams = $this->db->get_where('exams', array('id' => $param1))->result_array(); 
$school_id = school_id();
$classes = $this->db->get_where('classes', array('school_id' => $school_id))->result_array();
?>

<?php foreach($exams as $exam): ?>
<form method="POST" class="d-block ajaxForm" action="<?php echo route('exam/update/'.$param1); ?>">

    <!-- Champ caché pour le jeton CSRF -->
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" id="csrf_token" />
    
    <div class="form-row">

        <div class="form-group mb-1">
            <label for="exam_name"><?php echo get_phrase('exam_name'); ?><span class="required"> * </span></label>
            <input type="text" value="<?php echo $exam['name']; ?>" class="form-control" id="exam_name" name="exam_name" placeholder="name" required>
            <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_exam_name'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="starting_date"><?php echo get_phrase('date'); ?><span class="required"> * </span></label>
            <input type="datetime-local" class="form-control" id="starting_date" name="starting_date" 
                   value="<?php echo date('Y-m-d\TH:i', strtotime($exam['starting_date'])); ?>" required>
            <small id="date_help" class="form-text text-muted"><?php echo get_phrase('provide_date_and_time'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="class_id"><?php echo get_phrase('class'); ?><span class="required"> * </span></label>
            <select class="form-control" id="class_id" name="class_id" required>
                <option value=""><?php echo get_phrase('select_class'); ?></option>
                <?php foreach ($classes as $class): ?>
                    <option value="<?php echo $class['id']; ?>" 
                            <?php echo $class['id'] == $exam['class_id'] ? 'selected' : ''; ?>>
                        <?php echo $class['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small id="class_help" class="form-text text-muted"><?php echo get_phrase('select_a_class'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="section_id"><?php echo get_phrase('section'); ?><span class="required"> * </span></label>
            <select class="form-control" id="section_id" name="section_id" required>
                <option value=""><?php echo get_phrase('select_section'); ?></option>
                <?php
                $sections = $this->db->get_where('sections', array('class_id' => $exam['class_id']))->result_array();
                foreach ($sections as $section): ?>
                    <option value="<?php echo $section['id']; ?>" 
                            <?php echo $section['id'] == $exam['section_id'] ? 'selected' : ''; ?>>
                        <?php echo $section['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small id="section_help" class="form-text text-muted"><?php echo get_phrase('select_a_section'); ?></small>
        </div>

        <div class="form-group col-md-12">
            <button class="btn btn-block btn-primary btn-l px-4 " id="update-btn" type="submit"><i class="mdi mdi-account-check"></i><?php echo get_phrase('update_exam'); ?></button>
        </div>

    </div>
</form>
<?php endforeach; ?>

<script>

 $(document).ready(function() {

  function getCsrfToken() {
         // Récupérer le nom du token CSRF depuis le champ input caché
          var csrfName = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').attr('name');
         // Récupérer la valeur (hash) du token CSRF depuis le champ input caché
           var csrfHash = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();
         // Retourner un objet contenant le nom du token et sa valeur
         return { csrfName: csrfName, csrfHash: csrfHash };
      }

    // Charger dynamiquement les sections lorsque la classe change
    $('#class_id').on('change', function() {
        var class_id = $(this).val();
        var csrf_name = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var csrf_token = $('#csrf_token').val(); // Récupérer le jeton CSRF dynamiquement

        if (class_id) {
            $.ajax({
                url: '<?php echo site_url('admin/get_sections_by_class'); ?>',
                type: 'POST',
                data: {
                    class_id: class_id,
                    [csrf_name]: csrf_token
                },
                success: function(response) {
                    var data = JSON.parse(response);

                    // Vérifier si la session a expiré
                    if (data.status === 'error') {
                        alert(data.message);
                        window.location.href = '<?php echo site_url('login'); ?>';
                        return;
                    }

                    // Récupérer les sections
                    var sections = data.sections;
                    var sectionSelect = $('#section_id');
                    sectionSelect.empty();
                    sectionSelect.append('<option value=""><?php echo get_phrase('select_section'); ?></option>');
                    if (sections.length > 0) {
                        $.each(sections, function(index, section) {
                            sectionSelect.append('<option value="' + section.id + '">' + section.name + '</option>');
                        });
                    }

                    // Mettre à jour le jeton CSRF
                    $('#csrf_token').val(data.csrf.csrfHash);
                },
                error: function(xhr, status, error) {
                    console.log('Erreur AJAX : ', status, error);
                    console.log('Réponse serveur : ', xhr.responseText);
                    alert('Erreur lors du chargement des sections.');
                }
            });
        } else {
            $('#section_id').empty();
            $('#section_id').append('<option value=""><?php echo get_phrase('select_section'); ?></option>');
        }
    });

    // Déclencher le changement initial pour charger les sections
    $('#class_id').trigger('change');

//     // Mettre à jour le jeton CSRF après chaque soumission AJAX
//     $(".ajaxForm").submit(function(e) {
//         var form = $(this);
//         ajaxSubmit(e, form, function() {
            
           
//     });

 // Soumission du formulaire de logo
 // ——— Nouvelle fonction ajaxSubmit ———
  function ajaxSubmit(e, form, callback) {
    e.preventDefault();

    // désactivation du bouton
    var btn = form.find('button[type="submit"]'),
        updating = "<?= get_phrase('updating'); ?>...";
    btn.prop('disabled', true)
       .html('<i class="mdi mdi-loading mdi-spin"></i>' + updating);

    // préparation des données
    var csrf    = getCsrfToken(),
        payload = new FormData(form[0]);

    // appel AJAX principal
    $.ajax({
      url: form.attr('action'),
      type: 'POST',
      data: payload,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function(response) {
        if (response.status) {
          // mise à jour du token depuis la réponse
          $('input[name="'+response.csrf.name+'"]').val(response.csrf.hash);

          // **ici** on appelle ton callback (showAllExams)
          if (typeof callback === 'function') {
            callback();
          }

          // reload différé si tu le souhaites
          setTimeout(function(){
            location.reload();
          }, 3500);// Attendre 3500ms avant de recharger la page

            } else {
              error_notify('<?= js_phrase(get_phrase('action_not_allowed')); ?>')
                
            }
        },
        error: function () {
          error_notify(<?= js_phrase(get_phrase('an_error_occurred_during_submission')); ?>)
        }
     
   // Mettre à jour le jeton CSRF après la soumission
       $.ajax({
        url: '<?= site_url('admin/get_csrf_token'); ?>',
        type: 'GET',
        success: function(raw) {
          var d = JSON.parse(raw);
          $('#csrf_token').val(d.csrf_hash);
        }
      });
    }


  // ——— Binding : on utilise ajaxSubmit avec showAllExams ———
  $(".ajaxForm").submit(function(e) {
    ajaxSubmit(e, $(this), showAllExams);
  });
 
    // Initialisation de la validation jQuery
    $(".ajaxForm").validate({});
});
</script>
