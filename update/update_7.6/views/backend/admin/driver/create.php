<form method="POST" class="d-block ajaxForm" action="<?php echo route('driver/create'); ?>" enctype="multipart/form-data">
    <div class="form-row">
        <div class="form-group mb-1">
            <input type="hidden" name="school_id" value="<?php echo school_id(); ?>">
            <label for="name"><?php echo get_phrase('name'); ?></label>
            <input type="text" class="form-control" id="name" name="name" required>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_driver_name'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="email"><?php echo get_phrase('email'); ?></label>
            <input type="email" class="form-control" id="email" name="email" required>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_driver_email'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="password"><?php echo get_phrase('password'); ?></label>
            <input type="password" class="form-control" id="password" name="password" required>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_driver_password'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="phone"><?php echo get_phrase('phone_number'); ?></label>
            <input type="text" class="form-control" id="phone" name="phone" required>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_driver_phone_number'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="gender"><?php echo get_phrase('gender'); ?></label>
            <select name="gender" id="gender" class="form-control select2" data-toggle="select2">
                <option value=""><?php echo get_phrase('select_a_gender'); ?></option>
                <option value="Male"><?php echo get_phrase('male'); ?></option>
                <option value="Female"><?php echo get_phrase('female'); ?></option>
                <option value="Others"><?php echo get_phrase('others'); ?></option>
            </select>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_driver_gender'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="blood_group"><?php echo get_phrase('blood_group'); ?></label>
            <select name="blood_group" id="blood_group" class="form-control select2" data-toggle="select2">
                <option value=""><?php echo get_phrase('select_a_blood_group'); ?></option>
                <option value="a+">A+</option>
                <option value="a-">A-</option>
                <option value="b+">B+</option>
                <option value="b-">B-</option>
                <option value="ab+">AB+</option>
                <option value="ab-">AB-</option>
                <option value="o+">O+</option>
                <option value="o-">O-</option>
            </select>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_driver_blood_group'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label><?php echo get_phrase('facebook_profile_link'); ?></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="mdi mdi-facebook"></i></span>
                </div>
                <input type="text" class="form-control" name="facebook_link">
            </div>
            <small id="" class="form-text text-muted"><?php echo get_phrase('facebook_profile_link'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label><?php echo get_phrase('twitter_profile_link'); ?></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="mdi mdi-twitter"></i></span>
                </div>
                <input type="text" class="form-control" name="twitter_link">
            </div>
            <small id="" class="form-text text-muted"><?php echo get_phrase('twitter_profile_link'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label><?php echo get_phrase('linkedin_profile_link'); ?></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="mdi mdi-linkedin"></i></span>
                </div>
                <input type="text" class="form-control" name="linkedin_link">
            </div>
            <small id="" class="form-text text-muted"><?php echo get_phrase('linkedin_profile_link'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="phone"><?php echo get_phrase('address'); ?></label>
            <textarea class="form-control" id="address" name="address" rows="5" required></textarea>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_driver_address'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="about"><?php echo get_phrase('about'); ?></label>
            <textarea class="form-control" id="about" name="about" rows="5" required></textarea>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_a_small_about'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="image_file"><?php echo get_phrase('upload_image'); ?></label>
            <input type="file" class="form-control" id="image_file" name="image_file">
        </div>

        <div class="form-group mt-2 col-md-12">
            <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('create_driver'); ?></button>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $('select.select2:not(.normal)').each(function() {
        $(this).select2({
            dropdownParent: '#right-modal'
        });
    }); //initSelect2(['#department', '#gender', '#blood_group', '#show_on_website']);
});


$(".ajaxForm").validate({}); // Jquery form validation initialization
$(".ajaxForm").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, showAllDrivers);
});

// initCustomFileUploader();
</script>
