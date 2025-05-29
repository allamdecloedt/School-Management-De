<?php $rooms = $this->db->get_where('rooms', array('id' => $param1))->result_array(); ?>
<?php foreach($rooms as $room){ ?>

<form method="POST" class="d-block ajaxForm" action="<?php echo route('Liveclasse/update/'.$param1); ?>" >
    <!-- Champ caché pour le jeton CSRF -->
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

    <?php
        $school_id = school_id();
        

        $classes = $this->db->get_where('classes', array('school_id' => $school_id))->result_array();
       
        ?>
    
    <div class="form-row">
        <div class="form-group mb-1">
            <label for="name"><?php echo get_phrase('name'); ?><span class="required"> * </span></label>
            <input type="text" class="form-control" id="roomName"  value="<?php echo $room['name']; ?>"  name = "roomName" required>
            <small id="" class="form-text text-muted"><?php echo get_phrase('roomName'); ?></small>
        </div>


        <div class="form-group mb-1">
            <label for="name"><?php echo get_phrase('Description'); ?><span class="required"> * </span></label>
            <textarea class="form-control" id="description"   name ="description" ><?php echo $room['description']; ?></textarea>
            <small id="" class="form-text text-muted"><?php echo get_phrase('Description'); ?></small>
        </div>

        <div class="form-group mb-1">
          
            <label for="classSelect" class="form-label">Sélectionner une classe</label>
            <select class="form-select" id="classSelect" name="classSelect" disabled required>
                <option value="">Choisissez une classe</option>
                    <?php foreach ($classes as $class): ?>
                        <option <?php if ($room['class_id'] == $class['id']): ?> selected <?php endif; ?> value="<?php echo $class['id']; ?>"><?php echo $class['name']; ?></option>
                    <?php endforeach; ?>
            </select>
            <small id="" class="form-text text-muted"><?php echo get_phrase('select_classe'); ?></small>
        </div>



        <div class="form-group mt-2 col-md-12">
            <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('Update_room'); ?></button>
        </div>
    </div>
</form>
<?php } ?>

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