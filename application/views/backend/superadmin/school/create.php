<form method="POST" class="d-block ajaxForm" action="<?php echo route('school_crud/create'); ?>">
    <!-- Champ caché pour le jeton CSRF -->
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    
    <div class="form-row">
        <div class="form-group mb-1">
            <label for="name"><?php echo get_phrase('name'); ?><span class="required"> * </span></label>
            <input type="text" class="form-control" id="name" name = "name" required>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_name'); ?></small>
        </div>

        

        <div class="form-group mb-1">
            <label for="description"><?php echo get_phrase('description'); ?><span class="required"> * </span></label>
            <textarea class="form-control" id="description" name = "description" rows="5" required></textarea>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_description'); ?></small>
        </div>

       
        <div class="form-group mb-1">
            <label for="phone"><?php echo get_phrase('phone_number'); ?><span class="required"> * </span></label>
            <input type="text" class="form-control" id="phone" name = "phone" required>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_phone_number'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="access"><?php echo get_phrase('Access'); ?><span class="required"> * </span></label>
            <select name="access" id="access" class="form-control select2" data-toggle = "select2">
                <option value=""><?php echo get_phrase('select_a_access'); ?></option>
                <option value="1"><?php echo get_phrase('public'); ?></option>
                <option value="0"><?php echo get_phrase('privé'); ?></option>
              
            </select>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_access'); ?></small>
        </div>


        <div class="form-group mb-1">
            <label for="access"><?php echo get_phrase('Category'); ?><span class="required"> * </span></label>
            <select name="category" id="category" class="form-control select2" data-toggle = "select2">
                <option value=""><?php echo get_phrase('select_a_category'); ?></option>
                <?php $categories = $this->db->get_where('categories', array())->result_array(); ?>
                <?php foreach ($categories as $categorie): ?>
                    <option value="<?php echo $categorie['name']; ?>"><?php echo $categorie['name']; ?></option>
                <?php endforeach; ?>    
            </select>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_category'); ?></small>
        </div>

        

        <div class="form-group mb-1">
            <label for="phone"><?php echo get_phrase('address'); ?><span class="required"> * </span></label>
            <textarea class="form-control" id="address" name = "address" rows="5" required></textarea>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_address'); ?></small>
        </div>

        <div class="form-group mt-2 col-md-12">
            <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('create_school'); ?></button>
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
        ajaxSubmit(e, form, showAllSchools);
    });
</script>