<link rel="stylesheet" href="<?php echo base_url(); ?>assets/backend/css/manageQuizQuestions.css">
<?php
// $param1 is Exam id
$exam_details = $this->lms_model->get_exams('exam', $param1)->row_array();
$questions = $this->lms_model->get_exam_questions($param1)->result_array();
?>

<?php if (count($exam_details)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="row" data-plugin="dragula" data-containers='["question-list"]'>
                        <div class="col-md-12">
                            <div class="bg-dragula p-2 p-lg-4">
                                <h5 class="mt-0">
                                    <?php echo get_phrase('questions_of').': '.$exam_details['name']; ?>
                                    <div class="d-inline-flex quiz-action-buttons ms-2">
                                        <button type="button" class="btn btn-outline-primary btn-rounded btn-sm" id="question-sort-btn" onclick="sort()" name="button">
                                            <i class="mdi mdi-sort-variant"></i> <?php echo get_phrase('update_sorting'); ?>
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-rounded btn-sm ms-1" onclick="showAjaxModal('<?php echo site_url('modal/popup/academy/exam_question_add/'.$param1) ?>', '<?php echo get_phrase('add_new_question'); ?>')" name="button" data-dismiss="modal">
                                            <i class="mdi mdi-plus"></i> <?php echo get_phrase('add_new_question'); ?>
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-rounded btn-sm ms-1" onclick="largeModal('<?php echo site_url('modal/popup/academy/exam_questions/'.$param1); ?>', '<?php echo get_phrase('manage_exam_questions'); ?>')" name="button" data-dismiss="modal">
                                            <i class="mdi mdi-refresh"></i> <?php echo get_phrase('refresh'); ?>
                                        </button>
                                    </div>
                                </h5>
                                <div id="question-list" class="py-2">
                                    <?php foreach ($questions as $question): ?>
                                        <!-- Item -->
                                        <div class="card mb-0 mt-2 draggable-item on-hover-action" id="<?php echo $question['id']; ?>">
                                            <div class="card-body">
                                                <div class="media">
                                                    <div class="media-body">
                                                        <h5 class="mb-1 mt-0"><?php echo $question['title']; ?>
                                                            <span id="<?php echo 'widgets-of-'.$question['id']; ?>" class="widgets-of-quiz-question">
                                                                <a href="javascript:void(0)" class="alignToTitle float-end ms-1 text-secondary" onclick="deleteExamQuestionAndReloadModal('<?php echo $param1; ?>', '<?php echo $question['id']; ?>')" data-dismiss="modal"><i class="dripicons-cross"></i></a>
                                                                <a href="javascript:void(0)" class="alignToTitle float-end text-secondary" onclick="showAjaxModal('<?php echo site_url('modal/popup/academy/exam_question_edit/'.$question['id'].'/'.$param1); ?>', '<?php echo get_phrase('update_exam_question'); ?>')" data-dismiss="modal"><i class="dripicons-document-edit"></i></a>
                                                            </span>
                                                        </h5>
                                                    </div> <!-- end media-body -->
                                                </div> <!-- end media -->
                                            </div> <!-- end card-body -->
                                        </div> <!-- end col -->
                                        <!-- item -->
                                    <?php endforeach; ?>
                                </div> <!-- end question-list -->
                            </div> <!-- end div.bg-light -->
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div> <!-- end col -->
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4><?php echo get_phrase('no_exam_found'); ?></h4>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Init Dragula -->
<script type="text/javascript">
!function(r) {
    "use strict";
    var a = function() {
        this.$body = r("body");
    };
    a.prototype.init = function() {
        r('[data-plugin="dragula"]').each(function() {
            var a = r(this).data("containers"),
                t = [];
            if (a) {
                for (var n = 0; n < a.length; n++) t.push(r("#" + a[n])[0]);
            } else {
                t = [r(this)[0]];
            }
            var i = r(this).data("handleclass");
            i ? dragula(t, {
                moves: function(a, t, n) {
                    return n.classList.contains(i);
                }
            }) : dragula(t);
        });
    }, r.Dragula = new a, r.Dragula.Constructor = a;
}(window.jQuery),
function(a) {
    "use strict";
    window.jQuery.Dragula.init();
}();
</script>

<script type="text/javascript">
    jQuery(document).ready(function() {
        $('.widgets-of-quiz-question').hide();
        // Show sort button
        $('#question-sort-btn').show();

        $('.on-hover-action').mouseenter(function() {
            var id = this.id;
            $('#widgets-of-' + id).show();
        });
        $('.on-hover-action').mouseleave(function() {
            var id = this.id;
            $('#widgets-of-' + id).hide();
        });
    });

    function deleteExamQuestionAndReloadModal(examID, questionID) {
        var deletionURL = '<?php echo site_url(); ?>addons/courses/exam_questions/' + examID + '/delete/' + questionID;

        confirmModal(deletionURL, function(response) {
            if (!response) {
                error_notify('Erreur : Aucune réponse reçue du serveur.');
                return;
            }

            if (response.status) {
                success_notify('<?php echo get_phrase('exam_question_deleted_successfully'); ?>');
                try {
                    $('#alert-modal').modal('hide');
                } catch (e) {
                    console.error('Erreur lors de la fermeture du modal : ', e);
                }

                setTimeout(function() {
                    try {
                        updateLargeModal('<?php echo site_url('modal/popup/academy/exam_questions/'); ?>' + examID, '<?php echo get_phrase('manage_exam_questions'); ?>');
                    } catch (e) {
                        console.error('Erreur lors du rechargement de la liste : ', e);
                        error_notify('Erreur lors du rechargement de la liste des questions.');
                    }
                }, 500);
            } else {
                console.error('Échec de la suppression : ', response);
                error_notify('<?php echo get_phrase('error_deleting_question'); ?>');
            }
        });
    }

    function sort() {
        var containerArray = ['question-list'];
        var itemArray = [];
        for (var i = 0; i < containerArray.length; i++) {
            $('#' + containerArray[i]).each(function() {
                $(this).find('.draggable-item').each(function() {
                    itemArray.push(this.id);
                });
            });
        }

        var examID = '<?php echo $param1; ?>';
        var itemJSON = JSON.stringify(itemArray);
        $.ajax({
            url: '<?php echo site_url('addons/courses/ajax_sort_question/'); ?>',
            type: 'POST',
            data: { 
                itemJSON: itemJSON, 
                exam_id: examID 
            },
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    success_notify('<?php echo get_phrase('questions_have_been_sorted'); ?>');
                    setTimeout(function() {
                        updateLargeModal('<?php echo site_url('modal/popup/academy/exam_questions/'); ?>' + examID, '<?php echo get_phrase('manage_exam_questions'); ?>');
                    }, 1000);
                } else {
                    console.error('Échec du tri : ', response);
                    error_notify('<?php echo get_phrase('error_sorting_questions'); ?>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Erreur lors du tri : ', error);
                error_notify('<?php echo get_phrase('error_sorting_questions'); ?>');
            }
        });
    }
</script>