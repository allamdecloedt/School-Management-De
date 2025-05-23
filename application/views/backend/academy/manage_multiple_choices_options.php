<link rel="stylesheet" href="<?php echo base_url(); ?>assets/backend/css/manageMultipleQuiz.css">

<?php for($i = 1; $i <= $number_of_options; $i++): ?>
    <div class="form-group options">
        <label><?php echo get_phrase('option').' '.$i;?></label>
        <div class="input-group mb-2">
            <input type="text" class="form-control" name = "options[]" id="option_<?php echo $i; ?>" placeholder="<?php echo get_phrase('option_').$i; ?>" required>
            <div class="input-group-append">
                <span class="input-group-text d-block">
                    <input type='checkbox' name = "correct_answers[]" value = <?php echo $i; ?>>
                </span>
            </div>
        </div>
    </div>
<?php endfor; ?>
