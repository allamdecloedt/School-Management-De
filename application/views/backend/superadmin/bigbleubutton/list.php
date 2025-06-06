<?php
$school_id = school_id();
// $meetings = $this->db->get_where('sessions_meetings', array('school_id' => $school_id ))->result_array();

$classes = $this->db->get_where('classes', array('school_id' => $school_id))->result_array();
$rooms = $this->db->get_where('rooms', array('school_id' => $school_id,'Etat' => 1))->result_array();

?>


     <!-- SweetAlert2 (popup moderne) -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
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
        .delete-room-btn {
            border-radius: 50%;
            padding: 3px 8px;
            font-size: 14px;
            opacity: 0.8;
            transition: opacity 0.2s ease-in-out;
        }

        .delete-room-btn:hover {
            opacity: 1;
        }
        .multi-select {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .multi-select label {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        #calendar {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
</style>






<div class="row mt-4">
    <?php foreach ($rooms as $room):
        $className = $this->db->get_where('classes', array('id' => $room['class_id']))->row('name');
        $status = '<span class="badge bg-danger">Non Démarrée</span>'; // Par défaut, réunion non démarrée
    ?>
        <div class="col-md-4">
            <div class="meeting-card position-relative">
                  <!-- Bouton de suppression en haut à droite -->
                <button class="btn btn-sm delete-room-btn position-absolute top-0 end-0 m-2"
                        data-room-id="<?php echo htmlspecialchars($room['id']); ?>"
                        title="Supprimer la Room"
                        data-bs-toggle="modal" 
                        data-bs-target="#confirmDeleteModal">
                    ✖️
                </button>

                <div class="meeting-title"><?php echo $room['name']; ?></div>
                <div class="meeting-time">Classe : <?php echo $className; ?></div>
                <div class="meeting-status" id="status-<?php echo $room['id']; ?>"><?php echo $status; ?></div>

                <!-- Nombre de participants -->
                <div class="meeting-participants" id="participants-<?php echo $room['id']; ?>">👥 0 participants</div>
                <a href="<?php echo route('Calendar/').$room['class_id'].'/'.$room['id']; ?>"><i class="mdi mdi-calendar">Calendar</i></a>

                <div class="d-flex justify-content-between align-items-center mt-2">
                    <a href="<?php echo base_url('bigbluebutton/start_meeting/' . $room['id']); ?>"
                    target="_blank" 
                    class="btn btn-success meeting-btn join-btn"
                    id="start-btn-<?php echo $room['id']; ?>"
                    data-meeting-id="<?php echo $room['id']; ?>">
                        Start
                    </a> 
                    <!-- Bouton de copie du lien -->
                    <!-- <button  class="btn btn-success meeting-btn join-btn" onclick="rightModal('<?php //echo site_url('modal/popup/bigbleubutton/apointement/'.$room['id']); ?>', '<?php echo get_phrase('appointment'); ?>')" class="btn btn-outline-secondary " 
                           id="copy-btn-<?php //echo $room['id']; ?>"
                           >
                           <?php //echo get_phrase('start'); ?>
                         
                    </button> -->
                    <!-- Bouton de copie du lien -->
                    <button onclick="rightModal('<?php echo site_url('modal/popup/bigbleubutton/edit/'.$room['id']); ?>', '<?php echo get_phrase('update_room'); ?>')" class="btn btn-outline-secondary " 
                           id="copy-btn-<?php echo $room['id']; ?>"
                            >
                      
                         <i class="dripicons-pencil"></i>
                    </button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>




    <div id="calendar"></div>



</div>



<div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appointmentModalLabel"><?php echo get_phrase('gerer_le_rendez_vous'); ?></h5>
                <button type="button" onclick="closeModal()" class="btn-close" ></button>
                
            </div>
            <div class="modal-body">
                <form id="appointmentForm">
                    <input type="hidden" id="appointmentId">
                    <input type="hidden" id="classe_id" name="classe_id" value="<?= $classe_id; ?>">
                    <input type="hidden" id="room_id" name="room_id" value="<?= $room_id; ?>">

                    <div class="form-group">
                        <label for="appointmentTitle"><?php echo get_phrase('titre_du_rendez_vous'); ?></label><span class="required"> * </span>
                        <input type="text" class="form-control" id="appointmentTitle" required>
                    </div>
                    <div class="form-group">
                        <label for="appointmentDate"><?php echo get_phrase('date_heure'); ?></label><span class="required"> * </span>
                        <input type="datetime-local" class="form-control" id="appointmentDate" required>
                    </div>
                    <div class="form-group">
                        <label for="appointmentDescription"><?php echo get_phrase('description'); ?></label>
                        <textarea class="form-control" id="appointmentDescription" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="section"><?php echo get_phrase('section'); ?></label><span class="required"> * </span>
                        <select class="form-control" name="section[]" id="section" multiple>

                        </select>
                    </div>
                    <div class="form-group mt-2 col-md-12">
                        <button type="submit" class="btn btn-primary"><?php echo get_phrase('sauvegarder'); ?></button>
                        <button type="button" id="deleteAppointment" class="btn btn-danger float-right"><?php echo get_phrase('delete'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="appointmentModal_NonID" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appointmentModalLabel">Add new appointment directly from room calendar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

        </div>
    </div>
</div>

<!-- Notification dynamique -->
<div id="DynamicNotification" class="toast align-items-center text-white bg-success border-0 position-fixed bottom-0 end-0 p-2 m-3" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
        <div class="toast-body">
            Action effectuée avec succès.
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
</div>


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $('#section').selectpicker();
    });
</script>
<script type="text/javascript">
function closeModal() {
    $("#appointmentModal").modal("hide");
}


   $(document).ready(function () {
        var calendar = $('#calendar').fullCalendar({
            titleRangeSeparator: ' - ',
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            selectable: true,
            selectHelper: true,
            editable: true,
            eventLimit: true,
            events: "<?= base_url('superadmin/get_appointments'); ?>", // Charge les rendez-vous

            // 👉 Ouvrir la popup quand on clique sur une date
            select: function (start, end, allDay) {
                $('#appointmentForm')[0].reset(); // Réinitialiser le formulaire
                $('#appointmentId').val(""); // Vide l'ID
                $('#appointmentDate').val(moment(start).format('YYYY-MM-DD HH:mm'));

                $('#appointmentModal_NonID').modal('show');
            },
            eventRender: function(event, element) {
                let time = moment(event.start).format('HH:mm'); // Extraire l'heure correctement
                let title = event.title.replace(/(\d{2})a/, '$1:00 -'); // Nettoyer le titre
                
                element.html(`<strong>${time}</strong> - ${title}`);
            },
            

            // 👉 Modifier un rendez-vous quand on clique dessus
            eventClick: function (event) {
                $('#appointmentId').val(event.id); // Stocker l'ID
                $('#appointmentTitle').val(event.title);
                $('#appointmentDate').val(moment(event.start).format('YYYY-MM-DD HH:mm'));
                $('#appointmentDescription').val(event.description);
    
                $('#classe_id').val(event.classe_id);
                $('#room_id').val(event.room_id);

             
         
                    // Charger les sections dynamiquement
                    $.ajax({
                        url: "<?= base_url('superadmin/get_sections'); ?>",
                        type: "POST",
                        data: { classe_id: event.classe_id },
                        success: function (response) {

                       

                      

                                var sections = JSON.parse(response);
                                $('#section').empty();

                                $.each(sections, function (key, value) {
                                    $('#section').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                                });

                                // 👇 Sélection multiple
                                let selectedSections = event.section ? event.section.split(',') : [];
                              

                                // ⚠️ Attendre que les <option> soient bien injectés
                                setTimeout(function () {
                                    $('#section').val(selectedSections).trigger('change');
                                    $('#section').selectpicker('destroy'); // Supprime Bootstrap Select
                                    $('#section').selectpicker();
                                }, 100);
                        },
                        error: function () {
                            console.error("Erreur lors du chargement des sections.");
                        }
                    });
                $('#appointmentModal').modal('show');

                // Supprimer l'événement


                $('#deleteAppointment').off().on('click', function () {
                    var id = $('#appointmentId').val();

                    Swal.fire({
                        title: "Êtes-vous sûr ?",
                        text: "Cette action est irréversible !",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Oui, supprimer !",
                        cancelButtonText: "Annuler"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "<?= base_url('superadmin/delete_appointment'); ?>",
                                type: "POST",
                                data: { id: id },
                                success: function () {
                                    $('#appointmentModal').modal('hide');
                                    $('#calendar').fullCalendar('refetchEvents'); // Rafraîchir le calendrier
                                    Swal.fire("Supprimé !", "Le rendez-vous a été supprimé.", "success");
                                },
                                error: function () {
                                    Swal.fire("Erreur", "Impossible de supprimer le rendez-vous.", "error");
                                }
                            });
                        }
                    });
                });

            }
        });

        // 👉 Ajouter ou modifier un rendez-vous en cliquant sur "Sauvegarder"
        $('#appointmentForm').on('submit', function (e) {
            e.preventDefault();

            var id = $('#appointmentId').val();
            var title = $('#appointmentTitle').val();
            var start = $('#appointmentDate').val();
            var description = $('#appointmentDescription').val();
            var classe_id = $('#classe_id').val();
            var section = $('#section').val();
            var room_id = $('#room_id').val();
            // 🔥 Corriger la gestion des sections multiples : Transformer en string séparée par ","
            if (Array.isArray(section)) {
                sections = section.join(','); // Convertir ["1", "2", "3"] → "1,2,3"
            }

            var url = "<?= base_url('superadmin/update_appointment'); ?>" ;
            var successMessage =  "Rendez-vous mis à jour !";


            $.ajax({
                url: url,
                type: "POST",
                data: { id: id, title: title, start: start, description: description, classe_id: classe_id, sections: sections, room_id: room_id },
                success: function () {
                 
                    $('#appointmentModal').modal('hide');
                    $('#calendar').fullCalendar('refetchEvents'); // Rafraîchir le calendrier
                    // alert(id ? "Rendez-vous mis à jour !" : "Rendez-vous ajouté !");
                    showNotification(successMessage, "success");
                },
                error: function () {
                    showNotification("Une erreur est survenue.", "error");
                }
            });
        });
    });

    // Fonction pour afficher une notification dynamique avec un message personnalisé
    function showNotification(message, type = "success") {
        let toastEl = document.getElementById("DynamicNotification");

        // Modifier le texte et la classe de la notification
        let toastBody = toastEl.querySelector(".toast-body");
        toastBody.innerHTML = message;

        // Modifier la couleur selon le type (success, danger, warning, info)
        toastEl.className = "toast align-items-center text-white border-0 position-fixed bottom-0 end-0 p-2 m-3";
        if (type === "success") {
            toastEl.classList.add("bg-success");
        } else if (type === "error") {
            toastEl.classList.add("bg-danger");
        } else if (type === "warning") {
            toastEl.classList.add("bg-warning text-dark");
        } else {
            toastEl.classList.add("bg-info");
        }

        let toast = new bootstrap.Toast(toastEl);
        toast.show();
    }
</script>
