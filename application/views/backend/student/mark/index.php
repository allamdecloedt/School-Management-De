<?php $student_data = $this->user_model->get_logged_in_student_details();  ?>
<!--title-->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title d-inline-block"> <i class="mdi mdi-format-list-numbered title_icon"></i> <?php echo get_phrase('manage_marks'); ?> </h4>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="row mt-3">
                <div class="col-md-1 mb-1"></div>
                <div class="col-md-2 mb-1">
                    <select name="exam" id="exam_id" class="form-control select2" data-toggle = "select2" required onchange="examsWiseClass(this.value)">
                        <option value=""><?php echo get_phrase('select_a_exam'); ?></option>
                        <?php 
                        // $school_id = school_id();
                        $user_id = $this->session->userdata('user_id');
                        $student_data = $this->db->get_where('students', array('user_id' => $user_id))->result_array();
                        $school_ids = array();

                        foreach ($student_data as $student_data) {
                            $school_ids[] = $student_data['school_id'];
                       
                        $exams = $this->db->get_where('exams', array('school_id' => $student_data['school_id'], 'session' => active_session()))->result_array();
                        foreach($exams as $exam){ 
                            
                            ?>
                            <option value="<?php echo $exam['id']; ?>"><?php echo $exam['name'];?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2 mb-1">
                    <select name="class" id="class_id_mark" class="form-control select2" data-toggle = "select2" required onchange="classWiseSection(this.value)">
                        <option value=""><?php echo get_phrase('select_a_class'); ?></option>
                    </select>
                </div>
                <div class="col-md-2 mb-1">
                    <select name="section" id="section_id" class="form-control select2" data-toggle = "select2" required>
                        <option value=""><?php echo get_phrase('select_section'); ?></option>
                        <option value="<?php echo $student_data['section_id']; ?>"><?php echo $student_data['section_name']; ?></option>
                    </select>
                </div>
                <!-- <div class="col-md-2 mb-1">
                    <select name="subject" id="subject_id" class="form-control select2" data-toggle = "select2" required>
                        <option value=""><?php echo get_phrase('select_subject'); ?></option>
                    </select>
                </div> -->
                <div class="col-md-2">
                    <button class="btn btn-block btn-secondary" onclick="filter_marks()" ><?php echo get_phrase('filter'); ?></button>
                </div>
            </div>
            <div class="card-body mark_content">
                <div class="empty_box text-center">
                    <img class="mb-3" width="150px" src="<?php echo base_url('assets/backend/images/empty_box.png'); ?>" />
                    <br>
                    <span class=""><?php echo get_phrase('no_data_found'); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$('document').ready(function(){
    $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); }); //initSelect2(['#class_id', '#exam_id', '#section_id', '#subject_id']);
});

function examsWiseClass(examId) {

    $.ajax({
        url: "<?php echo route('exam_class/list/'); ?>"+examId,
        success: function(response){
            $('#class_id_mark').html(response);
      
        }
    });
}
function classWiseSection(classId) { 
    $.ajax({
        url: "<?php echo route('section/list/'); ?>"+classId,
        success: function(response){
            $('#section_id').html(response);
            classWiseSubject(classId);
        }
    });
}

function classWiseSubject(classId) {
    $.ajax({
        url: "<?php echo route('class_wise_subject/'); ?>"+classId,
        success: function(response){
            $('#subject_id').html(response);
        }
    });
}

function filter_marks(){
    var exam = $('#exam_id').val();
    var class_id = $('#class_id_mark').val();
    var section_id = $('#section_id').val();
    // var subject = $('#subject_id').val();
    // Récupérer le nom et la valeur du jeton CSRF depuis l'input caché
    var csrfName = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').attr('name');
    var csrfHash = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();

    if(class_id != "" && section_id != "" && exam != ""){
        $.ajax({
            type: 'POST',
            url: '<?php echo route('mark/list') ?>',
            data: {class_id : class_id, section_id : section_id, exam : exam , [csrfName]: csrfHash},
            dataType: 'json',
            success: function(response){
                $('.mark_content').html(response.status);
                // Mettre à jour le jeton CSRF avec le nouveau jeton renvoyé dans la réponse
                var newCsrfName = response.csrf.csrfName;
                var newCsrfHash = response.csrf.csrfHash;
                $('input[name="' + newCsrfName + '"]').val(newCsrfHash); // Mise à jour du token CSRF
            }
        });
    }else{
        toastr.error('<?php echo get_phrase('please_select_in_all_fields !'); ?>');
    }
}
</script>
