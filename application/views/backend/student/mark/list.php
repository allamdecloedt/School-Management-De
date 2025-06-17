<?php
$student_data = $this->user_model->get_logged_in_student_details();
$user_id = $this->session->userdata('user_id');
$classe = $this->db->get_where('classes', array('id' => $class_id))->row_array();
$school_id = $classe['school_id'];
$student_data = $this->db->get_where('students', array('user_id' => $user_id, 'school_id' => $classe['school_id']))->row_array();
$user_details = $this->db->get_where('users', array('id' => $user_id))->row_array();

// Récupérer le nombre total de questions pour l'examen
$total_questions = $this->crud_model->get_total_questions($exam_id);

// Récupérer les détails de l'examen
$exam_details = $this->db->get_where('exams', array('id' => $exam_id))->row_array();
?>

<div class="row mb-3">
    <div class="col-md-4"></div>
    <div class="col-md-4 toll-free-box text-center text-white pb-2" style="background-color: #6c757d; border-radius: 10px;">
        <h4><?php echo get_phrase('manage_marks'); ?></h4>
        <span><?php echo get_phrase('Exam name'); ?> : <?php echo $this->db->get_where('exams', array('id' => $exam_id))->row('name'); ?></span><br>
        <span><?php echo get_phrase('class'); ?> : <?php echo $this->db->get_where('classes', array('id' => $class_id))->row('name'); ?></span><br>
        <span><?php echo get_phrase('section'); ?> : <?php echo $this->db->get_where('sections', array('id' => $section_id))->row('name'); ?></span><br>
        <!-- Ajout de la date et heure de l'examen -->
        <span><?php echo get_phrase('exam_date'); ?> : <?php echo date('D, d-M-Y H:i', $exam_details['starting_date']); ?></span>
    </div>
</div>

<?php
$marks = $this->crud_model->get_marks($class_id, $section_id, $exam_id, $school_id)->result_array();
?>

<?php if (count($marks) > 0): ?>
    <table class="table table-bordered table-responsive-sm" width="100%">
        <thead class="thead-dark">
            <tr>
                <th><?php echo get_phrase('student_name'); ?></th>
                <th><?php echo get_phrase('mark'); ?></th>
                <th><?php echo get_phrase('comment'); ?></th>
                <th><?php echo get_phrase('result'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($marks as $mark): ?>
                <?php if ($mark['student_id'] == $student_data['id']): ?>
                    <?php
                    $mark_on_20 = ($mark['mark_obtained'] / 100) * 20;
                    $mark_on_20 = round($mark_on_20, 2);
                    ?>
                    <tr>
                        <td><?php echo $user_details['name']; ?></td>
                        <td>
                            <input class="form-control readonly" type="text" id="mark-<?php echo $mark['student_id']; ?>" name="mark" placeholder="mark" 
                                   value="<?php echo $mark_on_20 . '/20'; ?>" readonly>
                        </td>
                        <td>
                            <input class="form-control readonly" type="text" id="comment-<?php echo $mark['student_id']; ?>" name="comment" placeholder="comment" 
                                   value="<?php echo $mark['comment']; ?>" readonly>
                        </td>
                        <td class="text-center">
                            <a href="javascript:void(0);" 
                                onclick="largeModal('<?php echo site_url('student/exam_results/' . $exam_id . '/' . $mark['student_id']); ?>', '<?php echo get_phrase('exam_results'); ?>')" 
                                class="text-primary">
                                <i class="mdi mdi-beaker" style="font-size: 24px;"></i>
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <?php include APPPATH . 'views/backend/empty.php'; ?>
<?php endif; ?>