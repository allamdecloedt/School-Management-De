<form method="POST" class="d-block ajaxForm" action="<?php echo route('language/create'); ?>">
  <!-- Champ caché pour le jeton CSRF -->
  <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
  
  <div class="form-group mb-1">
    <label for="language"><?php echo get_phrase('language'); ?></label>
    <input type="text" class="form-control" id="language" name = "language" required>
  </div>

  <div class="form-group  col-md-12">
    <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('add_language'); ?></button>
  </div>
</form>

<script>
$(".ajaxForm").validate({}); // Jquery form validation initialization
$(".ajaxForm").submit(function(e) {
  var form = $(this);
  ajaxSubmit(e, form, showAllLanguages);
});
</script>
