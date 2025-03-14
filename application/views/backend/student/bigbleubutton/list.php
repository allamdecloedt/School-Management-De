<?php
$school_id = school_id();
$meetings = $this->db->get_where('sessions_meetings', array('school_id' => $school_id ))->result_array();

$classes = $this->db->get_where('classes', array('school_id' => $school_id))->result_array();
$rooms = $this->db->get_where('rooms', array('Etat' => 1))->result_array();

?>
 <!-- FullCalendar CSS -->
 <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">

<style>
        .meeting-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 10px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: 0.3s;
        }
        .meeting-card:hover {
            box-shadow: 4px 4px 15px rgba(0,0,0,0.2);
        }
        .meeting-btn {
            width: 100%;
        }
        .meeting-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .meeting-time {
            color: #777;
            font-size: 14px;
        }
    </style>

<div class="container mt-4">
    <!-- <div class="d-flex justify-content-between">
        <input type="text" class="form-control w-25" placeholder="🔍 Search">
        <a href="<?php // echo base_url('bigbluebutton/create'); ?>" class="btn btn-primary">+ New Room</a>
    </div> -->
 
</div>



   





<div class="row mt-4">
    <?php foreach ($rooms as $room):
        $className = $this->db->get_where('classes', array('id' => $room['class_id']))->row('name');
        $status = '<span class="badge bg-danger">Non Démarrée</span>'; // Par défaut, réunion non démarrée

        $user_id   = $this->session->userdata('user_id');
        
        $this->db->where('user_id', $user_id);
        $this->db->where('school_id', $room['school_id']);
        $check_student = $this->db->get('students')->row_array();    
        

        $this->db->where('student_id', $check_student['id']);
        $this->db->where('school_id', $room['school_id']);
        $this->db->where('class_id', $room['class_id']);
        $query = $this->db->get('enrols');
        $count = $query->num_rows();

        if($count != 0 ){
    ?>
        <div class="col-md-4">
            <div class="meeting-card">
                <div class="meeting-title"><?php echo $room['name']; ?></div>
                <div class="meeting-time">Classe : <?php echo $className; ?></div>
                <div class="meeting-status" id="status-<?php echo $room['id']; ?>"><?php echo $status; ?></div>

                <!-- Nombre de participants -->
                <div class="meeting-participants" id="participants-<?php echo $room['id']; ?>">👥 0 participants</div>
                

                <div class="d-flex justify-content-between align-items-center mt-2">
                    <!-- Bouton "Join" désactivé par défaut -->
                    <a href="#" 
                    target="_blank" 
                    class="btn btn-secondary meeting-btn join-btn disabled"
                    id="join-btn-<?php echo $room['id']; ?>"
                    data-meeting-id="<?php echo $room['id']; ?>">
                        Join 
                    </a>


                </div>
            </div>
        </div>
    <?php
         }

        endforeach; ?>
</div>

<div id="calendar"></div>





</div>


<div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appointmentModalLabel">Gérer le Rendez-vous</h5>
     
                <button type="button" onclick="closeModal()" class="btn-close" ></button>


            </div>
            <div class="modal-body">
               

                    <div class="form-group">
                        <label for="appointmentTitle">Titre du Rendez-vous</label>
                        <input type="text" class="form-control" id="appointmentTitle" required>
                    </div>
                    <div class="form-group">
                        <label for="appointmentDate">Date et heure</label>
                        <input type="datetime-local" class="form-control" id="appointmentDate" required>
                    </div>
                    <div class="form-group">
                        <label for="appointmentDescription">Description</label>
                        <textarea class="form-control" id="appointmentDescription" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="section">Section</label>
                        <select class="form-control" name="section" id="section">
                            <?php 
                            $sections = $this->db->get_where('sections', array('class_id' => $classe_id))->result_array();
                            var_dump($classe_id);
                            var_dump($sections);
                            foreach ($sections as $section): ?>
                                <option value="<?php echo $section['id']; ?>"><?php echo $section['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                
            </div>
        </div>
    </div>
</div>
	
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript">
   $(document).ready(function () {

    function closeModal() {
        $("#appointmentModal").modal("hide");
    }
        var calendar = $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            selectable: true,
            selectHelper: true,
            editable: true,
            eventLimit: true,
            events: "<?= base_url('student/get_appointments'); ?>", // Charge les rendez-vous
           

            // 👉 Ouvrir la popup quand on clique sur une date
            select: function (start, end, allDay) {
           

                $('#appointmentModal_NonID').modal('show');
            },
            

            // 👉 Modifier un rendez-vous quand on clique dessus
            eventClick: function (event) {
                console.log(event);
                $('#appointmentId').val(event.id); // Stocker l'ID
                $('#appointmentTitle').val(event.title);
                $('#appointmentDate').val(moment(event.start).format('YYYY-MM-DD HH:mm'));
                $('#appointmentDescription').val(event.description);
                // $('#section').val(event.section);
                $('#classe_id').val(event.classe_id);
                $('#room_id').val(event.room_id);
             
                    // $('#section').val(event.section).change();
                    // Charger les sections dynamiquement
                    $.ajax({
                        url: "<?= base_url('student/get_sections'); ?>",
                        type: "POST",
                        data: { classe_id: event.classe_id },
                        success: function (response) {
                            var sections = JSON.parse(response);
                            $('#section').empty();
                            $.each(sections, function (key, value) {
                                $('#section').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                            });

                            $('#section').val(event.section).change();
                        },
                        error: function () {
                            console.error("Erreur lors du chargement des sections.");
                        }
                    });
                $('#appointmentModal').modal('show');

                // Supprimer l'événement


               

            }
        });

  
    });
</script>
