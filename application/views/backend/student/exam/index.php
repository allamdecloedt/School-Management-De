<!--title-->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
                <i class="mdi mdi-grease-pencil title_icon"></i> <?php echo get_phrase('Exam'); ?>
            </h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body exam_content">
                <!-- Ne pas inclure list.php directement -->
                <!-- Le contenu sera chargé via AJAX -->
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Appeler showAllExams au chargement de la page pour charger le contenu
    showAllExams();
});

var showAllExams = function () {
    var url = '<?php echo route('exam/list'); ?>';

    $.ajax({
        type: 'GET',
        url: url,
        success: function(response) {
            $('.exam_content').html(response);
            // Pas besoin d'appeler initDataTable ici, car list.php gère l'initialisation
        }
    });
}
</script>