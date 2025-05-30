<script type="text/javascript">
var callBackFunction;
var callBackFunctionForGenericConfirmationModal;
function largeModal(url, header)
{
  jQuery('#large-modal').modal('show', {backdrop: 'true'});
  // SHOW AJAX RESPONSE ON REQUEST SUCCESS
  $.ajax({
    url: url,
    success: function(response)
    {
      jQuery('#large-modal .modal-body').html(response);
      jQuery('#large-modal .modal-title').html(header);
    }
  });
}
function confirmModal_alert(message)
{
  jQuery('#alert-modal-confimation').modal('show', {backdrop: 'true'});
  jQuery('#alert-modal-confimation .modal-message').html(message);

}

function previewModal(url, header)
{
  jQuery('#preview-modal').modal('show', {backdrop: 'true'});
  // SHOW AJAX RESPONSE ON REQUEST SUCCESS
  $.ajax({
    url: url,
    success: function(response)
    {
      jQuery('#preview-modal .modal-body').html(response);
      jQuery('#preview-modal .modal-title').html(header);
    }
  });
}

function rightModal(url, header)
{
  // LOADING THE AJAX MODAL
  jQuery('#right-modal').modal('show', {backdrop: 'true'});

  // SHOW AJAX RESPONSE ON REQUEST SUCCESS
  $.ajax({
    url: url,
    success: function(response)
    {
      jQuery('#right-modal .modal-body').html(response);
      jQuery('#right-modal .modal-title').html(header);
    }
  });
}


function confirmModal(delete_url, callback) {
    jQuery('#alert-modal').modal('show', {backdrop: 'static'});

    // S'assurer que l'action du formulaire est correcte
    jQuery('#delete_form').attr('action', delete_url);

    // Gérer la soumission du formulaire
    jQuery('#delete_form').off('submit').on('submit', function(e) {
        e.preventDefault(); // Empêcher la soumission par défaut
        var form = jQuery(this);
        var url = form.attr('action'); // Utiliser l'URL définie dans l'action

        jQuery.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(), // Pas besoin d'ajouter le jeton CSRF
            dataType: 'json',
            success: function(response) {
                // Fermer le modal
                jQuery('#alert-modal').modal('hide');

                // Afficher une notification basée sur la réponse
                if (response.status) {
                    showNotification('success', response.notification || '<?php echo get_phrase('exam_deleted_successfully'); ?>');
                } else {
                    showNotification('error', response.notification || '<?php echo get_phrase('failed_to_delete_exam'); ?>');
                }

                // Appeler le callback avec la réponse
                if (callback && typeof callback === 'function') {
                    callback(response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erreur AJAX : ', error);
                console.error('Statut : ', status);
                console.error('Réponse : ', xhr.responseText);
                showNotification('error', '<?php echo get_phrase('failed_to_delete_exam'); ?>');
                // Fermer le modal même en cas d'erreur
                jQuery('#alert-modal').modal('hide');
            }
        });
    });
}

function confirmModalRedirect(delete_url)
{
  jQuery('#alert-modal-redirect').modal('show', {backdrop: 'static'});
  // document.getElementById('alert-modal-redirect-url').setAttribute('href' , delete_url);
  // Mettre à jour l'action du formulaire avec l'URL de suppression
  document.getElementById('delete-form').setAttribute('action', delete_url);


}

function genericConfirmModal(callBackFunction)
{
  jQuery('#genric-confirmation-modal').modal('show', {backdrop: 'static'});
  callBackFunctionForGenericConfirmationModal = callBackFunction;
}

function callTheCallBackFunction() {
  $('#genric-confirmation-modal').modal('hide');
  callBackFunctionForGenericConfirmationModal();
}
function blankFunction(){

}
function reloadFunction(){
  // reload the current page
  window.location.reload();
}

function updateAjaxModal(url, header) {
    // Afficher un chargeur pendant la requête
    jQuery('#scrollable-modal .modal-body').html('<div style="text-align:center;margin-top:200px;"><img style="width: 100px; opacity: 0.4; " src="<?php echo base_url().'assets/backend/images/straight-loader.gif'; ?>" /></div>');
    jQuery('#scrollable-modal .modal-title').html('...');

    // Charger le contenu via AJAX
    $.ajax({
        url: url,
        success: function(response) {
            jQuery('#scrollable-modal .modal-body').html(response);
            jQuery('#scrollable-modal .modal-title').html(header);
        },
        error: function(xhr, status, error) {
            console.error('Erreur lors du chargement du contenu : ', error);
            console.error('Statut : ', status);
            console.error('Réponse : ', xhr.responseText);
            error_notify('Erreur lors du rechargement de la liste des questions.');
        }
    });
}

function updateLargeModal(url, header) {
    // S'assurer que le modal est visible
    if (!jQuery('#large-modal').hasClass('show')) {
        jQuery('#large-modal').modal('show', {backdrop: 'true'});
    }

    // Afficher un chargeur pendant la requête
    jQuery('#large-modal .modal-body').html('<div style="text-align:center;margin-top:200px;"><img style="width: 100px; opacity: 0.4; " src="<?php echo base_url().'assets/backend/images/straight-loader.gif'; ?>" /></div>');
    jQuery('#large-modal .modal-title').html('...');

    // Charger le contenu via AJAX
    $.ajax({
        url: url,
        success: function(response) {
            jQuery('#large-modal .modal-body').html(response);
            jQuery('#large-modal .modal-title').html(header);
        },
        error: function(xhr, status, error) {
            console.error('Erreur lors du chargement du contenu : ', error);
            console.error('Statut : ', status);
            console.error('Réponse : ', xhr.responseText);
            error_notify('Erreur lors du rechargement de la liste des questions.');
        }
    });
}
</script>



<!-- Right modal content -->
<div id="right-modal" class="modal fade" tabindex="0" role="dialog" aria-hidden="true" style="overflow-y: hidden !important;">
  <div class="modal-dialog modal-lg modal-right" style="width: 100% !important; max-width: 440px !important; min-height: 100% !important;">
    <div class="modal-content modal_height">

      <div class="modal-header border-1">
        <button type="button" class="btn btn-outline-secondary py-0 px-1" data-bs-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body" style="overflow-y: auto !important;">
        <div class="container-fluid text-center">
          <img src="<?php echo base_url('assets/backend/images/straight-loader.gif'); ?>" style="width: 60px; padding: 50% 0px; opacity: .6;">
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
  var myModalEl = document.getElementById('right-modal')
    myModalEl.addEventListener('hidden.bs.modal', function (event) {
      $('select.select2:not(.normal)').each(function () { $(this).select2(); });
  });
</script>


<!--  Large Modal -->
<div class="modal fade" id="large-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header d-print-none">
        <h4 class="modal-title" id="myLargeModalLabel"></h4>
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" aria-hidden="true">×</button>
      </div>
      <div class="modal-body">

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Info Alert Modal -->
<div id="alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body p-4">
        <div class="text-center">
          <i class="dripicons-information h1 text-info"></i>
          <h4 class="mt-2"><?php echo get_phrase('heads_up') ?>!</h4>
          <p class="mt-3"><?php echo get_phrase('are_you_sure'); ?>?</p>
          <form method="POST" class="ajaxDeleteForm" action="" id = "delete_form">
                <!-- Champ caché pour le jeton CSRF -->
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

            <button type="button" class="btn btn-info my-2" data-bs-dismiss="modal"><?php echo get_phrase('cancel'); ?></button>
            <button type="submit" class="btn btn-danger my-2" onclick=""><?php echo get_phrase('continue'); ?></button>
          </form>
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Info Alert Modal -->
<div id="alert-modal-confimation" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style=" z-index: 1056;">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body p-4">
        <div class="text-center">
          <i class="dripicons-information h1 text-info"></i>
          <h4 class="mt-2"><?php echo get_phrase('heads_up') ?>!</h4>
          <div class="modal-message">

          </div>
         
            <button type="button" class="btn btn-info my-2" data-bs-dismiss="modal"><?php echo get_phrase('ok'); ?></button>
       
          
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Info Alert Modal -->
<div id="alert-modal-redirect" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body p-4">
        <div class="text-center">
          <i class="dripicons-information h1 text-info"></i>
          <h4 class="mt-2"><?php echo get_phrase('heads_up') ?>!</h4>
          <p class="mt-3"><?php echo get_phrase('are_you_sure'); ?>?</p>
          <!-- <form id="delete-form" method="POST" action=""> -->
          <form method="POST" class="ajaxDeleteForm" action="" id = "delete-form">
            <!-- Ajoutez ici le jeton CSRF -->
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

          <button type="button" class="btn btn-info my-2" data-bs-dismiss="modal"><?php echo get_phrase('cancel'); ?></button>
          <button type="submit" class="btn btn-danger my-2" onclick="reloadFunction()"><?php echo get_phrase('continue'); ?></button>
          </form>
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Info Alert Modal THIS MODAL WAS USED BECAUSE OF SOME GENERIC ALERTS-->
<div id="genric-confirmation-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body p-4">
        <div class="text-center">
          <i class="dripicons-information h1 text-info"></i>
          <h4 class="mt-2"><?php echo get_phrase('heads_up') ?>!</h4>
          <p class="mt-3"><?php echo get_phrase('are_you_sure'); ?>?</p>
          <button type="button" class="btn btn-info my-2" data-bs-dismiss="modal"><?php echo get_phrase('cancel'); ?></button>
          <button type="submit" class="btn btn-danger my-2" onclick="callTheCallBackFunction()"><?php echo get_phrase('continue'); ?></button>
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="preview-modal" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content course-preview-modal">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" onclick="pageReload()">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center" style="min-height: 300px;">
            <img style="width: 60px; margin-top: 100px;" src="<?php echo site_url('assets/backend/images/straight-loader.gif'); ?>">
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  function pageReload(){
    //filterCourse();
    filterCourseFullPage();
    //location.reload();
  }
</script>

<!-- <script>
    jQuery(".ajaxDeleteForm").submit(function(e) {

        var form = $(this);
        ajaxSubmit(e, form, callBackFunction);
    });
</script> -->

<script>
  function showAjaxModal(url, header)
  {
      // SHOWING AJAX PRELOADER IMAGE
      jQuery('#scrollable-modal .modal-body').html('<div style="text-align:center;margin-top:200px;"><img style="width: 100px; opacity: 0.4; " src="<?php echo base_url().'assets/backend/images/straight-loader.gif'; ?>" /></div>');
      jQuery('#scrollable-modal .modal-title').html('...');
      // LOADING THE AJAX MODAL
      jQuery('#scrollable-modal').modal('show', {backdrop: 'true'});

      // SHOW AJAX RESPONSE ON REQUEST SUCCESS
      $.ajax({
          url: url,
          success: function(response)
          {
              jQuery('#scrollable-modal .modal-body').html(response);
              jQuery('#scrollable-modal .modal-title').html(header);
          }
      });
  }
</script>
<!-- Scrollable modal -->
<div class="modal fade" id="scrollable-modal" tabindex="-1" role="dialog" aria-labelledby="scrollableModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="scrollableModalTitle">Modal title</h5>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body ms-2 me-2">

          </div>
          <div class="modal-footer">
              <button class="btn btn-secondary" data-bs-dismiss="modal"><?php echo get_phrase("close"); ?></button>
          </div>
      </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>