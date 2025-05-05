<!-- En-tête et styles -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<div class="row">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title d-inline-block">
          <i class="mdi mdi-account-circle title_icon"></i> <?php echo get_phrase('Recording'); ?>
        </h4>
      </div>
    </div>
  </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row align-items-end g-3">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="meeting_name" class="form-label fw-semibold text-muted"><?php echo get_phrase('meeting_name'); ?></label>
                        <input type="text" class="form-control" id="meeting_name" name="meeting_name" placeholder="Ex: Réunion pédagogique" value="<?= htmlspecialchars($filters['meeting_name'] ?? '') ?>">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="dateRange" class="form-label fw-semibold text-muted"><?php echo get_phrase('Date range'); ?></label>
                        <input type="text" class="form-control" id="dateRange" name="date_range" placeholder="Choisir une plage de dates" value="<?= htmlspecialchars($filters['date_range'] ?? '') ?>">
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 d-flex gap-2">
                        <button type="button" id="applyFilters" class="btn btn-primary w-100"><?php echo get_phrase('apply'); ?></button>
                        <button type="button" id="clearFilters" class="btn btn-outline-secondary w-100"><?php echo get_phrase('clear'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h4 class="fw-bold mb-3">📋 Historique </h4>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th><?php echo get_phrase('name'); ?></th>
                    <th><?php echo get_phrase('room'); ?></th>
                    <th><?php echo get_phrase('class'); ?></th>
                    <th><?php echo get_phrase('section'); ?></th>
                    <th><?php echo get_phrase('creation_date'); ?></th>
                    <th><?php echo get_phrase('duration'); ?></th>
                    <th><?php echo get_phrase('recording'); ?></th>
                    <th><?php echo get_phrase('action'); ?></th>
                </tr>
            </thead>
            <tbody id="recordingTable">
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?= htmlspecialchars($appointment['title']) ?></td>
                        <td><?= htmlspecialchars($appointment['name']) ?></td>
                        <td><?= 
                        
                       
                        $classe_name = $this->db->get_where('classes', array('id' => $appointment['classe_id']))->row('name');
                        htmlspecialchars($classe_name) 
                        ?></td>
                        <td>
                            <?php 
                                if (!empty($appointment['section'])) {
                                    $section_ids = explode(',', $appointment['section']);
                                    $section_names = [];
                                    foreach ($section_ids as $id) {
                                        $name = $this->db->get_where('sections', array('id' => $id))->row('name');
                                        if ($name) $section_names[] = $name;
                                    }
                                    echo implode(', ', $section_names);
                                } else {
                                    echo '—';
                                }
                            ?>
                        </td>
                        <td><?= date('d-m-Y H:i', strtotime($appointment['start'])) ?></td>
                        <td>
                            <?php
                                echo !empty($appointment['recordings']) && isset($appointment['recordings'][0]['duration']) 
                                    ? $appointment['recordings'][0]['duration'] 
                                    : '—';
                            ?>
                        </td>
                        <td>
                            <?php
                                // if (!empty($appointment['recordings'])) {
                                //     $recording = $appointment['recordings'][0];
                                //     $playbackUrl = $recording['playback_url'] ?? null;
                                //     $endTime = !empty($recording['endTime']) ? (int)$recording['endTime'] / 1000 : null; // Convertir ms en s

                                //     // Vérification de l'expiration (7 jours après endTime)
                                //     $isExpired = $endTime ? (time() > ($endTime + (7 * 24 * 60 * 60))) : false;

                                //     if ($isExpired) {
                                //         echo '<span class="badge bg-warning text-dark"> Expired</span>';
                                //     } elseif ($playbackUrl) {
                                //         echo '<a href="' . htmlspecialchars($playbackUrl, ENT_QUOTES, 'UTF-8') . '" target="_blank" class="btn btn-sm btn-success">VIDEO</a>';
                                //     } else {
                                //         echo '<span class="badge bg-danger"> NOT RECORDED</span>';
                                //     }
                                // } else {
                                //     echo '<span class="badge bg-danger"> NOT RECORDED</span>';
                                // }


                                if (!empty($appointment['recordings'])) {
                                    $rec = $appointment['recordings'][0];
                                    $endTime = !empty($rec['endTime']) ? (int)$rec['endTime'] / 1000 : null;
                                    $isExpired = $endTime ? (time() > ($endTime + (7 * 24 * 60 * 60))) : false;
                   
                                    if ($isExpired) {
                                        echo '<span class="badge bg-warning text-dark"> Expired</span>';
                                    } elseif (!empty($rec['playback_url'])) {
                                        echo '<a href="' . htmlspecialchars($rec['playback_url']) . '" target="_blank" class="btn btn-sm btn-success">VIDEO</a>';
                                    } else {
                                        echo '<span class="badge bg-danger">NOT RECORDED</span>';
                                    }
                                } else {
                                    echo '<span class="badge bg-danger">NOT RECORDED</span>';
                                }
                            ?>
                        </td>
                        <td>
                                <?php if (!empty($appointment['recordings']) && $isExpired != 1): ?>
                                    <?php $rec = $appointment['recordings'][0]; ?>
                                   
                                    <a href="<?= $rec['download_url'] ?>" class="btn btn-sm btn-success"> Download</a>
                                    
                                <?php endif ?>
                               
                     

                                <a href="<?= site_url('teacher/delete_appointment_and_recording/' . $appointment['id']) ?>"
                                onclick="return confirm('❗ Cette action supprimera le rendez-vous et l’enregistrement associé. Continuer ?')"
                                class="btn btn-sm btn-danger">
                                🗑️ Supprimer
                                </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    flatpickr("#dateRange", {
        mode: "range",
        dateFormat: "Y-m-d",
        locale: "fr",
        altInput: true,
        altFormat: "d F Y",
        showMonths: 2,
        allowInput: true
    });

      // Fonction pour charger les données via AJAX
      function loadRecordings() {
        const meetingName = document.getElementById('meeting_name').value.trim();
        const dateRange = document.getElementById('dateRange').value.trim();

        $.ajax({
            url: "<?php echo site_url('teacher/filter_recordings'); ?>",
            type: "POST",
            data: {
                meeting_name: meetingName,
                date_range: dateRange,
                <?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash(); ?>"
            },
            success: function(response) {
                $('#recordingTable').html(response);
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue lors du chargement des données.'
                });
            }
        });
    }

    // Gestionnaire pour appliquer les filtres
    document.getElementById('applyFilters').addEventListener('click', function () {
        loadRecordings();
    });

    // Gestionnaire pour effacer les filtres
    document.getElementById('clearFilters').addEventListener('click', function () {
        document.getElementById('meeting_name').value = '';
        document.getElementById('dateRange').value = '';
        loadRecordings();
    });

    // Charger les données initiales
    // loadRecordings();
</script>