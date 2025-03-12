<form method="POST" class="d-block ajaxForm" action="<?php echo route('Liveclasse/create'); ?>">
    <!-- Champ caché pour le jeton CSRF -->
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

    <?php
        $school_id = school_id();
        

        $classes = $this->db->get_where('classes', array('school_id' => $school_id))->result_array();
       
        ?>
    
    <div class="form-row">
        <div class="form-group mb-1">
            <label for="name"><?php echo get_phrase('name'); ?><span class="required"> * </span></label>
            <input type="text" class="form-control" id="roomName" name = "roomName" required>
            <small id="" class="form-text text-muted"><?php echo get_phrase('roomName'); ?></small>
        </div>


        <div class="form-group mb-1">
            <label for="name"><?php echo get_phrase('Description'); ?><span class="required"> * </span></label>
            <textarea class="form-control" id="description" name ="description" ></textarea>
            <small id="" class="form-text text-muted"><?php echo get_phrase('Description'); ?></small>
        </div>

        <div class="form-group mb-1">
          
            <label for="classSelect" class="form-label">Sélectionner une classe</label>
            <select class="form-select" id="classSelect" name="classSelect" required>
                <option value="">Choisissez une classe</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class['id']; ?>"><?php echo $class['name']; ?></option>
                    <?php endforeach; ?>
            </select>
            <small id="" class="form-text text-muted"><?php echo get_phrase('select_classe'); ?></small>
        </div>



        <div class="form-group mt-2 col-md-12">
            <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('create_room'); ?></button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); });
    });
    

    $(".ajaxForm").validate({}); // Jquery form validation initialization
    $(".ajaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, showAllRooms);
       
    });
</script>