<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/edit-design-button.css">

<?php $school_id = school_id(); ?>
<form method="POST" class="d-block ajaxForm responsive_media_query" action="<?php echo route('attendance/take_attendance'); ?>" style="min-width: 300px; max-width: 400px;">
    <!-- Champ caché pour le jeton CSRF -->
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    
    <div class="form-group row">
        <div class="col-md-12">
            <label for="date_on_taking_attendance"><?php echo get_phrase('date'); ?></label>
            <input type="text" class="form-control date" id="date_on_taking_attendance" data-bs-toggle="date-picker" data-single-date-picker="true" name = "date" value="" required>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
            <label  for="class_id_on_taking_attendance"><?php echo get_phrase('class'); ?></label>
            <select name="class_id" id="class_id_on_taking_attendance" class="form-control select2" data-bs-toggle="select2" onchange="classWiseSectionOnTakingAttendance(this.value)" required>
                <option value=""><?php echo get_phrase('select_a_class'); ?></option>
                <?php $classes = $this->db->get_where('classes', array('school_id' => $school_id))->result_array(); ?>
                <?php foreach($classes as $class): ?>
                    <option value="<?php echo $class['id']; ?>"><?php echo $class['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-group row mb-3">
        <div class="col-md-12" id = "section_content_2">
            <label for="section_id_on_taking_attendance"><?php echo get_phrase('section'); ?></label>
            <select name="section_id" id="section_id_on_taking_attendance" class="form-control select2" data-bs-toggle="select2" required >
                <option value=""><?php echo get_phrase('select_section'); ?></option>
            </select>
        </div>
    </div>


    <div class="row" id = "student_content" style="margin-left: 2px;">
    </div>

    <div class='row'>
        <div class="form-group col-md-12" id="showStudentDiv">
            <a class="btn btn-primary btn-l px-4" id="update-btn"  onclick="getStudentList()" style="color: #fff;" disabled><i class="mdi mdi-eye"></i><?php echo get_phrase('show_student_list'); ?></a>
        </div>
    </div>
    <div class="form-group col-md-12 mt-4" id = "updateAttendanceDiv" style="display: none;">
        <button class="btn btn-primary btn-l px-4" id="update-btn" type="submit"><i class="mdi mdi-account-check"></i><?php echo get_phrase('update_attendance'); ?></button>
    </div>
</form>

<script>

    $('document').ready(function(){
        $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); }); //initSelect2(['#class_id_on_taking_attendance', '#section_id_on_taking_attendance']);

        $('#date_on_taking_attendance').change(function(){
            $('#showStudentDiv').show();
            $('#updateAttendanceDiv').hide();
            $('#student_content').hide();
        });
        $('#class_id_on_taking_attendance').change(function(){
            $('#showStudentDiv').show();
            $('#updateAttendanceDiv').hide();
            $('#student_content').hide();
        });
        $('#section_id_on_taking_attendance').change(function(){
            $('#showStudentDiv').show();
            $('#updateAttendanceDiv').hide();
            $('#student_content').hide();
        });

$(".ajaxForm").validate({}); // Jquery form validation initialization
  $(".ajaxForm").submit(function(e) {
      
      e.preventDefault(); // Bloque le comportement normal
      var form = $(this);
      //ajaxSubmit(e, form, showAllGrades);
      function getCsrfToken() {
       // Récupérer le nom du token CSRF depuis le champ input caché
        var csrfName = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').attr('name');
       // Récupérer la valeur (hash) du token CSRF depuis le champ input caché
         var csrfHash = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();
       // Retourner un objet contenant le nom du token et sa valeur
       return { csrfName: csrfName, csrfHash: csrfHash };
    }
         // Cible uniquement le bouton de ce formulaire
      var submitButton = $(this).find('button[type="submit"]');
      var adding_text = "<?php echo get_phrase('updating'); ?>...";
      
      // Désactive et met à jour uniquement ce bouton
      submitButton.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i>'+adding_text);
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
              success_notify(response.notification);
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

    $('#date_on_taking_attendance').daterangepicker();

    function classWiseSectionOnTakingAttendance(classId) {
        $.ajax({
            url: "<?php echo route('section/list/'); ?>"+classId,
            success: function(response){
                $('#section_id_on_taking_attendance').html(response);
            }
        });
    }

    function getStudentList() {
        var date = $('#date_on_taking_attendance').val();
        var class_id = $('#class_id_on_taking_attendance').val();
        var section_id = $('#section_id_on_taking_attendance').val();
        // Récupérer le nom et la valeur du jeton CSRF depuis l'input caché
        var csrfName = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').attr('name');
        var csrfHash = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();

        if(date != '' && class_id != '' && section_id != ''){
            $.ajax({
                type : 'POST',
                url : '<?php echo route('attendance/student/'); ?>',
                data: {date : date, class_id : class_id, section_id : section_id , [csrfName]: csrfHash},
                dataType: 'json',
                success : function(response) {
                    $('#student_content').show();
                    $('#student_content').html(response.status);
                    $('#showStudentDiv').hide();
                    $('#updateAttendanceDiv').show();
                        // Mettre à jour le jeton CSRF avec le nouveau jeton renvoyé dans la réponse
                    var newCsrfName = response.csrfName;
                    var newCsrfHash = response.csrfHash;
                    $('input[name="' + newCsrfName + '"]').val(newCsrfHash); // Mise à jour du token CSRF
                }
            });
        }else{
            toastr.error('<?php echo get_phrase('please_select_in_all_fields !'); ?>');
        }
    }
</script>
