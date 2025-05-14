<?php
$this->db->where('room_id', $param1);
$this->db->where('start_date >', date('Y-m-d H:i:s')); // uniquement les RDV futurs
$appointments = $this->db->get('appointments')->result_array();
$appointments_count = count($appointments);

?>


<!-- <form method="POST" class="d-block ajaxForm" action="<?php // echo route('Liveclasse/update/'.$param1); ?>" > -->
    <!-- Champ caché pour le jeton CSRF -->
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

    <?php
        $school_id = school_id();
        ?>
    
    <div class="form-row">
       

        <div class="form-group mb-1">
          
            <label for="appointementSelect" class="form-label"><?php echo get_phrase('Select_an_appointment'); ?>  </label>
            <select class="form-select" id="appointementSelect" name="appointementSelect"  required>
                <option value="">Choisissez un Appointement</option>
                    <?php foreach ($appointments as $appointment): ?>
                        <option value="<?php echo $appointment['id']; ?>"><?php echo $appointment['title']; ?></option>
                    <?php endforeach; ?>
            </select>
            <span id="message_obligatoir" style="color: red;"></span></br>
            <?php if($appointments_count == 0){ ?>
            <span  style="color: red;"><?php echo get_phrase('Please_add_an_appointment_in_the_calendar'); ?></span></br>
            <?php } ?>
   
        </div>


        <a href="<?php echo base_url('bigbluebutton/start_meeting/' .$param1); ?>"
                    target="_blank" 
                    class="btn btn-success meeting-btn join-btn"
                    id="start-btn-<?php echo $param1; ?>"
                    data-meeting-id="<?php echo $param1; ?>">
                    <?php echo get_phrase('join'); ?>                    </a>
  
    </div>
<!-- </form> -->


<script>
    $(document).ready(function () {
        $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); });
    });
    

    $(".ajaxForm").validate({}); // Jquery form validation initialization
    $(".ajaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, showAllRooms);
       
    });
    $(document).ready(function () {
    var baseMeetingUrl = "<?php echo base_url('bigbluebutton/start_meeting/'); ?>";
    var roomId = "<?php echo $param1; ?>";
    var $joinBtn = $('#start-btn-<?php echo $param1; ?>');

    // Désactiver le bouton au départ
    $joinBtn.prop('disabled', true);

    // Lors de la sélection d’un appointment
    $('#appointementSelect').on('change', function () {
        var selectedAppointmentId = $(this).val();

        if (selectedAppointmentId) {
            var finalUrl = baseMeetingUrl + roomId + '?appointment_id=' + selectedAppointmentId;
            $joinBtn.attr('href', finalUrl).prop('disabled', false);
            $('#message_obligatoir').text("");

        } else {
            $joinBtn.attr('href', '#').prop('disabled', true);
        }
    });

    // Empêche de cliquer si rien n’est sélectionné
    $joinBtn.on('click', function (e) {
        var selectedAppointmentId = $('#appointementSelect').val();
        if (!selectedAppointmentId) {
            e.preventDefault();
            $('#message_obligatoir').text("Veuillez sélectionner un appointment avant de rejoindre la réunion.");
           
        }
    });
});

</script>
