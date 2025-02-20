<!--title-->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title d-inline-block">
          <i class="mdi mdi-account-multiple-check title_icon"></i> <?php echo get_phrase('Réunions'); ?>
        </h4>
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
      <div class="card-body admin_content">
        <?php include 'list.php'; ?>
      </div>
    </div>
  </div>
</div>


<div id="copyNotification" class="toast align-items-center text-white bg-danger border-0 position-fixed bottom-0 end-0 p-2 m-3" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
        <div class="toast-body">
            ⚠️ Aucun lien de réunion disponible pour la copie.
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
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
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Supprimer</button>
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

    // document.addEventListener("DOMContentLoaded", function() {
    //     fetch("<?php echo base_url('bigbluebutton/get_meetings'); ?>")
    //     .then(response => response.json())
    //     .then(data => {
    //         if (data.returncode === "SUCCESS" && data.meetings && data.meetings.meeting) {
    //             let meetings = Array.isArray(data.meetings.meeting) ? data.meetings.meeting : [data.meetings.meeting];
                
    //             meetings.forEach(meeting => {
    //                 let meetingID = meeting.meetingID;
    //                 let participantCount = meeting.participantCount || 0;
    //                 let isRunning = meeting.running === "true";
                    
    //                 // Vérifier si cette réunion existe dans la liste
    //                 let meetingStatus = document.getElementById(`status-${meetingID}`);
    //                 if (meetingStatus) {
    //                     meetingStatus.innerHTML = isRunning 
    //                         ? `<span class="badge bg-success">En Cours - ${participantCount} Participants</span>` 
    //                         : `<span class="badge bg-danger">Non Démarrée</span>`;
    //                 }
    //             });
    //         } else {
    //             console.log("Aucune réunion active.");
    //         }
    //     })
    //     .catch(error => console.error("Erreur lors de la récupération des réunions :", error));
    // });

    
        // document.getElementById("createRoomForm").addEventListener("submit", function(event) {
        //     event.preventDefault();
        //     let roomName = document.getElementById("roomName").value;
        //     let description = document.getElementById("description").value;

        //     fetch("<?php // echo base_url('bigbluebutton/create_room'); ?>", {
        //         method: "POST",
        //         body: JSON.stringify({ room_name: roomName, description: description , class_id: classID }),
        //         headers: { "Content-Type": "application/json" }
        //     })
        //     .then(response => response.json())
        //     .then(data => {
        //         if (data.status === "success") {
        //             alert("Salle créée avec succès !");
        //             location.reload();
        //         } else {
        //             alert("Erreur : " + data.message);
        //         }
        //     })
        //     .catch(error => console.error("Erreur lors de la création de la salle :", error));
        // });



//     document.addEventListener("DOMContentLoaded", function() {
//     function updateMeetingStatus() {
//         fetch("<?php echo base_url('bigbluebutton/get_meetings'); ?>")
//         .then(response => response.json())
//         .then(data => {
//             if (data.returncode === "SUCCESS" && data.meetings && data.meetings.meeting) {
//                 let meetings = Array.isArray(data.meetings.meeting) ? data.meetings.meeting : [data.meetings.meeting];
                
//                 meetings.forEach(meeting => {
//                     let meetingID = meeting.meetingID;
//                     let participantCount = meeting.participantCount || 0;
//                     let isRunning = meeting.running === "true";
                    
//                     // Vérifier si cette réunion existe dans la liste
//                     let meetingStatus = document.getElementById(`status-${meetingID}`);
//                     let joinButton = document.getElementById(`join-btn-${meetingID}`);

//                     if (meetingStatus) {
//                         meetingStatus.innerHTML = isRunning 
//                             ? `<span class="badge bg-success">En Cours - ${participantCount} Participants</span>` 
//                             : `<span class="badge bg-danger">Non Démarrée</span>`;
//                     }

//                     if (joinButton) {
//                         if (isRunning) {
//                             joinButton.classList.remove("btn-success");
//                             joinButton.classList.add("btn-primary");
//                             joinButton.innerText = "Join";
//                         } else {
//                             joinButton.classList.remove("btn-primary");
//                             joinButton.classList.add("btn-success");
//                             joinButton.innerText = "Start";
//                         }
//                     }
//                 });
//             } else {
//                 console.log("Aucune réunion active.");
//             }
//         })
//         .catch(error => console.error("Erreur lors de la récupération des réunions :", error));
//     }

//     // Rafraîchir toutes les 10 secondes
//     setInterval(updateMeetingStatus, 5000);
//     updateMeetingStatus();
// });


// document.addEventListener("DOMContentLoaded", function() {
//     function updateMeetingStatus() {
//         fetch("<?php //echo base_url('bigbluebutton/check_active_meetings'); ?>")
//         .then(response => response.json())
//         .then(data => {
//             if (data.status === "success") {
//                 let activeMeetings = data.meetings;
//                 console.log(data.status);

//                 document.querySelectorAll(".meeting-card").forEach(card => {
//                     let meetingID = card.getAttribute("data-meeting-id");
//                     let joinButton = card.querySelector(".join-btn");
//                     let statusBadge = card.querySelector(".meeting-status");

//                     if (activeMeetings[meetingID]) {
//                         joinButton.innerText = "Join";
//                         joinButton.classList.remove("btn-success");
//                         joinButton.classList.add("btn-warning");
//                         joinButton.href = "<?php //echo base_url('bigbluebutton/join_meeting/'); ?>" + meetingID;
//                         statusBadge.innerHTML = `<span class="badge bg-success">En Cours (${activeMeetings[meetingID].participantCount} Participants)</span>`;
//                     } else {
//                         joinButton.innerText = "Start";
//                         joinButton.classList.remove("btn-warning");
//                         joinButton.classList.add("btn-success");
//                         joinButton.href = "<?php //echo base_url('bigbluebutton/start_meeting/'); ?>" + meetingID;
//                         statusBadge.innerHTML = `<span class="badge bg-danger">Non Démarrée</span>`;
//                     }
//                 });
//             }
//         })
//         .catch(error => console.error("Erreur lors de la mise à jour des réunions :", error));
//     }

//     // Exécuter toutes les 5 secondes pour actualisation en temps réel
//     setInterval(updateMeetingStatus, 5000);
// });
// hana
// document.addEventListener("DOMContentLoaded", function () {
//     function checkActiveMeetings() {
//         fetch("<?php echo base_url('bigbluebutton/get_active_meetings'); ?>")
//             .then(response => response.json())
//             .then(data => {
//                 data.active_meetings.forEach(meeting => {
//                     let roomID = meeting.room_id;
//                     // console.log(data);
//                     console.log("Données reçues :", data);
//                     let isRunning = meeting.running === "true";
//                     let participantCount = meeting.participant_count;
//                     let meetingID = meeting.meeting_id;

//                     let statusElement = document.getElementById(`status-${roomID}`);
//                     let startButton = document.getElementById(`start-btn-${roomID}`);
//                     let participantElement = document.getElementById(`participants-${roomID}`);
//                     let copyButton = document.getElementById(`copy-btn-${roomID}`);


//                     if (statusElement && startButton) {
//                         if (isRunning) {
//                             statusElement.innerHTML = `<span class="badge bg-success">En Cours</span>`;
//                             startButton.innerText = "Join";
//                             startButton.href = "<?php echo base_url('bigbluebutton/join_meeting/'); ?>" + meeting.meeting_id;
//                         } else {
//                             statusElement.innerHTML = `<span class="badge bg-danger">Non Démarrée</span>`;
//                             startButton.innerText = "Start";
//                             startButton.href = "<?php echo base_url('bigbluebutton/start_meeting/'); ?>" + roomID;
//                         }
//                     }
//                       // Mise à jour du nombre de participants
//                       if (participantElement) {
//                         participantElement.innerHTML = `👥 ${participantCount} participants`;
//                         }
//                         // Mise à jour du bouton de copie
//                         if (copyButton) {
//                             copyButton.setAttribute("data-url", "<?php echo base_url('bigbluebutton/join_meeting/'); ?>" + meetingID);
//                         }
//                 });
//             })
//             .catch(error => console.error("Erreur lors de la récupération des réunions :", error));
//     }

//     // Vérification en boucle toutes les 5 secondes
//     setInterval(checkActiveMeetings, 5000);
//     checkActiveMeetings();


//        // Gestion du bouton de copie
//     document.querySelectorAll(".copy-btn").forEach(button => {
//         button.addEventListener("click", function () {
//             let meetingURL = this.getAttribute("data-url");

//             if (!meetingURL) {
//                 console.error("Aucun lien de réunion disponible pour la copie.");
//                 return;
//             }

//             navigator.clipboard.writeText(meetingURL)
//                 .then(() => {
//                     this.innerText = "✅";
//                     this.title = "Lien copié !";

//                     setTimeout(() => {
//                         this.innerText = "📋";  
//                         this.title = "Copier le lien";
//                     }, 2000);
//                 })
//                 .catch(err => {
//                     console.error("Erreur lors de la copie du lien :", err);
//                 });
//         });
//     });
// });







document.addEventListener("DOMContentLoaded", function () {
    function checkActiveMeetings() {
        fetch("<?php echo base_url('bigbluebutton/get_active_meetings'); ?>")
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
                            ? "<?php echo base_url('bigbluebutton/join_meeting/'); ?>" + meetingID 
                            : "<?php echo base_url('bigbluebutton/start_meeting/'); ?>" + roomID;
                    }

                    if (participantElement) {
                        participantElement.innerHTML = `👥 ${participantCount} participants`;
                    }

                    // Vérifier si meetingID est bien défini avant de mettre à jour le bouton de copie
                    if (copyButton) {
                        if (meetingID) {
                            let meetingLink = "<?php echo base_url('bigbluebutton/join_meeting/'); ?>" + meetingID;
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
    setInterval(checkActiveMeetings, 5000);
    checkActiveMeetings();

    // Gestion du bouton de copie
    document.querySelectorAll(".copy-btn").forEach(button => {
        button.addEventListener("click", function () {
            let meetingURL = this.getAttribute("data-url");

            if (!meetingURL || meetingURL.trim() === "" || meetingURL.includes("undefined") || meetingURL.includes("null")) {
            console.warn("⚠️ Aucun lien de réunion disponible pour la copie.");
            // alert("Aucun lien de réunion disponible pour la copie."); // Affichage d'une alerte pour informer l'utilisateur
            showCopyNotification();
            return;
        }

            navigator.clipboard.writeText(meetingURL)
                .then(() => {
                    this.innerText = "✅";
                    this.title = "Lien copié !";

                    setTimeout(() => {
                        this.innerText = "📋";  
                        this.title = "Copier le lien";
                    }, 2000);
                })
                .catch(err => {
                    console.error("Erreur lors de la copie du lien :", err);
                });
        });
    });
});


document.addEventListener("DOMContentLoaded", function () {
    let selectedRoomID = null; // Stocker l'ID de la room sélectionnée
    
    // Lorsqu'on clique sur le bouton ❌, ouvrir le pop-up et stocker l'ID de la room
    document.querySelectorAll(".delete-room-btn").forEach(button => {
        button.addEventListener("click", function () {
            selectedRoomID = this.getAttribute("data-room-id");
        });
    });

    // Lorsqu'on clique sur "Supprimer" dans le pop-up
    document.getElementById("confirmDeleteBtn").addEventListener("click", function () {
        if (!selectedRoomID) return;
        alert(selectedRoomID);
       
        fetch("<?php echo base_url('bigbluebutton/delete_room/'); ?>" , {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ selectedRoomID: selectedRoomID})
        })
        .then(response => response.json())
        .then(data => {
            // Affichage d'une notification de succès ou d'erreur
            if (data.status === "success") {
                // alert("✅ Room supprimée avec succès !");
                location.reload(); // Rafraîchir la page
            } else {
                alert("❌ Erreur : " + data.message);
            }
        })
        .catch(error => console.error("Erreur :", error));
    });
});

















// document.addEventListener("DOMContentLoaded", function () {
//         document.querySelectorAll(".copy-btn").forEach(button => {
//             button.addEventListener("click", function () {
//                 let meetingURL = this.getAttribute("data-url");

//                 // Copier dans le presse-papier
//                 navigator.clipboard.writeText(meetingURL).then(() => {
//                     // Changer le bouton temporairement pour afficher "Copié !"
//                     this.innerText = "✅";
//                     this.title = "Lien copié !";
                    
//                     setTimeout(() => {
//                         this.innerText = "📋";  // Rétablir l'icône de copie
//                         this.title = "Copier le lien";
//                     }, 2000);
//                 }).catch(err => {
//                     console.error("Erreur lors de la copie du lien :", err);
//                 });
//             });
//         });
//     });


// document.addEventListener("DOMContentLoaded", function() {
//     fetch("<?php //echo base_url('bigbluebutton/get_meetings'); ?>")
//     .then(response => response.json())
//     .then(data => {
//         if (data.returncode === "SUCCESS" && data.meetings && data.meetings.meeting) {
//             let meetings = Array.isArray(data.meetings.meeting) ? data.meetings.meeting : [data.meetings.meeting];

//             meetings.forEach(meeting => {
//                 let meetingID = meeting.meetingID;
//                 let isRunning = meeting.running === "true";

//                 let joinButton = document.getElementById(`join-btn-${meetingID}`);
//                 let statusBadge = document.getElementById(`status-${meetingID}`);

//                 if (joinButton && statusBadge) {
//                     if (isRunning) {
//                         joinButton.innerText = "Join";
//                         joinButton.href = "<?php echo base_url('bigbluebutton/join/'); ?>" + meetingID;
//                         statusBadge.innerHTML = `<span class="badge bg-success">En Cours</span>`;
//                     }
//                 }
//             });
//         }
//     })
//     .catch(error => console.error("Erreur lors de la récupération des réunions :", error));

//     document.querySelectorAll('.copy-btn').forEach(button => {
//         button.addEventListener('click', function() {
//             navigator.clipboard.writeText(this.dataset.url).then(() => {
//                 alert("Lien copié !");
//             });
//         });
//     });
// });




    
    document.getElementById("createRoomForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Empêcher le rechargement de la page
    
    // let meetingName = document.getElementById("meetingName").value;
    let roomName = document.getElementById("roomName").value;
    let description = document.getElementById("description").value;
    let classID = document.getElementById("classSelect").value;
    
    if (!roomName || !classID) {
        alert("Veuillez remplir tous les champs !");
        return;
    }

    fetch("<?php echo base_url('bigbluebutton/create_room'); ?>", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ room_name: roomName, description: description , class_id: classID })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            console.log("Salle créée avec succès !");
            window.location.reload(); // Recharger la page après création
        } else {
          console.log("Erreur lors de la création de la salle !");
        }
    })
    .catch(error => console.error("Erreur AJAX :", error));
});

// Fonction pour afficher la notification
function showCopyNotification() {
    let toastEl = document.getElementById("copyNotification");
    let toast = new bootstrap.Toast(toastEl);
    toast.show();
}

</script>

