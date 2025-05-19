<?php

// Récupérer les détails de l'examen pour Exam Date
$exam_details = $this->db->get_where('exams', array('id' => $exam_id))->row_array();
?>

<div class="row mb-3">
    <div class="col-md-4"></div>
    <div class="col-md-4 toll-free-box text-center text-white pb-2" style="background-color: #6c757d; border-radius: 10px;">
        <h4><?php echo get_phrase('manage_marks'); ?></h4>
        <span><?php echo get_phrase('Exam name'); ?> : <?php echo $this->db->get_where('exams', array('id' => $exam_id))->row('name'); ?></span><br>
        <span><?php echo get_phrase('class'); ?> : <?php echo $this->db->get_where('classes', array('id' => $class_id))->row('name'); ?></span><br>
        <span><?php echo get_phrase('section'); ?> : <?php echo $this->db->get_where('sections', array('id' => $section_id))->row('name'); ?></span><br>
        <span><?php echo get_phrase('exam_date'); ?> : <?php echo date('D, d-M-Y H:i', $exam_details['starting_date']); ?></span>
    </div>
</div>
<?php
$school_id = school_id();
$marks = $this->crud_model->get_marks($class_id, $section_id, $exam_id, $school_id)->result_array();
?>
<?php if (count($marks) > 0): ?>
    <table class="table table-bordered table-responsive-sm" width="100%">
        <thead class="thead-dark">
            <tr>
                <th><?php echo get_phrase('student_name'); ?></th>
                <th><?php echo get_phrase('mark'); ?></th>
                <th><?php echo get_phrase('grade_point'); ?></th>
                <th><?php echo get_phrase('comment'); ?></th>
                <th><?php echo get_phrase('result'); ?></th>
                <th><?php echo get_phrase('action'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($marks as $mark):
                $student = $this->db->get_where('students', array('id' => $mark['student_id']))->row_array();
                $mark_on_20 = ($mark['mark_obtained'] / 100) * 20;
                $mark_on_20 = round($mark_on_20, 2);
        ?>
                <tr>
                    <td><?php echo $this->user_model->get_user_details($student['user_id'], 'name'); ?></td>
                    <td>
                        <input class="form-control readonly" type="text" id="mark-<?php echo $mark['student_id']; ?>" name="mark" placeholder="mark" 
                               value="<?php echo $mark_on_20 . '/20'; ?>" readonly>
                    </td>
                    <td><span id="grade-for-mark-<?php echo $mark['student_id']; ?>"><?php echo get_grade($mark['mark_obtained']); ?></span></td>
                    <td>
                        <input class="form-control" type="text" id="comment-<?php echo $mark['student_id']; ?>" name="comment" placeholder="comment" 
                               value="<?php echo $mark['comment']; ?>">
                    </td>
                    <td class="text-center">
                        <a href="javascript:void(0);" 
                           onclick="largeModal('<?php echo site_url('superadmin/exam_results/' . $exam_id . '/' . $mark['student_id']); ?>', '<?php echo get_phrase('exam_results'); ?>')" 
                           class="text-primary">
                            <i class="mdi mdi-beaker" style="font-size: 24px;"></i>
                        </a>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-success" onclick="comment_update('<?php echo $mark['student_id']; ?>')">
                            <i class="mdi mdi-checkbox-marked-circle"></i>
                        </button>
                    </td>
                </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <?php include APPPATH.'views/backend/empty.php'; ?>
<?php endif; ?>

<script>
    function comment_update(student_id) {
        var class_id = '<?php echo $class_id; ?>';
        var section_id = '<?php echo $section_id; ?>';       
        var exam_id = '<?php echo $exam_id; ?>';
        var comment = $('#comment-' + student_id).val();
        var mark_on_20 = $('#mark-' + student_id).val().split('/')[0]; // Extract mark value before /20
        var mark = (mark_on_20 / 20) * 100; // Convert to 100-scale for backend
        var csrfName = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').attr('name');
        var csrfHash = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();
        
        $.ajax({
            type: 'POST',
            url: '<?php echo route('mark/mark_update'); ?>',
            data: {
                student_id: student_id,
                class_id: class_id,
                section_id: section_id,
                exam_id: exam_id,
                mark: mark,
                comment: comment,
                [csrfName]: csrfHash
            },
            dataType: 'json',
            success: function(response) {
                success_notify('<?php echo get_phrase('comment_has_been_updated_successfully'); ?>');
                var newCsrfName = response.csrf.csrfName;
                var newCsrfHash = response.csrf.csrfHash;
                $('input[name="' + newCsrfName + '"]').val(newCsrfHash);
            }
        });
    }
</script>

<style>
    .form-control.readonly {
        background-color: #e9ecef;
        cursor: not-allowed;
    }
</style>