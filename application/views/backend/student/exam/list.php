<?php
$school_id = school_id();
$session = active_session();

// Récupérer les écoles associées à l'étudiant connecté
$user_id = $this->session->userdata('user_id');
$this->db->select('schools.*');
$this->db->from('schools');
$this->db->join('students', 'students.school_id = schools.id');
$this->db->where('students.user_id', $user_id);
$schools = $this->db->get()->result_array();

// Ne pas charger les examens au démarrage
$exams = []; // Tableau vide pour éviter le chargement initial
$exam_calendar = [];
$exam_calendar_json = json_encode($exam_calendar);
?>

<!-- Listes déroulantes pour filtrer -->
<div class="row justify-content-center mb-3">
    <div class="col-md-10">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label for="school_id"><?php echo get_phrase('school'); ?></label>
                        <select class="form-control" id="school_id" name="school_id">
                            <option value=""><?php echo get_phrase('select_school'); ?></option>
                            <?php foreach ($schools as $school): ?>
                                <option value="<?php echo $school['id']; ?>"><?php echo $school['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="class_id"><?php echo get_phrase('class'); ?></label>
                        <select class="form-control" id="class_id" name="class_id" disabled>
                            <option value=""><?php echo get_phrase('select_class'); ?></option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="section_id"><?php echo get_phrase('section'); ?></label>
                        <select class="form-control" id="section_id" name="section_id" disabled>
                            <option value=""><?php echo get_phrase('select_section'); ?></option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date_filter"><?php echo get_phrase('exam_date'); ?></label>
                        <input type="text" class="form-control" id="date_filter" name="date_filter" placeholder="<?php echo get_phrase('select_date_or_range'); ?>" disabled>
                    </div>
                    <div class="d-flex justify-content-center align-items-end mt-3">
    <button class="btn btn-primary w-25" id="filter_exams"><?php echo get_phrase('search'); ?></button>
</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contenu principal : tableau en haut, calendrier en bas -->
<div class="main-content">
    <!-- Tableau centré en haut -->
    <div class="table-container">
        <div class="card">
            <div class="card-body">
                <!-- Hidden fields for JavaScript -->
                <input type="hidden" id="base_url" value="<?php echo site_url(); ?>">
                <input type="hidden" id="csrf_name" value="<?php echo $this->security->get_csrf_token_name(); ?>">
                <input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
                    <thead>
                        <tr style="background-color: #313a46; color: #ababab;">
                            <th><?php echo get_phrase('exam_name'); ?></th>
                            <th><?php echo get_phrase('date'); ?></th>
                            <th><?php echo get_phrase('class'); ?></th>
                            <th><?php echo get_phrase('section'); ?></th>
                            <th><?php echo get_phrase('link'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="exam_table_body">
                        <!-- La table est vide au démarrage -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Calendrier centré en bas -->
    <div class="calendar-container">
        <div class="card">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour afficher les résultats -->
<div class="modal fade" id="resultsModalMicron" tabindex="-1" aria-labelledby="resultsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resultsModalLabel"><?php echo get_phrase('exam_results'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="resultsModalContent">
                <!-- Le contenu sera chargé dynamiquement via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo get_phrase('close'); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialiser le calendrier vide
    var examCalendarData = <?php echo $exam_calendar_json; ?>;
    
    $('#calendar').fullCalendar({
        disableDragging: true,
        events: examCalendarData,
        displayEventTime: false,
        selectable: false,
        eventClick: function() { return false; }
    });

    // Initialiser DataTables avec une table vide
    function initDataTable() {
        $('#basic-datatable').DataTable({
            searching: false,
            paging: true,
            lengthChange: true,
            responsive: true,
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "<?php echo get_phrase('all'); ?>"]],
            dom: 'lfrtip',
            info: true,
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>"
                },
                lengthMenu: '<?php echo get_phrase('show'); ?> _MENU_ <?php echo get_phrase('entries'); ?>',
                info: '<?php echo get_phrase('showing'); ?> _START_ <?php echo get_phrase('to'); ?> _END_ <?php echo get_phrase('of'); ?> _TOTAL_ <?php echo get_phrase('entries'); ?>',
                infoEmpty: '<?php echo get_phrase('no_entries_to_show'); ?>',
                emptyTable: '<?php echo get_phrase('no_data_available_in_table'); ?>'
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                updateCountdowns(); // Reapply countdowns after table redraw
            }
        });
        $('#basic-datatable').attr('data-datatable-initialized', 'true');

        // Reapply countdowns when a responsive child row is shown
        $('#basic-datatable').on('responsive-display', function(e, datatable, row, showHide, update) {
            if (showHide) {
                var $childRow = row.child();
                if ($childRow) {
                    var $countdownElements = $childRow.find('.exam-countdown');
                    if ($countdownElements.length > 0) {
                        console.log('Found countdown elements in child row:', $countdownElements.length);
                        $countdownElements.removeClass('countdown-processed');
                        setTimeout(function() {
                            updateCountdowns();
                        }, 100);
                    } else {
                        console.warn('No .exam-countdown elements found in child row');
                    }
                }
            }
        });
    }

    // Appeler l'initialisation au chargement de la page
    initDataTable();

    // Initialiser Daterangepicker
    $('#date_filter').daterangepicker({
        singleDatePicker: false,
        autoUpdateInput: false,
        locale: {
            format: 'DD-MM-YYYY',
            applyLabel: '<?php echo get_phrase('apply'); ?>',
            cancelLabel: '<?php echo get_phrase('cancel'); ?>',
            fromLabel: '<?php echo get_phrase('from'); ?>',
            toLabel: '<?php echo get_phrase('to'); ?>',
            customRangeLabel: '<?php echo get_phrase('custom'); ?>',
            daysOfWeek: ['<?php echo get_phrase('su'); ?>', '<?php echo get_phrase('mo'); ?>', '<?php echo get_phrase('tu'); ?>', '<?php echo get_phrase('we'); ?>', '<?php echo get_phrase('th'); ?>', '<?php echo get_phrase('fr'); ?>', '<?php echo get_phrase('sa'); ?>'],
            monthNames: ['<?php echo get_phrase('january'); ?>', '<?php echo get_phrase('february'); ?>', '<?php echo get_phrase('march'); ?>', '<?php echo get_phrase('april'); ?>', '<?php echo get_phrase('may'); ?>', '<?php echo get_phrase('june'); ?>', '<?php echo get_phrase('july'); ?>', '<?php echo get_phrase('august'); ?>', '<?php echo get_phrase('september'); ?>', '<?php echo get_phrase('october'); ?>', '<?php echo get_phrase('november'); ?>', '<?php echo get_phrase('december'); ?>'],
            firstDay: 1
        }
    });

    // Gérer l'application de la sélection de date
    $('#date_filter').on('apply.daterangepicker', function(ev, picker) {
        if (picker.startDate.isSame(picker.endDate)) {
            $(this).val(picker.startDate.format('DD-MM-YYYY'));
        } else {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        }
    });

    // Gérer l'annulation de la sélection
    $('#date_filter').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    // Fonction pour activer/désactiver le champ de date
    function toggleDateField() {
        var school_id = $('#school_id').val();
        var class_id = $('#class_id').val();
        var section_id = $('#section_id').val();
        if (school_id && class_id && section_id) {
            $('#date_filter').prop('disabled', false);
        } else {
            $('#date_filter').prop('disabled', true).val('');
        }
    }

    // Appeler la fonction au changement des sélecteurs
    $('#school_id, #class_id, #section_id').on('change', toggleDateField);

    // Fonction pour charger les examens initiaux
    function loadInitialExams(class_id, section_id, date_filter = '') {
        var csrf_token = $('#csrf_hash').val();

        console.log('loadInitialExams appelé avec : ', { class_id, section_id, date_filter, csrf_token });

        if (class_id && section_id) {
            $.ajax({
                url: '<?php echo site_url('student/load_initial_exams'); ?>',
                type: 'POST',
                data: {
                    class_id: class_id,
                    section_id: section_id,
                    date_filter: date_filter,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': csrf_token
                },
                success: function(response) {
                    console.log('Réponse load_initial_exams : ', response);
                    try {
                        var data = JSON.parse(response);
                        $('#csrf_hash').val(data.csrf_hash || $('#csrf_hash').val());
                        if (data.error) {
                            console.warn('Erreur retournée par load_initial_exams : ', data.error);
                            $('#exam_table_body').html('<tr><td colspan="5">' + data.error + '</td></tr>');
                            return;
                        }
                        $('#calendar').fullCalendar('removeEvents');
                        $('#calendar').fullCalendar('addEventSource', data.exam_calendar);

                        // Détruire l'instance DataTables existante
                        if ($('#basic-datatable').attr('data-datatable-initialized') === 'true') {
                            $('#basic-datatable').DataTable().destroy();
                        }

                        // Mettre à jour le contenu du tableau
                        $('#exam_table_body').html(data.table_html);

                        // Réinitialiser DataTables
                        initDataTable();

                        // Initialiser les compteurs
                        updateCountdowns();
                    } catch (e) {
                        console.error('Erreur de parsing JSON dans load_initial_exams : ', e, response);
                        $('#exam_table_body').html('<tr><td colspan="5"><?php echo get_phrase('error_loading_exams'); ?></td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur AJAX load_initial_exams : ', status, error, xhr.responseText);
                    $('#exam_table_body').html('<tr><td colspan="5"><?php echo get_phrase('error_loading_exams'); ?></td></tr>');
                }
            });
        } else {
            console.warn('Données manquantes pour loadInitialExams : ', { class_id, section_id });
            $('#exam_table_body').html('<tr><td colspan="5"><?php echo get_phrase('please_select_class_and_section'); ?></td></tr>');
        }
    }

    // Charger les classes lorsqu'une école est sélectionnée
    $('#school_id').on('change', function() {
        var school_id = $(this).val();
        if (school_id) {
            $.ajax({
                url: '<?php echo site_url('student/get_classes_by_school'); ?>',
                type: 'POST',
                data: {
                    school_id: school_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': $('#csrf_hash').val()
                },
                success: function(response) {
                    try {
                        var data = JSON.parse(response);
                        $('#csrf_hash').val(data.csrf_hash || $('#csrf_hash').val());
                        var classes = data.classes || data;
                        var classSelect = $('#class_id');
                        classSelect.empty().append('<option value=""><?php echo get_phrase('select_class'); ?></option>');
                        if (classes.length > 0) {
                            $.each(classes, function(index, classItem) {
                                classSelect.append('<option value="' + classItem.id + '">' + classItem.name + '</option>');
                            });
                            classSelect.prop('disabled', false);
                        } else {
                            classSelect.append('<option value=""><?php echo get_phrase('no_classes_found'); ?></option>');
                            classSelect.prop('disabled', false);
                        }
                        $('#section_id').empty().append('<option value=""><?php echo get_phrase('select_section'); ?></option>').prop('disabled', true);
                        toggleDateField();
                    } catch (e) {
                        console.error('Erreur de parsing JSON dans get_classes_by_school : ', e);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur AJAX get_classes_by_school : ', status, error);
                }
            });
        } else {
            $('#class_id').empty().append('<option value=""><?php echo get_phrase('select_class'); ?></option>').prop('disabled', true);
            $('#section_id').empty().append('<option value=""><?php echo get_phrase('select_section'); ?></option>').prop('disabled', true);
            toggleDateField();
        }
    });

    // Charger les sections lorsqu'une classe est sélectionnée
    $('#class_id').on('change', function() {
        var class_id = $(this).val();
        if (class_id) {
            $.ajax({
                url: '<?php echo site_url('student/get_sections'); ?>',
                type: 'POST',
                data: {
                    classe_id: class_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': $('#csrf_hash').val()
                },
                success: function(response) {
                    try {
                        var data = JSON.parse(response);
                        $('#csrf_hash').val(data.csrf_hash || $('#csrf_hash').val());
                        var sections = data.sections || data;
                        var sectionSelect = $('#section_id');
                        sectionSelect.empty().append('<option value=""><?php echo get_phrase('select_section'); ?></option>');
                        if (sections.length > 0) {
                            $.each(sections, function(index, section) {
                                sectionSelect.append('<option value="' + section.id + '">' + section.name + '</option>');
                            });
                            sectionSelect.prop('disabled', false);
                        } else {
                            sectionSelect.append('<option value=""><?php echo get_phrase('no_sections_found'); ?></option>');
                            sectionSelect.prop('disabled', false);
                        }
                        toggleDateField();
                    } catch (e) {
                        console.error('Erreur de parsing JSON dans get_sections : ', e);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur AJAX get_sections : ', status, error);
                }
            });
        } else {
            $('#section_id').empty().append('<option value=""><?php echo get_phrase('select_section'); ?></option>').prop('disabled', true);
            toggleDateField();
        }
    });

    // Filtrer les examens lorsqu'on clique sur le bouton Rechercher
    $('#filter_exams').on('click', function() {
        var school_id = $('#school_id').val();
        var class_id = $('#class_id').val();
        var section_id = $('#section_id').val();
        var date_filter = $('#date_filter').val();
        var csrf_token = $('#csrf_hash').val();

        if (school_id && class_id && section_id) {
            $.ajax({
                url: '<?php echo site_url('student/filter_exams'); ?>',
                type: 'POST',
                data: {
                    school_id: school_id,
                    class_id: class_id,
                    section_id: section_id,
                    date_filter: date_filter,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': csrf_token
                },
                success: function(response) {
                    try {
                        var data = JSON.parse(response);
                        $('#csrf_hash').val(data.csrf_hash || $('#csrf_hash').val());
                        $('#calendar').fullCalendar('removeEvents');
                        $('#calendar').fullCalendar('addEventSource', data.exam_calendar);

                        // Détruire l'instance DataTables existante
                        if ($('#basic-datatable').attr('data-datatable-initialized') === 'true') {
                            $('#basic-datatable').DataTable().destroy();
                        }

                        // Mettre à jour le contenu du tableau
                        $('#exam_table_body').html(data.table_html);

                        // Réinitialiser DataTables
                        initDataTable();

                        // Initialiser les compteurs
                        updateCountdowns();
                    } catch (e) {
                        console.error('Erreur de parsing JSON dans filter_exams : ', e);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur AJAX filter_exams : ', status, error);
                }
            });
        } else {
            console.error('Données manquantes pour filter_exams : ', {
                school_id: school_id,
                class_id: class_id,
                section_id: section_id
            });
        }
    });

    // Fonction pour mettre à jour les compteurs
    function updateCountdowns() {
    $('.exam-countdown').each(function() {
        var $element = $(this);
        var examStartTime = parseInt($element.data('exam-start'));
        var examId = $element.data('exam-id');
        var currentTime = Math.floor(Date.now() / 1000);
        var timeDiff = examStartTime - currentTime;

        if (!examStartTime) {
            return;
        }

        if (timeDiff <= 0) {
            $element.html(
                '<div class="d-flex justify-content-center">' +
                '<a href="<?php echo site_url('student/online_exam/'); ?>' + examId + '" target="_blank" class="btn btn-sm btn-primary access-exam-btn"><?php echo get_phrase('access'); ?></a>' +
                '</div>'
            );
            $element.addClass('countdown-processed');
            return;
        }

        var days = Math.floor(timeDiff / (60 * 60 * 24));
        var hours = Math.floor((timeDiff % (60 * 60 * 24)) / (60 * 60));
        var minutes = Math.floor((timeDiff % (60 * 60)) / 60);
        var seconds = timeDiff % 60;

        var countdownText = '';
        if (days > 0) {
            countdownText += days + 'd ';
        }
        countdownText += hours + 'h ' + minutes + 'm ' + seconds + 's';

        $element.html(
            '<div class="text-center">' +
            '<div><?php echo get_phrase('exam_not_yet_available'); ?></div>' +
            '<div class="countdown-text">' + countdownText + '</div>' +
            '</div>'
        );

        $element.addClass('countdown-processed');
    });

    $('.countdown-processed').each(function() {
        if (!$.contains(document, this)) {
            $(this).removeClass('countdown-processed');
        }
    });
}

    // Mettre à jour les compteurs toutes les secondes
    setInterval(updateCountdowns, 1000);

    // Initialiser les compteurs immédiatement
    updateCountdowns();

    // Gestion du clic sur le bouton "View Results"
    $(document).on('click', '.view-results-btn', function() {
        var examId = $(this).data('exam-id');
        var studentId = $(this).data('student-id');

        $.ajax({
            url: '<?php echo site_url('student/get_exam_results_popup/'); ?>' + examId + '/' + studentId,
            type: 'POST',
            headers: {
                'Accept': 'application/json'
            },
            data: {
                '<?php echo $this->security->get_csrf_token_name(); ?>': $('#csrf_hash').val()
            },
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    $('#csrf_hash').val(data.csrf_hash || $('#csrf_hash').val());
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    $('#resultsModalContent').html(data.html);
                    var modal = new bootstrap.Modal(document.getElementById('resultsModalMicron'));
                    modal.show();
                } catch (e) {
                    console.error('Erreur de parsing JSON dans get_exam_results_popup : ', e);
                    alert('Erreur lors du chargement des résultats');
                }
            },
            error: function(xhr, status, error) {
                console.error('Erreur AJAX get_exam_results_popup : ', status, error);
                alert('Une erreur est survenue lors du chargement des résultats');
            }
        });
    });

    // Fonction pour charger les données initiales sans sélectionner les listes déroulantes
    function autoLoadExams() {
        console.log('autoLoadExams appelé');
        $.ajax({
            url: '<?php echo site_url('student/get_classes_by_student'); ?>',
            type: 'POST',
            data: {
                '<?php echo $this->security->get_csrf_token_name(); ?>': $('#csrf_hash').val()
            },
            success: function(response) {
                console.log('Réponse get_classes_by_student : ', response);
                try {
                    var data = JSON.parse(response);
                    $('#csrf_hash').val(data.csrf_hash || $('#csrf_hash').val());
                    var classes = data.classes || data;
                    var classSelect = $('#class_id');
                    classSelect.empty().append('<option value=""><?php echo get_phrase('select_class'); ?></option>');
                    if (classes.length > 0) {
                        $.each(classes, function(index, classItem) {
                            classSelect.append('<option value="' + classItem.id + '">' + classItem.name + '</option>');
                        });
                        classSelect.prop('disabled', false);

                        var class_id = classes[0].id;
                        console.log('Classe par défaut : ', class_id);

                        $.ajax({
                            url: '<?php echo site_url('student/get_sections'); ?>',
                            type: 'POST',
                            data: {
                                classe_id: class_id,
                                '<?php echo $this->security->get_csrf_token_name(); ?>': $('#csrf_hash').val()
                            },
                            success: function(response) {
                                console.log('Réponse get_sections : ', response);
                                try {
                                    var data = JSON.parse(response);
                                    $('#csrf_hash').val(data.csrf_hash || $('#csrf_hash').val());
                                    var sections = data.sections || data;
                                    var sectionSelect = $('#section_id');
                                    sectionSelect.empty().append('<option value=""><?php echo get_phrase('select_section'); ?></option>');
                                    if (sections.length > 0) {
                                        $.each(sections, function(index, section) {
                                            sectionSelect.append('<option value="' + section.id + '">' + section.name + '</option>');
                                        });
                                        sectionSelect.prop('disabled', false);

                                        var section_id = sections[0].id;
                                        console.log('Section par défaut : ', section_id);

                                        loadInitialExams(class_id, section_id);
                                    } else {
                                        sectionSelect.append('<option value=""><?php echo get_phrase('no_sections_found'); ?></option>');
                                        sectionSelect.prop('disabled', false);
                                        console.warn('Aucune section trouvée pour la classe : ', class_id);
                                        $('#exam_table_body').html('<tr><td colspan="5"><?php echo get_phrase('no_sections_found'); ?></td></tr>');
                                    }
                                } catch (e) {
                                    console.error('Erreur de parsing JSON dans get_sections : ', e, response);
                                    $('#exam_table_body').html('<tr><td colspan="5"><?php echo get_phrase('error_loading_sections'); ?></td></tr>');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Erreur AJAX get_sections : ', status, error, xhr.responseText);
                                $('#exam_table_body').html('<tr><td colspan="5"><?php echo get_phrase('error_loading_sections'); ?></td></tr>');
                            }
                        });
                    } else {
                        classSelect.append('<option value=""><?php echo get_phrase('no_classes_found'); ?></option>');
                        classSelect.prop('disabled', false);
                        console.warn('Aucune classe trouvée pour l\'étudiant');
                        $('#exam_table_body').html('<tr><td colspan="5"><?php echo get_phrase('no_classes_found'); ?></td></tr>');
                    }
                } catch (e) {
                    console.error('Erreur de parsing JSON dans get_classes_by_student : ', e, response);
                    $('#exam_table_body').html('<tr><td colspan="5"><?php echo get_phrase('error_loading_classes'); ?></td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Erreur AJAX get_classes_by_student : ', status, error, xhr.responseText);
                $('#exam_table_body').html('<tr><td colspan="5"><?php echo get_phrase('error_loading_classes'); ?></td></tr>');
            }
        });
    }

    // Appeler la fonction pour charger les examens au chargement de la page
    autoLoadExams();
});
</script>

<style>
/* Style pour le conteneur principal */
.main-content {
    padding: 20px;
}

/* Style pour le conteneur du tableau */
.table-container {
    max-width: 1185px; /* Largeur maximale du tableau */
    margin: 0 auto 30px auto; /* Centrer horizontalement et marge en bas */
}

/* Style pour le conteneur du calendrier */
.calendar-container {
    max-width: 1185px; /* Largeur maximale du calendrier */
    margin: 0 auto; /* Centrer horizontalement */
}

/* Style pour le modal */
.modal-content {
    background-color: #fff;
    border-radius: 12px;
}
.modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}
.modal-title {
    color: #000;
}
.modal-body {
    max-height: 60vh;
    overflow-y: auto;
}

/* Style pour le daterangepicker */
#date_filter {
    background-color: #fff;
    border: 1px solid #ced4da;
}

/* Style pour DataTables */
.dataTables_filter {
    display: none !important; /* Cacher le champ de recherche */
}
.dataTables_length {
    display: block !important; /* S'assurer que le sélecteur "Show entries" est visible */
}
.dataTables_info {
    padding-top: 10px; /* Ajouter un peu d'espace pour les informations de pagination */
}

/* Style pour réduire la largeur de la colonne Date */
#basic-datatable th:nth-child(2),
#basic-datatable td:nth-child(2) {
    width: 100px !important;
    min-width: 80px;
    white-space: nowrap;
}

/* Style pour le compteur d'examen */
.exam-countdown {
    text-align: center;
    vertical-align: middle;
}
.exam-countdown .countdown-text {
    display: inline-block;
    background-color: #3A87AD;
    color: white;
    border-radius: 5px;
    padding: 3px;
    font-size: 14px;
}

/* Style pour les lignes enfants en mode responsive */
.dataTables_wrapper .dtr-details .exam-countdown {
    padding: 5px;
    display: block;
}

/* Style pour aligner les boutons et compteurs */
#basic-datatable td {
    vertical-align: middle !important;
}

/* Style spécifique pour la colonne des actions */
#basic-datatable td:last-child {
    text-align: center;
    white-space: nowrap;
}

/* Style pour les boutons */
.access-exam-btn, .view-results-btn {
    margin: 2px;
    padding: 5px 10px;
    font-size: 13px;
}

/* Style pour le compteur dans la table */
.exam-countdown {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 60px;
}

/* Style pour le texte du compteur */
.exam-countdown .countdown-text {
    margin-top: 5px;
}

/* Style pour les lignes enfants en mode responsive */
.dataTables_wrapper .dtr-details .exam-countdown {
    padding: 5px;
    display: block;
    min-height: auto;
}

/* Ajustement pour les boutons en mode responsive */
@media (max-width: 767px) {
    #basic-datatable td:last-child {
        text-align: left;
    }
    
    .exam-countdown {
        text-align: left;
        align-items: flex-start;
    }
}


</style>