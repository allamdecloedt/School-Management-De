<!--title-->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title d-inline-block">
          <i class="mdi mdi-account-multiple-check title_icon"></i> <?php echo get_phrase('assigned_permission_for_teacher'); ?>
        </h4>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="row mt-3">
                <div class="col-md-1"></div>
                <div class="col-md-4">
                    <select name="class" id="class_id_perm" class="form-control select2" data-toggle = "select2" onchange="classWiseSection(this.value)" required>
                        <option value=""><?php echo get_phrase('select_a_class'); ?></option>
                            <?php
                            $classes = $this->db->get_where('classes', array('school_id' => school_id()))->result_array();
                            $school_id = school_id();
                            foreach($classes as $class){
                                $this->db->where('class_id', $class['id']); 
                                $this->db->where('school_id', $school_id);
                                $total_student = $this->db->get('enrols');
                            ?>
                                <option value="<?php echo $class['id']; ?>">
                                    <?php echo $class['name']; ?>
                                    <?php echo "(".$total_student->num_rows().")"; ?>
                                </option>
                            <?php } ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="section" id="section_id" class="form-control select2" data-toggle = "select2"  required>
                        <option value=""><?php echo get_phrase('select_a_section'); ?></option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-block btn-secondary" onclick="filter()" ><?php echo get_phrase('filter'); ?></button>
                </div>
            </div>
            <div class="card-body permission_content">
            	<div class="empty_box text-center">
                    <img class="mb-3" width="150px" src="<?php echo base_url('assets/backend/images/empty_box.png'); ?>" />
                    <br>
                    <span class=""><?php echo get_phrase('no_data_found'); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modyfy section -->
<script>
    $('document').ready(function(){

    });

    function classWiseSection(classId) {
        $.ajax({
            url: "<?php echo route('section/list/'); ?>"+classId,
            success: function(response){
                $('#section_id').html(response);
            }
        });
    }

    function filter(){
        var class_id = $('#class_id_perm').val();
        var section_id = $('#section_id').val();
        if(class_id != "" && section_id!= ""){
            $.ajax({
                url: '<?php echo route('permission/filter/') ?>'+class_id+'/'+section_id,
                success: function(response){
                    $('.permission_content').html(response);
                }
            });
        }else{
            toastr.error('<?php echo get_phrase('please_select_a_class_and_section'); ?>');
        }
    }
</script>

<!-- permission insert and update -->
<script>
    function togglePermission(checkbox_id, column_name, teacher_id){

        var value = $('#'+checkbox_id).val();
        if(value == 1){
            value = 0;
        }else{
            value = 1;
        }
           
            // Récupérer le nom et la valeur du jeton CSRF depuis l'input caché
            var csrfName = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').attr('name');
            var csrfHash = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();
            
            var class_id = $('#class_id_perm').val();
            var section_id = $('#section_id').val();

        $.ajax({
            type: 'POST',
            url: '<?php echo route('permission/modify_permission/') ?>',
            data: {class_id : class_id, section_id : section_id, teacher_id : teacher_id, column_name : column_name,  value : value , [csrfName]: csrfHash},
            dataType: 'json',
            success: function(response){
              
            // Injecter le nouveau contenu HTML
            $('.permission_content').html(response.html);
            success_notify('<?php echo get_phrase('permission_updated_successfully.'); ?>');

            // Mettre à jour le jeton CSRF avec le nouveau jeton renvoyé dans la réponse
            var newCsrfName = response.csrfName;
            var newCsrfHash = response.csrfHash;
            $('input[name="' + newCsrfName + '"]').val(newCsrfHash); // Mise à jour du token CSRF
        
            }
        });

    }
</script>
