<?php
$school_id = school_id();
$session = active_session();

// Requête avec jointures pour récupérer les noms des classes et sections
$this->db->select('exams.*, classes.name as class_name, sections.name as section_name');
$this->db->from('exams');
$this->db->join('classes', 'exams.class_id = classes.id', 'left');
$this->db->join('sections', 'exams.section_id = sections.id', 'left');
$this->db->where('exams.school_id', $school_id);
$this->db->where('exams.session', $session);
$exams = $this->db->get()->result_array();

if (count($exams) > 0): ?>
    <!-- Hidden fields for JavaScript -->
    <!-- Hidden fields for JavaScript -->
<input type="hidden" id="base_url" value="<?php echo site_url(); ?>">
<input type="hidden" id="csrf_name" value="<?php echo $this->security->get_csrf_token_name(); ?>">
<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>">
<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
<table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
    <thead>
        <tr style="background-color: #313a46; color: #ababab;">
            <th><?php echo get_phrase('exam_name'); ?></th>
            <th><?php echo get_phrase('date'); ?></th>
            <th><?php echo get_phrase('class'); ?></th>
            <th><?php echo get_phrase('section'); ?></th>
            <th><?php echo get_phrase('options'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($exams as $exam): ?>
        <tr>
            <td><?php echo $exam['name']; ?></td>
            <td><?php echo date('D, d-M-Y H:i', $exam['starting_date']); ?></td>
            <td><?php echo !empty($exam['class_name']) ? $exam['class_name'] : get_phrase('no_class'); ?></td>
            <td><?php echo !empty($exam['section_name']) ? $exam['section_name'] : get_phrase('no_section'); ?></td>
            <td>
                <div class="dropdown text-center">
                    <button type="button" class="btn btn-sm btn-icon btn-rounded btn-outline-secondary dropdown-btn dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="javascript::" class="dropdown-item" onclick="largeModal('<?php echo site_url('modal/popup/academy/exam_questions/'.$exam['id']); ?>', '<?php echo get_phrase('manage_exam_questions'); ?>')"><?php echo get_phrase('manage_exam_questions'); ?></a>
                        <!-- item-->
						            <a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('modal/popup/exam/edit/'.$exam['id'])?>',  &quot;<?php echo get_phrase('update_exam'); ?>&quot;)"><?php echo get_phrase('edit'); ?></a>
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo route('exam/delete/'.$exam['id']); ?>', showAllExams)"><?php echo get_phrase('delete'); ?></a>
                    </div>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <?php include APPPATH.'views/backend/empty.php'; ?>
<?php endif; ?>
<script src="<?php echo base_url('assets/backend/js/common_scripts.js'); ?>"></script>