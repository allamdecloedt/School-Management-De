<?php
    $student = $this->db->get_where('students', array('id' => $param1))->row_array();

?>
<div class="h-100">
    <div class="row align-items-center h-100">
        <div class="col-md-4 pb-2">
            <div class="text-center">
                <img class="rounded-circle" width="50" height="50" src="<?php echo $this->user_model->get_user_image($student['user_id']); ?>">
                <br>
                <span style="font-weight: bold;">
                    <?php echo get_phrase('name'); ?>: <?php echo $this->user_model->get_user_details($student['user_id'], 'name'); ?>
                </span>
                <br>
                <span style="font-weight: bold;">
                    <?php echo get_phrase('student_code'); ?>: <?php echo $student['code']; ?>
                </span>
            </div>
        </div>
        <div class="col-md-8">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php echo get_phrase('profile'); ?></a>
                </li>
              
                <li class="nav-item">
                    <a class="nav-link" id="parent-tab" data-bs-toggle="tab" href="#exam_marks" role="tab" aria-controls="exam_marks" aria-selected="false"><?php echo get_phrase('exam_marks'); ?></a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <table class="table table-centered mb-0">
                        <tbody>
                            <tr>
                                <td style="font-weight: bold;"><?php echo get_phrase('name'); ?>:</td>
                                <td><?php echo $this->user_model->get_user_details($student['user_id'], 'name'); ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;"><?php echo get_phrase('class'); ?>:</td>
                                <td>
                                    <?php
                                        $class_id = $this->db->get_where('enrols', array('student_id' => $param1))->row('class_id');
                                        echo $this->db->get_where('classes', array('id' => $class_id))->row('name');
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;"><?php echo get_phrase('section'); ?>:</td>
                                <td>
                                    <?php
                                        $section_id = $this->db->get_where('enrols', array('student_id' => $param1))->row('section_id');
                                        echo $this->db->get_where('sections', array('id' => $section_id))->row('name');
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="tab-pane fade show table-responsive" id="exam_marks" role="tabpanel" aria-labelledby="parent-tab">
                    <table class="table table-centered mb-0">
                        <thead>
                            <tr>
                                <th><?php echo get_phrase('exam'); ?></th>
                                <th><?php echo get_phrase('subject'); ?></th>
                                <th><?php echo get_phrase('mark'); ?></th>
                                <th><?php echo get_phrase('grade_point'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $school_id = school_id();
                            $exam_id = 0;
                            $marks = $this->db->where(array('student_id' => $param1, 'school_id' => $school_id, 'session' => active_session()))->order_by('exam_id', 'desc')->get('marks')->result_array();
                            foreach($marks as $mark):
                                $exam = $this->db->get_where('exams', array('id' => $mark['exam_id']))->row_array();
                                $total_row = $this->db->get_where('marks', array('exam_id' => $mark['exam_id'], 'student_id' => $param1))->num_rows(); ?>
                                <tr>
                                    <?php if($exam_id != $mark['exam_id']): ?>
                                        <td rowspan="<?php echo $total_row; ?>" style="font-weight: bold;"><?php echo $exam['name']; ?></td>
                                    <?php endif; ?>
                                    <td><?php echo $this->db->get_where('subjects', array('id' => $mark['subject_id']))->row('name'); ?></td>
                                    <td><?php echo $mark['mark_obtained']; ?></td>
                                    <td>
                                        <?php echo get_grade($mark['mark_obtained']); ?>
                                    </td>
                                </tr>
                                <?php $exam_id =$mark['exam_id']; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
