<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/smtp-settings.css">
<div class="row justify-content-md-center">
    <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" class="col-12 smtpForm" action="<?php echo route('smtp_settings/update') ; ?>" id = "smtpsettings">
                    <!-- Champ caché pour le jeton CSRF -->
                     <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                    <div class="col-12">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="mail_sender"><?php echo get_phrase('mail_sender') ; ?><span class="required"> * </span></label>
                            <div class="col-md-9">
                                <select class="form-control select2" data-toggle = "select2" name="mail_sender" id="mail_sender" onchange = "showHideSMTPCredentials(this.value)" required>
                                    <option value="php_mailer" <?php if (get_smtp('mail_sender') == 'php_mailer'): ?> selected <?php endif; ?>><?php echo get_phrase('php_mailer') ;?></option>
                                    <option value="generic_smtp" <?php if (get_smtp('mail_sender') == 'generic_smtp'): ?> selected <?php endif; ?>><?php echo get_phrase('generic_smtp') ;?></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="smtp_protocol">SMTP <?php echo get_phrase('protocol') ; ?><span class="required"> * </span></label>
                            <div class="col-md-9">
                                <input type="text" id="smtp_protocol" name="smtp_protocol" class="form-control"  value="<?php echo get_smtp('smtp_protocol') ; ?>" required>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="smtp_host">SMTP <?php echo get_phrase('host') ; ?><span class="required"> * </span></label>
                            <div class="col-md-9">
                                <input type="text" id="smtp_host" name="smtp_host" class="form-control"  value="<?php echo get_smtp('smtp_host') ; ?>" required>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="smtp_crypto">SMTP <?php echo get_phrase('crypto') ; ?><span class="required"> * </span></label>
                            <div class="col-md-9">
                                <input type="text" id="smtp_crypto" name="smtp_crypto" class="form-control"  value="<?php echo get_smtp('smtp_crypto') ; ?>" required>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="smtp_port">SMTP <?php echo get_phrase('port') ; ?><span class="required"> * </span></label>
                            <div class="col-md-9">
                                <input type="text" id="smtp_port" name="smtp_port" class="form-control"  value="<?php echo get_smtp('smtp_port') ; ?>" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="smtp_username">SMTP <?php echo get_phrase('username') ; ?><span class="required"> * </span></label>
                            <div class="col-md-9">
                                <input type="text" id="smtp_username" name="smtp_username" class="form-control"  value="<?php echo get_smtp('smtp_username') ; ?>" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="smtp_password">SMTP <?php echo get_phrase('password') ; ?><span class="required"> * </span></label>
                            <div class="col-md-9">
                                <input type="text" id="smtp_password" name="smtp_password" class="form-control"  value="<?php echo get_smtp('smtp_password') ; ?>" required>
                            </div>
                        </div>
                        <div id="php-mailer-visibility-div">
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="smtp_secure">SMTP <?php echo get_phrase('secure') ; ?></label>
                                <div class="col-md-9">
                                    <input type="text" id="smtp_secure" name="smtp_secure" class="form-control"  value="<?php echo get_smtp('smtp_secure') ; ?>">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="smtp_set_from">SMTP <?php echo get_phrase('set_from') ; ?></label>
                                <div class="col-md-9">
                                    <input type="text" id="smtp_set_from" name="smtp_set_from" class="form-control"  value="<?php echo get_smtp('smtp_set_from') ; ?>">
                                </div>
                            </div>
                            <div class="form-group row mb-3 hidden">
                                <label class="col-md-3 col-form-label" for="smtp_show_error">SMTP <?php echo get_phrase('show_error') ; ?></label>
                                <div class="col-md-9">
                                    <select class="form-control select2" data-toggle = "select2" name="smtp_show_error" id="smtp_show_error">
                                        <option value="yes" <?php if (get_smtp('smtp_show_error') == 'yes'): ?> selected <?php endif; ?>><?php echo get_phrase('show') ;?></option>
                                        <option value="no" <?php if (get_smtp('smtp_show_error') == 'no'): ?> selected <?php endif; ?>><?php echo get_phrase('do_not_show') ;?></option>
                                    </select>
                                    <small class = "text-muted"><?php echo get_phrase("error_will_be_shown_if_sending_mail_fails"); ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="text-md-center">
                            <button type="submit" class="btn btn-primary btn-l px-4" id="update-btn" onclick="updateSmtpInfo()"><i class="mdi mdi-account-check"></i><?php echo get_phrase('update_SMTP_settings') ; ?></button>
                        </div>
                    </div>
                </form>

            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div>
</div>


<script type="text/javascript">

    $(document).ready(function () {
        var mail_sender = $('#mail_sender').val();
        showHideSMTPCredentials(mail_sender);
  
    function showHideSMTPCredentials(mail_sender) {
        if (mail_sender === "php_mailer") {
            $("#php-mailer-visibility-div").slideDown();
        }else{
            $("#php-mailer-visibility-div").slideUp();
        }
    }
  // Fonction pour récupérer et retourner le token CSRF
function getCsrfToken() {
         // Récupérer le nom du token CSRF depuis le champ input caché
          var csrfName = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').attr('name');
         // Récupérer la valeur (hash) du token CSRF depuis le champ input caché
           var csrfHash = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();
         // Retourner un objet contenant le nom du token et sa valeur
         return { csrfName: csrfName, csrfHash: csrfHash };
      }


 // Soumission du formulaire de logo
 $(".smtpForm").submit(function(e) {
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

$(document).ready(function() {
    var mail_sender = $('#mail_sender').val();
    showHideSMTPCredentials(mail_sender);
    $('#smtpsettings').on('submit', function(e) {
        e.preventDefault();
       
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();
       
        // Afficher le spinner
        submitBtn.html('<i class="mdi mdi-loading mdi-spin"></i> ' + originalText).prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.status) {
                    if(response.type == 'success') {
                        success_notify(response.notification);
                    } else {
                        error_notify(response.notification);
                    }
                }
            },
            error: function(xhr) {
                error_notify("<?php echo get_phrase('an_error_occurred'); ?>");
                console.error(xhr.responseText);
            },
            complete: function() {
                // Réinitialiser le bouton après 2 secondes
                setTimeout(function() {
                    submitBtn.html(originalText).prop('disabled', false);
                }, 3300);
            }
        });
    });
});

function showHideSMTPCredentials(mail_sender) {
    if (mail_sender === "php_mailer") {
        $("#php-mailer-visibility-div").slideDown();
    } else {
        $("#php-mailer-visibility-div").slideUp();
    }
}

</script>