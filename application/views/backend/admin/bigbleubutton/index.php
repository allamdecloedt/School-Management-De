
<!--title-->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title d-inline-block">
          <i class="mdi mdi-account-circle title_icon"></i> <?php echo get_phrase('my_Rooms'); ?>
        </h4>
        <button type="button" class="btn btn-outline-primary btn-rounded align-middle mt-1 float-end" onclick="rightModal('<?php echo site_url('modal/popup/bigbleubutton/create'); ?>', '<?php echo get_phrase('New_Room'); ?>')"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('New_Room'); ?></button>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="row mt-3">
                <div class="col-md-1"></div>
                <div class="col-md-4">

                </div>

                <div class="col-md-2">
                    <!-- <button class="btn btn-block btn-secondary" onclick="startMeeting()"  ><?php //echo get_phrase('Démarrer'); ?></button> -->
                    <!-- <button class="btn btn-block btn-secondary" onclick="startMeeting('moderator')">Rejoindre en tant que Modérateur</button> -->
                    <!-- <button class="btn btn-block btn-secondary" onclick="startMeeting('attendee')">Rejoindre en tant que Participant</button> -->
                </div>
            </div>
          
        </div>
    </div>
</div>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body room_content">
        <?php include 'list.php'; ?>
      </div>
    </div>
  </div>
</div>




<!-- POPUP DE CONFIRMATION -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteLabel">❌ Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>⚠️ Êtes-vous sûr de vouloir supprimer cette room ? Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn_room" data-bs-dismiss="modal">Supprimer</button>
            </div>
        </div>
    </div>
</div>






<!-- modyfy section -->
<script>
      
      function startMeeting(role) {
        // window.location.href = "http://localhost/SchoolManagement/bigbluebutton/start/" + role;
        window.open("http://localhost/SchoolManagement/bigbluebutton/start", "_blank");
    }








        document.addEventListener("DOMContentLoaded", function () {
            function checkActiveMeetings() {
                fetch("<?php  echo base_url('bigbluebutton/get_active_meetings'); ?>")
                    .then(response => response.json())
                    .then(data => {
                        console.log("Données reçues :", data);
                        
                        if (!data.active_meetings || !Array.isArray(data.active_meetings)) {
                            console.error("Format de données incorrect :", data);
                            return;
                        }

                        data.active_meetings.forEach(meeting => {
                            let roomID = meeting.room_id;
                            let isRunning = meeting.running === "true";
                            let participantCount = meeting.participant_count || 0;
                            let meetingID = meeting.meeting_id;

                            let statusElement = document.getElementById(`status-${roomID}`);
                            let startButton = document.getElementById(`start-btn-${roomID}`);
                            let participantElement = document.getElementById(`participants-${roomID}`);
                            let copyButton = document.getElementById(`copy-btn-${roomID}`);

                            if (statusElement) {
                                statusElement.innerHTML = isRunning 
                                    ? `<span class="badge bg-success">En Cours</span>` 
                                    : `<span class="badge bg-danger">Non Démarrée</span>`;
                            }

                            if (startButton) {
                                startButton.innerText = isRunning ? "Join" : "Start";
                                startButton.href = isRunning 
                                    ? "<?php  echo base_url('bigbluebutton/join_meeting/'); ?>" + meetingID 
                                    : "<?php echo base_url('bigbluebutton/start_meeting/'); ?>" + roomID;
                            }

                            if (participantElement) {
                                participantElement.innerHTML = `👥 ${participantCount} participants`;
                            }

                            // Vérifier si meetingID est bien défini avant de mettre à jour le bouton de copie
                            if (copyButton) {
                                if (meetingID) {
                                    let meetingLink = "<?php // echo base_url('bigbluebutton/join_meeting/'); ?>" + meetingID;
                                    copyButton.setAttribute("data-url", meetingLink);
                                    copyButton.style.display = "inline-block"; // Afficher le bouton s'il y a un lien
                                } else {
                                    console.warn("Aucun meetingID valide trouvé pour roomID :", roomID);
                                    copyButton.style.display = "none"; // Masquer le bouton s'il n'y a pas de meeting actif
                                }
                            }
                        });
                    })
                    .catch(error => console.error("Erreur lors de la récupération des réunions :", error));
            }

            // Vérification toutes les 5 secondes
            setInterval(checkActiveMeetings, 100000);
            // checkActiveMeetings();

        
        });

        document.addEventListener("DOMContentLoaded", function () { 
            let selectedRoomID = null;
        

            // 🎯 Utilisation de l'event delegation pour éviter d'ajouter trop d'écouteurs
            document.body.addEventListener("click", function (event) {
                if (event.target.classList.contains("delete-room-btn")) {
                    selectedRoomID = event.target.dataset.roomId;
                }
            });
        
            // 🎯 Lorsqu'on clique sur "Supprimer" dans le modal
            document.getElementById("confirmDeleteBtn_room").addEventListener("click", async function () {
                if (!selectedRoomID) {
                    console.error("❌ Erreur : Aucun ID de room sélectionné !");
                    return;
                }
 
                try {
                    $.ajax({
                                url: "<?= base_url('admin/delete_room'); ?>",
                                type: "POST",
                                data: { selectedRoomID: selectedRoomID },
                                success: function () {                       // ✅ Fermeture correcte du modal
                                    let modal = document.getElementById('confirmDeleteModal');
                                    modal.classList.remove('show');
                                    modal.setAttribute('aria-hidden', 'true');
                                    document.body.classList.remove('modal-open');

                                    // Supprimer le backdrop si nécessaire (si Bootstrap ne le gère pas)
                                    let backdrop = document.querySelector(".modal-backdrop");
                                    if (backdrop) backdrop.remove();

                                    // ✅ Rafraîchir la liste des rooms
                                    showAllRooms();
                                    
                                    // ✅ Notification de succès
                                    Swal.fire("Supprimé !", "La room a été supprimée avec succès.", "success");
                                },
                                error: function () {
                                    Swal.fire("Erreur", `❌ Impossible de supprimer la room : ${data.message}`, "error");
                                }
                            });

                                let response = await fetch("<?= base_url('admin/delete_room'); ?>", {
                                    method: "POST",
                                    headers: { "Content-Type": "application/json" },
                                    body: JSON.stringify({ selectedRoomID })
                                });
                } catch (error) {
                    console.error("❌ Erreur lors de la suppression :", error);
                    Swal.fire("Erreur", "❌ Une erreur inattendue est survenue.", "error");
                }
            });
        });





                

          


        var showAllRooms = function () {
            var url = '<?php echo route('Liveclasse/list'); ?>';

            $.ajax({
                type : 'GET',
                url: url,
                success : function(response) {
                $('.room_content').html(response);
                initDataTable('basic-datatable');
                }
            });
        }

</script>

