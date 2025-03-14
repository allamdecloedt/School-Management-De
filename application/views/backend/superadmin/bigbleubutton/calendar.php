
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <!-- SweetAlert2 (popup moderne) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <!-- jQuery et FullCalendar JS -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

    <style>
        #calendar {
            max-width: 900px;
            margin: auto;
        }
    </style>


    <div id="calendar"></div>

    <!-- Bootstrap Modal -->
<!-- Bootstrap Modal -->
<!-- Bootstrap Modal -->
<div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appointmentModalLabel">Gérer le Rendez-vous</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="appointmentForm">
                    <input type="hidden" id="appointmentId">
                    <input type="hidden" id="classe_id" name="classe_id" value="<?= $classe_id; ?>">
                    <input type="hidden" id="room_id" name="room_id" value="<?= $room_id; ?>">

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
                            foreach ($sections as $section): ?>
                                <option value="<?php echo $section['id']; ?>"><?php echo $section['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mt-2 col-md-12">
                        <button type="submit" class="btn btn-primary">Sauvegarder</button>
                        <button type="button" id="deleteAppointment" class="btn btn-danger float-right">Supprimer</button>
                    </div>
                </form>
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




<!-- Ajoute Bootstrap -->

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<script>
  
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
            events: "<?= base_url('superadmin/get_appointments'); ?>", // Charge les rendez-vous

            // 👉 Ouvrir la popup quand on clique sur une date
            select: function (start, end, allDay) {
                $('#appointmentForm')[0].reset(); // Réinitialiser le formulaire
                $('#appointmentId').val(""); // Vide l'ID
                $('#appointmentDate').val(moment(start).format('YYYY-MM-DD HH:mm'));
                $('#appointmentModal').modal('show');
            },

            // 👉 Modifier un rendez-vous quand on clique dessus
            eventClick: function (event) {
                $('#appointmentId').val(event.id); // Stocker l'ID
                $('#appointmentTitle').val(event.title);
                $('#appointmentDate').val(moment(event.start).format('YYYY-MM-DD HH:mm'));
                $('#appointmentDescription').val(event.description);
                $('#section').val(event.section);
                $('#classe_id').val(event.classe_id);
                $('#room_id').val(event.room_id);

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

            var url = id ? "<?= base_url('superadmin/update_appointment'); ?>" : "<?= base_url('superadmin/add_appointment'); ?>";
            var successMessage = id ? "Rendez-vous mis à jour !" : "Rendez-vous ajouté avec succès !";


            $.ajax({
                url: url,
                type: "POST",
                data: { id: id, title: title, start: start, description: description, classe_id: classe_id, section: section, room_id: room_id },
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



     // Fonction pour afficher la notification
     function showCopyNotification($param) {
                let toastEl = document.getElementById($param);
                let toast = new bootstrap.Toast(toastEl);
                toast.show();
            }
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




