<?php
$student_data = $this->user_model->get_logged_in_student_details();
$user_id = $this->session->userdata('user_id');
$session_id = active_session();

// Récupérer les informations des inscriptions de l'étudiant connecté
$this->db->select('enrols.class_id, enrols.section_id, enrols.school_id, classes.name as class_name, sections.name as section_name');
$this->db->from('enrols');
$this->db->join('students', 'students.id = enrols.student_id', 'left');
$this->db->join('classes', 'classes.id = enrols.class_id', 'left');
$this->db->join('sections', 'sections.id = enrols.section_id', 'left');
$this->db->where('students.user_id', $user_id);
$this->db->where('enrols.session', $session_id);
$enrolments = $this->db->get()->result_array();
?>

<!-- Title -->
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body py-2">
                <h4 class="page-title d-inline-block"> <i class="mdi mdi-format-list-numbered title_icon"></i> <?php echo get_phrase('manage_marks'); ?> </h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
 
        <div class="card">
            <div class="row mt-3">
                <div class="col-md-1 mb-1"></div>
                <div class="col-md-2 mb-1">
                    <select name="exam" id="exam_id" class="form-control select2" data-toggle="select2" required onchange="examsWiseClass(this.value)">
                        <option value=""><?php echo get_phrase('select_a_exam'); ?></option>
                        <?php
                        // Récupérer les examens liés aux inscriptions de l'étudiant
                        foreach ($enrolments as $enrolment) {
                            $this->db->select('exams.*');
                            $this->db->from('exams');
                            $this->db->where('exams.school_id', $enrolment['school_id']);
                            $this->db->where('exams.class_id', $enrolment['class_id']);
                            $this->db->where('exams.section_id', $enrolment['section_id']);
                            $this->db->where('exams.session', $session_id);
                            $exams = $this->db->get()->result_array();

                            foreach ($exams as $exam) {
                                ?>
                                <option value="<?php echo $exam['id']; ?>"><?php echo $exam['name']; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2 mb-1">
                    <select name="class" id="class_id_mark" class="form-control select2" data-toggle="select2" required onchange="classWiseSection(this.value)" disabled>
                        <option value=""><?php echo get_phrase('select_a_class'); ?></option>
                        <?php
                        // Afficher les classes où l'étudiant est inscrit
                        foreach ($enrolments as $enrolment) {
                            ?>
                            <option value="<?php echo $enrolment['class_id']; ?>"><?php echo $enrolment['class_name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2 mb-1">
                    <select name="section" id="section_id" class="form-control select2" data-toggle="select2" required disabled>
                        <option value=""><?php echo get_phrase('select_section'); ?></option>
                        <?php
                        // Afficher les sections où l'étudiant est inscrit
                        foreach ($enrolments as $enrolment) {
                            ?>
                            <option value="<?php echo $enrolment['section_id']; ?>"><?php echo $enrolment['section_name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-block btn-secondary" onclick="filter_marks()"><?php echo get_phrase('filter'); ?></button>
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
    $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); });
});

function examsWiseClass(examId) {
    var classSelect = $('#class_id_mark');
    var sectionSelect = $('#section_id');

    if (examId) {
        $.ajax({
            url: "<?php echo route('exam_class/list/'); ?>" + examId,
            success: function(response){
                classSelect.html(response);
                classSelect.prop('disabled', false); // Activer le menu des classes
                sectionSelect.html('<option value=""><?php echo get_phrase('select_section'); ?></option>');
                sectionSelect.prop('disabled', true); // Garder les sections désactivées jusqu'à ce qu'une classe soit choisie
            }
        });
    } else {
        // Si aucun examen n'est sélectionné, désactiver les menus
        classSelect.html('<option value=""><?php echo get_phrase('select_a_class'); ?></option>');
        classSelect.prop('disabled', true);
        sectionSelect.html('<option value=""><?php echo get_phrase('select_section'); ?></option>');
        sectionSelect.prop('disabled', true);
    }
}

function classWiseSection(classId) { 
    var sectionSelect = $('#section_id');

    if (classId) {
        $.ajax({
            url: "<?php echo route('section/list/'); ?>" + classId,
            success: function(response){
                sectionSelect.html(response);
                sectionSelect.prop('disabled', false); // Activer le menu des sections
            }
        });
    } else {
        // Si aucune classe n'est sélectionnée, désactiver le menu des sections
        sectionSelect.html('<option value=""><?php echo get_phrase('select_section'); ?></option>');
        sectionSelect.prop('disabled', true);
    }
}

function filter_marks(){
    var exam = $('#exam_id').val();
    var class_id = $('#class_id_mark').val();
    var section_id = $('#section_id').val();
    var csrfName = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').attr('name');
    var csrfHash = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();

    if(class_id != "" && section_id != "" && exam != ""){
        $.ajax({
            type: 'POST',
            url: '<?php echo route('mark/list') ?>',
            data: {class_id: class_id, section_id: section_id, exam: exam, [csrfName]: csrfHash},
            dataType: 'json',
            success: function(response){
                $('.mark_content').html(response.status);
                // Mettre à jour le jeton CSRF
                var newCsrfName = response.csrf.csrfName;
                var newCsrfHash = response.csrf.csrfHash;
                $('input[name="' + newCsrfName + '"]').val(newCsrfHash);
            }
        });
    }else{
        toastr.error('<?php echo get_phrase('please_select_in_all_fields !'); ?>');
    }
}
</script>