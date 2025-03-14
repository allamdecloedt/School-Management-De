<!--title-->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title d-inline-block">
          <i class="mdi mdi-book-open-page-variant title_icon"></i> <?php echo get_phrase('subject'); ?>
        </h4>
        <button type="button" class="btn btn-outline-primary btn-rounded alignToTitle float-end mt-1" onclick="rightModal('<?php echo site_url('modal/popup/subject/create'); ?>', '<?php echo get_phrase('create_subject'); ?>')"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('add_subject'); ?></button>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="row mt-3">
                <div class="col-md-3"></div>
                <div class="col-md-4">
                    <select name="class_id" id="class_id_subject" class="form-control select2" data-toggle = "select2" required>
                        <option value=""><?php echo get_phrase('select_a_class'); ?></option>
                        <?php
                        $classes = $this->db->get_where('classes', array('school_id' => $school_id))->result_array();
                        $school_id = school_id(); ?>
                        <?php foreach ($classes as $class): 
                            $this->db->where('class_id', $class['id']);
                            $this->db->where('school_id', $school_id);
                            $total_student = $this->db->get('enrols');
                            ?>
                            <option value="<?php echo $class['id']; ?>">
                                <?php echo $class['name']; ?>
                                <?php echo "(".$total_student->num_rows().")"; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-block btn-secondary" onclick="filter_class()" ><?php echo get_phrase('filter'); ?></button>
                </div>
            </div>
            <div class="card-body subject_content">
                <?php include 'list.php'; ?>
            </div>
        </div>
    </div>
</div>


<script>
function filter_class(){
    var class_id = $('#class_id_subject').val();
    if(class_id != ""){
        showAllSubjects();
    }else{
        toastr.error('<?php echo get_phrase('please_select_a_class'); ?>');
    }
}

var showAllSubjects = function () {
    var class_id = $('#class_id_subject').val();
    if(class_id != ""){
        $.ajax({
            url: '<?php echo route('subject/list/') ?>'+class_id,
            success: function(response){
                $('.subject_content').html(response);
            }
        });
    }
}
</script>
