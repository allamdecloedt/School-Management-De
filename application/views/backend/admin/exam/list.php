<?php
$school_id = school_id();
$session = active_session();

// Initialize filter variables
$class_id = isset($_POST['class_id']) ? htmlspecialchars($_POST['class_id']) : '';
$section_id = isset($_POST['section_id']) ? htmlspecialchars($_POST['section_id']) : '';
$date_range = isset($_POST['date_range']) && !empty($_POST['date_range']) ? htmlspecialchars($_POST['date_range']) : '';
$date_from = '';
$date_to = '';

if (!empty($date_range)) {
    $dates = explode(' - ', $date_range);
    $date_from = strtotime(trim($dates[0]) . ' 00:00:00');
    $date_to = strtotime(trim($dates[1]) . ' 23:59:59');
}

// Build the query with filters
$this->db->select('exams.*, classes.name as class_name, sections.name as section_name');
$this->db->from('exams');
$this->db->join('classes', 'exams.class_id = classes.id', 'left');
$this->db->join('sections', 'exams.section_id = sections.id', 'left');
$this->db->where('exams.school_id', $school_id);
$this->db->where('exams.session', $session);
if (!empty($class_id)) {
    $this->db->where('exams.class_id', $class_id);
}
if (!empty($section_id)) {
    $this->db->where('exams.section_id', $section_id);
}
if (!empty($date_range)) {
    $this->db->where('exams.starting_date >=', $date_from);
    $this->db->where('exams.starting_date <=', $date_to);
}
$exams = $this->db->get()->result_array();

// Convert exam data to JSON for the calendar
$exam_calendar = [];
foreach ($exams as $exam) {
    $exam_calendar[] = [
        'id' => $exam['id'],
        'title' => $exam['name'],
        'start' => date('Y-m-d H:i:s', $exam['starting_date'])
    ];
}
$exam_calendar_json = json_encode($exam_calendar);
?>

<!-- Filter Form -->
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form id="filterForm">
                    <div class="row">
                        <!-- Class Dropdown -->
                        <div class="col-md-3">
                            <label for="class_id"><?php echo get_phrase('class'); ?></label>
                            <select name="class_id" id="class_id" class="form-control" onchange="getFilterSections(this.value)">
                                <option value=""><?php echo get_phrase('select_class'); ?></option>
                                <?php
                                $classes = $this->db->get_where('classes', ['school_id' => $school_id])->result_array();
                                foreach ($classes as $class) {
                                    $selected = ($class['id'] == $class_id) ? 'selected' : '';
                                    echo "<option value='{$class['id']}' $selected>{$class['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Section Dropdown -->
                        <div class="col-md-3">
                            <label for="section_id"><?php echo get_phrase('section'); ?></label>
                            <select name="section_id" id="section_id" class="form-control">
                                <option value=""><?php echo get_phrase('select_section'); ?></option>
                                <?php
                                if (!empty($class_id)) {
                                    $sections = $this->db->get_where('sections', ['class_id' => $class_id])->result_array();
                                    foreach ($sections as $section) {
                                        $selected = ($section['id'] == $section_id) ? 'selected' : '';
                                        echo "<option value='{$section['id']}' $selected>{$section['name']}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Date Range Picker -->
                        <div class="col-md-3">
                            <label for="date_range"><?php echo get_phrase('Exam date'); ?></label>
                            <input type="text" name="date_range" id="date_range" class="form-control daterange" value="<?php echo $date_range; ?>" placeholder="Select Date Range">
                        </div>
                        <!-- Search Button -->
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary"><?php echo get_phrase('search'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Main Content: Calendar on Left, Table on Right -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div id="exam-table-container">
                    <?php if (count($exams) > 0): ?>
                        <table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
                            <thead>
                                <tr style="background-color: #313a46; color: #ababab;">
                                    <th><?php echo get_phrase('exam_name'); ?></th>
                                    <th><?php echo get_phrase('date'); ?></th>
                                    <th><?php echo get_phrase('class'); ?></th>
                                    <th><?php echo get_phrase('section'); ?></th>
                                    <th><?php echo get_phrase('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($exams as $exam): ?>
                                    <tr id="row_<?php echo $exam['id']; ?>" data-exam-id="<?php echo $exam['id']; ?>">
                                        <td><?php echo $exam['name']; ?></td>
                                        <td><?php echo date('D, d-M-Y H:i', $exam['starting_date']); ?></td>
                                        <td><?php echo !empty($exam['class_name']) ? $exam['class_name'] : get_phrase('no_class'); ?></td>
                                        <td><?php echo !empty($exam['section_name']) ? $exam['section_name'] : get_phrase('no_section'); ?></td>
                                        <td>
                                            <div class="dropdown text-center">
                                                <button type="button" class="btn btn-sm btn-icon btn-rounded btn-outline-secondary dropdown-btn dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0)" class="dropdown-item" onclick="largeModal('<?php echo site_url('modal/popup/academy/exam_questions/'.$exam['id']); ?>', '<?php echo get_phrase('manage_exam_questions'); ?>')"><?php echo get_phrase('manage_exam_questions'); ?></a>
                                                    <a href="javascript:void(0)" class="dropdown-item" onclick="rightModal('<?php echo site_url('modal/popup/exam/edit/'.$exam['id']); ?>', '<?php echo get_phrase('update_exam'); ?>')"><?php echo get_phrase('edit'); ?></a>
                                                    <a href="javascript:void(0)" class="dropdown-item" onclick="confirmModal('<?php echo route('exam/delete/'.$exam['id']); ?>', showAllExams)"><?php echo get_phrase('delete'); ?></a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <?php include APPPATH.'views/backend/empty.php'; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="<?php echo base_url('assets/backend/js/common_scripts.js'); ?>"></script>

<script>
function initDataTable() {
    if ($('#basic-datatable').length && !$.fn.DataTable.isDataTable('#basic-datatable')) {
        $('#basic-datatable').DataTable({
            searching: false,
            dom: 'lfrtip',
            responsive: true,
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            rowId: function(row) {
                return 'row_' + row.id;
            },
            createdRow: function(row, data, dataIndex) {
                $(row).attr('data-exam-id', data.id);
            },
            language: {
                paginate: {
                    previous: '<i class="mdi mdi-chevron-left"></i>',
                    next: '<i class="mdi mdi-chevron-right"></i>'
                },
                lengthMenu: '<?php echo get_phrase('show'); ?> _MENU_ <?php echo get_phrase('entries'); ?>',
                info: '<?php echo get_phrase('showing'); ?> _START_ <?php echo get_phrase('to'); ?> _END_ <?php echo get_phrase('of'); ?> _TOTAL_ <?php echo get_phrase('entries'); ?>'
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            }
        });
        $('#basic-datatable').attr('data-datatable-initialized', 'true');
    }
}

function getFilterSections(class_id, selectedSectionId = '') {
    if (class_id) {
        $.ajax({
            url: '<?php echo site_url('admin/get_sections_by_class'); ?>',
            type: 'POST',
            data: { class_id: class_id },
            success: function(response) {
                var data = JSON.parse(response);
                var sectionSelect = $('#section_id');
                sectionSelect.html('<option value=""><?php echo get_phrase('select_section'); ?></option>');
                
                if (data.sections && data.sections.length > 0) {
                    $.each(data.sections, function(index, section) {
                        var isSelected = (section.id == selectedSectionId) ? 'selected' : '';
                        sectionSelect.append('<option value="' + section.id + '" ' + isSelected + '>' + section.name + '</option>');
                    });
                } else {
                    console.warn('No sections found for filter:', data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Filter Sections AJAX Error:', status, error);
                console.error('Response Text:', xhr.responseText);
                $('#section_id').html('<option value=""><?php echo get_phrase('select_section'); ?></option>');
            }
        });
    } else {
        $('#section_id').html('<option value=""><?php echo get_phrase('select_section'); ?></option>');
    }
}

function updateExamTable(exams) {
    if ($('#basic-datatable').attr('data-datatable-initialized') === 'true') {
        $('#basic-datatable').DataTable().destroy();
        $('#basic-datatable').removeAttr('data-datatable-initialized');
    }

    var tableHtml = '';
    if (exams && Array.isArray(exams) && exams.length > 0) {
        exams = exams.filter(function(exam) {
            return exam && exam.id && exam.name && exam.formatted_date;
        });

        if (exams.length > 0) {
            tableHtml = `
                <table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
                    <thead>
                        <tr style="background-color: #313a46; color: #ababab;">
                            <th><?php echo get_phrase('exam_name'); ?></th>
                            <th><?php echo get_phrase('date'); ?></th>
                            <th><?php echo get_phrase('class'); ?></th>
                            <th><?php echo get_phrase('section'); ?></th>
                            <th><?php echo get_phrase('options'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            $.each(exams, function(index, exam) {
                tableHtml += `
                    <tr id="row_${exam.id}" data-exam-id="${exam.id}">
                        <td>${exam.name || 'Unnamed Exam'}</td>
                        <td>${exam.formatted_date || 'No Date'}</td>
                        <td>${exam.class_name || '<?php echo get_phrase('no_class'); ?>'}</td>
                        <td>${exam.section_name || '<?php echo get_phrase('no_section'); ?>'}</td>
                        <td>
                            <div class="dropdown text-center">
                                <button type="button" class="btn btn-sm btn-icon btn-rounded btn-outline-secondary dropdown-btn dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:void(0)" class="dropdown-item" onclick="largeModal('<?php echo site_url('modal/popup/academy/exam_questions/'); ?>${exam.id}', '<?php echo get_phrase('manage_exam_questions'); ?>')"><?php echo get_phrase('manage_exam_questions'); ?></a>
                                    <a href="javascript:void(0)" class="dropdown-item" onclick="rightModal('<?php echo site_url('modal/popup/exam/edit/'); ?>${exam.id}', '<?php echo get_phrase('update_exam'); ?>')"><?php echo get_phrase('edit'); ?></a>
                                    <a href="javascript:void(0)" class="dropdown-item" onclick="confirmModal('<?php echo route('exam/delete/'); ?>${exam.id}', showAllExams)"><?php echo get_phrase('delete'); ?></a>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
            });
            tableHtml += `
                    </tbody>
                </table>
            `;
        } else {
            tableHtml = `<?php include APPPATH.'views/backend/empty.php'; ?>`;
        }
    } else {
        tableHtml = `<?php include APPPATH.'views/backend/empty.php'; ?>`;
    }

    $('#exam-table-container').html(tableHtml);
    if (exams && exams.length > 0) {
        initDataTable();
    }
}

// Nouvelle fonction pour mettre à jour la table et le calendrier après la création
window.updateExamTableAndCalendar = function(classId = '') {
    var formData = {
        class_id: classId || '',
        section_id: '',
        date_range: ''
    };

    $.ajax({
        url: '<?php echo site_url('admin/filter_exams'); ?>',
        type: 'POST',
        data: formData,
        success: function(response) {
            try {
                var data = JSON.parse(response);

                var calendar = $('#calendar').fullCalendar('getCalendar');
                if (calendar) {
                    $('#calendar').fullCalendar('removeEvents');
                    $('#calendar').fullCalendar('addEventSource', data.calendar);
                    $('#calendar').fullCalendar('rerenderEvents');
                } else {
                    console.warn('Calendar instance not found');
                }

                updateExamTable(data.exams);

                // Ne pas charger les sections si aucun class_id n'est fourni
                if (formData.class_id) {
                    getFilterSections(formData.class_id, formData.section_id);
                } else {
                    $('#section_id').html('<option value=""><?php echo get_phrase('select_section'); ?></option>');
                    // Réinitialiser les filtres pour afficher tous les exams
                    $('#class_id').val('');
                    $('#section_id').val('');
                    $('#date_range').val('');
                }
            } catch (e) {
                console.error('Erreur lors du parsing de la réponse:', e);
                showNotification('error', '<?php echo get_phrase('failed_to_update_exams'); ?>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Update AJAX Error:', status, error);
            console.error('Response Text:', xhr.responseText);
            showNotification('error', '<?php echo get_phrase('failed_to_update_exams'); ?>');
        }
    });
};

$(document).ready(function() {
    $('#date_range').daterangepicker({
        locale: {
            format: 'DD-MM-YYYY',
            separator: ' - ',
            applyLabel: '<?php echo get_phrase('apply'); ?>',
            cancelLabel: '<?php echo get_phrase('cancel'); ?>',
            fromLabel: '<?php echo get_phrase('from'); ?>',
            toLabel: '<?php echo get_phrase('to'); ?>',
            customRangeLabel: '<?php echo get_phrase('custom'); ?>',
            weekLabel: 'W',
            daysOfWeek: [
                '<?php echo get_phrase('su'); ?>',
                '<?php echo get_phrase('mo'); ?>',
                '<?php echo get_phrase('tu'); ?>',
                '<?php echo get_phrase('we'); ?>',
                '<?php echo get_phrase('th'); ?>',
                '<?php echo get_phrase('fr'); ?>',
                '<?php echo get_phrase('sa'); ?>'
            ],
            monthNames: [
                '<?php echo get_phrase('january'); ?>',
                '<?php echo get_phrase('february'); ?>',
                '<?php echo get_phrase('march'); ?>',
                '<?php echo get_phrase('april'); ?>',
                '<?php echo get_phrase('may'); ?>',
                '<?php echo get_phrase('june'); ?>',
                '<?php echo get_phrase('july'); ?>',
                '<?php echo get_phrase('august'); ?>',
                '<?php echo get_phrase('september'); ?>',
                '<?php echo get_phrase('october'); ?>',
                '<?php echo get_phrase('november'); ?>',
                '<?php echo get_phrase('december'); ?>'
            ],
            firstDay: 1
        },
        autoUpdateInput: false
    }).on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
    }).on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    var examCalendarData = <?php echo $exam_calendar_json; ?>;
    $('#calendar').fullCalendar({
        disableDragging: true,
        events: examCalendarData,
        displayEventTime: false,
        selectable: false,
        eventClick: function() { return false; }
    });

    initDataTable();

    $('#filterForm').on('submit', function(e) {
        e.preventDefault();

        var formData = {
            class_id: $('#class_id').val(),
            section_id: $('#section_id').val(),
            date_range: $('#date_range').val()
        };

        $.ajax({
            url: '<?php echo site_url('admin/filter_exams'); ?>',
            type: 'POST',
            data: formData,
            success: function(response) {
                var data = JSON.parse(response);

                $('#calendar').fullCalendar('removeEvents');
                $('#calendar').fullCalendar('addEventSource', data.calendar);
                $('#calendar').fullCalendar('rerenderEvents');

                updateExamTable(data.exams);

                if (formData.class_id) {
                    getFilterSections(formData.class_id, formData.section_id);
                } else {
                    $('#section_id').html('<option value=""><?php echo get_phrase('select_section'); ?></option>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Filter AJAX Error:', status, error);
                console.error('Response Text:', xhr.responseText);
            }
        });
    });

    $('#class_id').on('change', function() {
        var class_id = $(this).val();
        getFilterSections(class_id);
    });

    window.rightModal = function(url, title) {
        $.ajax({
            url: url,
            success: function(response) {
                $('#right-modal .modal-title').html(title);
                $('#right-modal .modal-body').html(response);
                $('#right-modal').modal('show');

                $('#right-modal form').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: $(this).serialize() + '&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>',
                        success: function(response) {
                            handleExamActionResponse(response);
                            $('#right-modal').modal('hide');
                        },
                        error: function(xhr, status, error) {
                            console.error('Modal Form Submission Error:', status, error);
                            showNotification('error', '<?php echo get_phrase('failed_to_submit_form'); ?>');
                        }
                    });
                });
            },
            error: function(xhr, status, error) {
                console.error('Modal Load Error:', status, error);
                showNotification('error', '<?php echo get_phrase('failed_to_load_form'); ?>');
            }
        });
    };
});
</script>
<style>
    .dataTables_filter {
        display: none !important;
    }
    .daterange {
        width: 100%;
    }
</style>