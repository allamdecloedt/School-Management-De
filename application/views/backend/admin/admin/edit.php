<?php
$users = $this->db->get_where('users', array('id' => $param1))->result_array();
foreach($users as $user): ?>
<form method="POST" class="d-block ajaxForm" action="<?php echo route('admin/update/'.$param1); ?>">
  <!-- Champ caché pour le jeton CSRF -->
  <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
  <div class="form-row">
    <div class="form-group mb-1">
      <label for="name"><?php echo get_phrase('name'); ?><span class="required"> * </span></label>
      <input type="text" value="<?php echo $user['name']; ?>" class="form-control" id="name" name = "name" required>
      <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_name'); ?></small>
    </div>

    <div class="form-group mb-1">
      <label for="email"><?php echo get_phrase('email'); ?><span class="required"> * </span></label>
      <input type="email" value="<?php echo $user['email']; ?>" class="form-control" id="email" name = "email" required>
      <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_email'); ?></small>
    </div>


    <div class="form-group mb-1">
      <label for="phone"><?php echo get_phrase('phone_number'); ?><span class="required"> * </span></label>
      <input type="text" value="<?php echo $user['phone']; ?>" class="form-control" id="phone" name = "phone" required>
      <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_phone_number'); ?></small>
    </div>

    <div class="form-group mb-1">
      <label for="gender"><?php echo get_phrase('admin_of'); ?></label>
      <select name="school_id" id="school_id" class="form-control select2" data-toggle = "select2">
        <option value=""><?php echo get_phrase('select_a_school'); ?></option>
        <?php $schools = $this->crud_model->get_schools()->result_array(); ?>
        <?php foreach ($schools as $school): ?>
          <option value="<?php echo $school['id']; ?>" <?php if ($school['id'] == $user['school_id']): ?> selected <?php endif; ?>><?php echo $school['name']; ?></option>
        <?php endforeach; ?>
      </select>
      <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_gender'); ?></small>
    </div>

    <div class="form-group mb-1">
      <label for="gender"><?php echo get_phrase('gender'); ?></label>
      <select name="gender" id="gender" class="form-control select2" data-toggle = "select2">
        <option value=""><?php echo get_phrase('select_a_gender'); ?></option>
        <option value="Male" <?php if($user['gender'] == 'Male') echo 'selected'; ?>><?php echo get_phrase('male'); ?></option>
        <option value="Female" <?php if($user['gender'] == 'Female') echo 'selected'; ?>><?php echo get_phrase('female'); ?></option>
        <option value="Others" <?php if($user['gender'] == 'Others') echo 'selected'; ?>><?php echo get_phrase('others'); ?></option>
      </select>
      <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_gender'); ?></small>
    </div>

    <div class="form-group mb-1">
      <label for="phone"><?php echo get_phrase('address'); ?><span class="required"> * </span></label>
      <textarea class="form-control" id="address" name = "address" rows="5" required><?php echo $user['address']; ?></textarea>
      <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_address'); ?></small>
    </div>

    <div class="form-group mt-2 col-md-12">
      <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('update_admin'); ?></button>
    </div>
  </div>
</form>
<?php endforeach; ?>

<script>

  $(document).ready(function () {
    $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); });
  });
  $(".ajaxForm").validate({}); // Jquery form validation initialization
  $(".ajaxForm").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, showAllAdmins);
  });
</script>
