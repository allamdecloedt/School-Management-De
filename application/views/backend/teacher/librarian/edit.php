<?php
    $users = $this->db->get_where('users', array('id' => $param1))->result_array();
    foreach($users as $user){
?>
    <form method="POST" class="d-block ajaxForm" action="<?php echo route('librarian/update/'.$param1); ?>">
        <!-- Champ caché pour le jeton CSRF -->
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    
        <div class="form-row">
            <div class="form-group mb-1">
                <label for="name"><?php echo get_phrase('name'); ?></label>
                <input type="text" value="<?php echo $user['name']; ?>" class="form-control" id="name" name = "name" required>
                <small id="" class="form-text text-muted"><?php echo get_phrase('provide_name'); ?></small>
            </div>

            <div class="form-group mb-1">
                <label for="email"><?php echo get_phrase('email'); ?></label>
                <input type="email" value="<?php echo $user['email']; ?>" class="form-control" id="email" name = "email" required>
                <small id="" class="form-text text-muted"><?php echo get_phrase('provide_email'); ?></small>
            </div>

            <div class="form-group mb-1">
                <label for="phone"><?php echo get_phrase('phone'); ?></label>
                <input type="text" value="<?php echo $user['phone']; ?>" class="form-control" id="phone" name = "phone" required>
                <small id="" class="form-text text-muted"><?php echo get_phrase('provide_phone_number'); ?></small>
            </div>

            <div class="form-group mb-1">
                <label for="gender"><?php echo get_phrase('gender'); ?></label>
                <select name="gender" id="gender" class="form-control">
                    <option value=""><?php echo get_phrase('select_a_gender'); ?></option>
                    <option value="Male" <?php if($user['gender'] == 'Male') echo 'selected'; ?>><?php echo get_phrase('male'); ?></option>
                    <option value="Female" <?php if($user['gender'] == 'Female') echo 'selected'; ?>><?php echo get_phrase('female'); ?></option>
                    <option value="Others" <?php if($user['gender'] == 'Others') echo 'selected'; ?>><?php echo get_phrase('others'); ?></option>
                </select>
                <small id="" class="form-text text-muted"><?php echo get_phrase('provide_gender'); ?></small>
            </div>

            <div class="form-group mb-1">
                <label for="address"><?php echo get_phrase('address'); ?></label>
                <textarea class="form-control" id="address" name = "address" rows="5" required><?php echo $user['address']; ?></textarea>
                <small id="" class="form-text text-muted"><?php echo get_phrase('provide_address'); ?></small>
            </div>

            <div class="form-group  col-md-12">
                <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('update_librarian'); ?></button>
            </div>
        </div>
    </form>
<?php } ?>

<script>
    $(".ajaxForm").validate({}); // Jquery form validation initialization
    $(".ajaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, showAllLibrarians);
    });
</script>
