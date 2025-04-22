<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/edit-design-button.css">

<?php $invoice_details = $this->crud_model->get_invoice_by_id($param1); ?>
<form method="POST" class="d-block ajaxForm" action="<?php echo route('invoice/update/'.$param1); ?>">
    <!-- Champ caché pour le jeton CSRF -->
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    
    <div class="form-row">
        <div class="form-group mb-1">
            <label for="class_id_on_create"><?php echo get_phrase('class'); ?><span class="required"> * </span></label>
            <select name="class_id" id="class_id_on_create" class="form-control select2" data-bs-toggle="select2"  required onchange="classWiseStudentOnCreate(this.value)">
                <option value=""><?php echo get_phrase('select_a_class'); ?></option>
                <?php $classes = $this->crud_model->get_classes()->result_array(); ?>
                <?php foreach($classes as $class): ?>
                    <option value="<?php echo $class['id']; ?>" <?php if ($class['id'] == $invoice_details['class_id']): ?> selected <?php endif; ?>><?php echo $class['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>


        <div class="form-group mb-1">
            <label for="student_id_on_create"><?php echo get_phrase('select_student'); ?><span class="required"> * </span></label>
            <div id = "student_content">
                <select name="student_id" id="student_id_on_create" class="form-control select2" data-bs-toggle="select2" required >
                    <option value=""><?php echo get_phrase('select_a_student'); ?></option>
                    <?php $enrolments = $this->user_model->get_student_details_by_id('class', $invoice_details['class_id']);
                    foreach ($enrolments as $enrolment): ?>
                        <option value="<?php echo $enrolment['student_id']; ?>" <?php if ($invoice_details['student_id'] == $enrolment['student_id']): ?>selected<?php endif; ?>><?php echo $enrolment['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group mb-1">
            <label for="title"><?php echo get_phrase('invoice_title'); ?><span class="required"> * </span></label>
            <input type="text" class="form-control" id="title" name = "title" value="<?php echo $invoice_details['title']; ?>" required>
        </div>

        <div class="form-group mb-1">
            <label for="total_amount"><?php echo get_phrase('total_amount').' ('.currency_code_and_symbol('code').')'; ?><span class="required"> * </span></label>
            <input type="text" class="form-control" id="total_amount" name = "total_amount" value="<?php echo $invoice_details['total_amount']; ?>" required>
        </div>

        <div class="form-group mb-1">
            <label for="paid_amount"><?php echo get_phrase('paid_amount').' ('.currency_code_and_symbol('code').')'; ?><span class="required"> * </span></label>
            <input type="text" class="form-control" id="paid_amount" name = "paid_amount" value="<?php echo $invoice_details['paid_amount']; ?>" required>
        </div>

        <div class="form-group mb-1">
            <label for="status"><?php echo get_phrase('status'); ?><span class="required"> * </span></label>
            <select name="status" id="status" class="form-control select2" data-bs-toggle="select2" required >
                <option value=""><?php echo get_phrase('select_a_status'); ?></option>
                <option value="paid" <?php if ($invoice_details['status'] == 'paid'): ?> selected <?php endif; ?>><?php echo get_phrase('paid'); ?></option>
                <option value="unpaid" <?php if ($invoice_details['status'] == 'unpaid'): ?> selected <?php endif; ?>><?php echo get_phrase('unpaid'); ?></option>
            </select>
        </div>
    </div>
    <div class="form-group  col-md-12">
        <button class="btn btn-primary btn-l px-4" id="update-btn" type="submit"><i class="mdi mdi-account-check"></i><?php echo get_phrase('update_invoice'); ?></button>
    </div>
</form>

<script>
$(".ajaxForm").validate({}); // Jquery form validation initialization
$(".ajaxForm").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, showAllInvoices);
    function getCsrfToken() {
         // Récupérer le nom du token CSRF depuis le champ input caché
          var csrfName = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').attr('name');
         // Récupérer la valeur (hash) du token CSRF depuis le champ input caché
           var csrfHash = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();
         // Retourner un objet contenant le nom du token et sa valeur
         return { csrfName: csrfName, csrfHash: csrfHash };
      }


 // Soumission du formulaire de logo
 $(".ajaxForm").submit(function(e) {
    e.preventDefault();

           // Cible uniquement le bouton de ce formulaire
        var submitButton = $(this).find('button[type="submit"]');
        var updating_text = "<?php echo get_phrase('updating'); ?>...";
        
        // Désactive et met à jour uniquement ce bouton
        submitButton.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i>'+updating_text);
         // Récupérer le token CSRF avant l'envoi
         var csrf = getCsrfToken(); // Appel de la fonction pour obtenir le token
         const formData = new FormData(this);// Crée une nouvelle instance de FormData en passant l'élément du formulaire courant

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            if (response.status) { // Vérifie si la mise à jour a réussi
                // Met à jour le token CSRF
                $('input[name="' + response.csrf.name + '"]').val(response.csrf.hash);

                // Rafraîchissement de la page après un léger délai pour s'assurer que les modifications sont appliquées
                setTimeout(function() {
                  location.reload();
                }, 3500);// Attendre 3500ms avant de recharger la page
            } else {
              error_notify('<?= js_phrase(get_phrase('action_not_allowed')); ?>')
                
            }
        },
        error: function () {
          error_notify(<?= js_phrase(get_phrase('an_error_occurred_during_submission')); ?>)
        }
      });
    });
  });

$(document).ready(function () {
    $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); }); //initSelect2(['#class_id_on_create', '#student_id_on_create', '#status']);
});

function classWiseStudentOnCreate(classId) {
    $.ajax({
        url: "<?php echo route('invoice/student/'); ?>"+classId,
        success: function(response){
            $('#student_id_on_create').html(response);
        }
    });
}
</script>
