<?php
$exam_details = $exam_details;
$questions = $questions;
?>

<!-- En-têtes anti-cache dans le HTML -->
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

<div class="container-fluid exam_container">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg exam_header_navbar">
        <div class="container-fluid">
            <span class="navbar-brand">
                <img src="<?php echo $this->settings_model->get_logo_light(); ?>" alt="" height="40">
                <span class="ms-2"><?php echo get_phrase('online_exam'); ?></span>
            </span>
        </div>
    </nav>
    <div class="container-fluid" style="margin-top: 10vh;">
        <div class="row justify-content-center" id="exam-container">
            <!-- Exam content starts -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4><strong><?php echo get_phrase('exam'); ?>:</strong> <?php echo $exam_details['name']; ?></h4>
                        <p><strong><?php echo get_phrase('class'); ?>:</strong> <?php echo $exam_details['class_name']; ?></p>
                        <p><strong><?php echo get_phrase('section'); ?>:</strong> <?php echo $exam_details['section_name']; ?></p>
                        <p><strong><?php echo get_phrase('school'); ?>:</strong> <?php echo $exam_details['school_name']; ?></p>
                        <p><strong><?php echo get_phrase('start_time'); ?>:</strong> <?php echo date('D, d-M-Y H:i', $exam_details['starting_date']); ?></p>
                        <p><strong><?php echo get_phrase('total_questions'); ?>:</strong> <?php echo count($questions); ?></p>
                        <!-- Contenu de l'examen -->
                        <div id="exam_content">
                            <?php if (empty($questions)): ?>
                                <p class="error-text"><?php echo get_phrase('no_questions_found'); ?></p>
                            <?php else: ?>
                                <!-- Bouton Start Exam -->
                                <div id="start-exam-container">
                                    <button type="button" id="start-exam-btn" class="btn start-exam-btn mt-2 text-white">
                                        <i class="fas fa-play me-2"></i><?php echo get_phrase('start_exam'); ?>
                                    </button>
                                </div>

                                <!-- Formulaire des questions (caché par défaut) -->
                                <div id="questions-container" style="display: none;">
                                    <form id="exam_form" action="<?php echo site_url('student/submit_exam'); ?>" method="post">
                                        <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <?php foreach ($questions as $index => $question): ?>
                                            <div class="question <?php echo $index == 0 ? '' : 'hidden'; ?>" id="question-number-<?php echo $index + 1; ?>">
                                                <div class="timer-container text-end mb-3">
                                                    <div id="timer<?php echo $index + 1; ?>" class="timer">Temps restant : <span>00:15</span></div>
                                                    <div class="timer-bar" id="timer-bar-<?php echo $index + 1; ?>"></div>
                                                </div>
                                                <h5><?php echo ($index + 1) . '. ' . htmlspecialchars($question['title']); ?></h5>
                                                <?php $options = json_decode($question['options']); ?>
                                                <?php foreach ($options as $option): ?>
                                                    <div class="form-check form-check-option mb-2" onclick="selectOption(this, '<?php echo $question['id']; ?>', '<?php echo htmlspecialchars($option); ?>')">
                                                        <input class="form-check-input" type="radio" name="question_<?php echo $question['id']; ?>" value="<?php echo htmlspecialchars($option); ?>" required style="display: none;">
                                                        <label class="form-check-label"><?php echo htmlspecialchars($option); ?></label>
                                                    </div>
                                                <?php endforeach; ?>
                                                <button type="button" id="next-btn-<?php echo $question['id']; ?>" class="btn next-btn mt-2 text-white" disabled
                                                        <?php if ($index + 1 == count($questions)): ?>
                                                            onclick="submitExam()"
                                                        <?php else: ?>
                                                            onclick="showNextQuestion('<?php echo $index + 2; ?>')"
                                                        <?php endif; ?>
                                                        data-question-id="<?php echo $question['id']; ?>">
                                                    <?php echo $index + 1 == count($questions) ? '<i class="fas fa-check me-2"></i>' . get_phrase('submit_exam') : '<i class="fas fa-arrow-right me-2"></i>' . get_phrase('next'); ?>
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    </form>
                                    <div id="exam-result" class="text-left mt-4"></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Exam content ends -->
        </div>
    </div>
</div>

<style>
.exam_container {
    padding: 20px;
    color: #e0e0e0;
    min-height: 100vh;
    font-family: 'Orbitron', sans-serif;
}
.exam_header_navbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    background: linear-gradient(90deg, #1e1e1e, #2a2a2a);
    padding: 12px 0;
    margin-bottom: 20px;
}
.navbar-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.75rem;
    font-weight: 700;
    color: #ffffff !important;
}
.card {
    border: 1px solid #D9D9D9;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
.card-body {
    background-color: transparent !important;
}
.card-body h4 {
    font-size: 2rem;
    font-weight: 700;
    color: #000;
    margin-bottom: 25px;
}
.card-body h5 {
    font-size: 1rem;
    font-weight: 700;
    color: #000;
}
.card-body p {
    font-size: 1.1rem;
    margin-bottom: 12px;
    color: #000;
}
.card-body p strong {
    color: #000;
}
.start-exam-btn {
    background: #2196F3;
    border: none;
    padding: 12px 24px;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s ease;
}
.start-exam-btn:hover {
    background: #2487D5;
    transform: scale(1.05);
}
.question {
    margin-bottom: 20px;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid #D9D9D9;
    transition: all 0.3s ease;
    color: #000;
}
.question h5 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #000;
    margin-bottom: 20px;
}
.timer-container {
    position: relative;
}
.timer {
    background: #fff;
    padding: 6px 12px;
    border-radius: 6px;
    border: 1px solid #000;
    color: #000;
    font-weight: 600;
    display: inline-block;
    margin-bottom: 20px;
}
.timer-bar {
    height: 4px;
    background: linear-gradient(90deg, #DA4437, #04A523);
    border-radius: 2px;
    width: 100%;
    position: absolute;
    bottom: -2px;
    left: 0;
    animation: timerProgress 15s linear forwards;
}
@keyframes timerProgress {
    0% { width: 100%; }
    100% { width: 0%; }
}
.form-check-option {
    background: #fff;
    padding: 12px 18px;
    border-radius: 10px;
    border: 1px solid #ADB0B4;
    transition: all 0.3s ease;
    cursor: pointer;
}
.form-check-option:hover {
    background: #D9D9D9;
    border-color: #ADB0B4;
}
.form-check-option.selected {
    background: #C2C5CA;
    border-color: #ADB0B4;
}
.form-check-label {
    color: #000;
    margin-left: 12px;
    font-size: 1rem;
    width: 100%;
    cursor: pointer;
}
.next-btn {
    background: #2196F3;
    border: none;
    padding: 10px 20px;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s ease;
}
.next-btn:hover {
    background: #2487D5;
    transform: scale(1.05);
}
.next-btn:disabled {
    background: #4a4a4a;
    cursor: not-allowed;
    box-shadow: none;
}
.hidden {
    display: none;
}
.error-text {
    color: #ff4444;
    font-style: italic;
}
.confirmation-container {
    text-align: center;
    padding: 30px;
    background: linear-gradient(145deg, #ffffff, #f0f0f0);
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    animation: fadeIn 0.5s ease;
}
.confirmation-icon {
    font-size: 3rem;
    color: #04A523;
    margin-bottom: 20px;
    animation: pulseIcon 1.5s infinite;
}
.confirmation-container h5 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #000;
    margin-bottom: 15px;
}
.confirmation-container p {
    font-size: 1.2rem;
    color: #333;
    margin-bottom: 20px;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes pulseIcon {
    0% { transform: scale(1); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}
</style>

<script>
// Tableau pour stocker les références des timers
let timers = {};
const totalQuestions = <?php echo count($questions); ?>;
const examId = <?php echo json_encode($exam_id); ?>;

// Activer le bouton "Suivant" ou "Soumettre" quand une réponse est sélectionnée
function enableNextButton(questionId) {
    const button = document.getElementById('next-btn-' + questionId);
    button.disabled = false;
}

// Sélectionner une option en cliquant dessus
function selectOption(element, questionId, optionValue) {
    const options = element.parentElement.querySelectorAll('.form-check-option');
    options.forEach(opt => opt.classList.remove('selected'));
    element.classList.add('selected');
    const radioInput = element.querySelector('input[type="radio"]');
    radioInput.checked = true;
    enableNextButton(questionId);
}

// Arrêter tous les timers
function stopAllTimers() {
    Object.keys(timers).forEach(function (key) {
        clearInterval(timers[key]);
    });
    timers = {};
}

// Démarrer ou réinitialiser le timer pour une question spécifique
function startTimer(index) {
    stopAllTimers();
    let timerSpan = document.getElementById('timer' + index).querySelector('span');
    let timerBar = document.getElementById('timer-bar-' + index);
    let timeLeft = 15;
    timerSpan.textContent = '00:' + (timeLeft < 10 ? '0' + timeLeft : timeLeft);
    timerBar.style.width = '100%';

    let timer = setInterval(function () {
        if (timeLeft <= 0) {
            clearInterval(timer);
            delete timers[index];
            let button = document.getElementById('next-btn-' + document.getElementById('question-number-' + index).querySelector('button').dataset.questionId);
            button.disabled = true;
            if (index < totalQuestions) {
                showNextQuestion(index + 1);
            } else {
                submitExam();
            }
        } else {
            timerSpan.textContent = '00:' + (timeLeft < 10 ? '0' + timeLeft : timeLeft);
            timeLeft--;
        }
    }, 1000);
    timers[index] = timer;
}

// Afficher la question suivante et réinitialiser le timer
function showNextQuestion(nextQuestionNumber) {
    document.querySelectorAll('.question').forEach(function (question) {
        question.classList.add('hidden');
    });
    const nextQuestion = document.getElementById('question-number-' + nextQuestionNumber);
    if (nextQuestion) {
        nextQuestion.classList.remove('hidden');
        startTimer(nextQuestionNumber);
    }
}

// Soumettre l'examen
function submitExam() {
    stopAllTimers();
    let form = document.getElementById('exam_form');
    let formData = new FormData(form);
    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur réseau: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            alert(data.error);
            return;
        }
        // Mettre à jour le jeton CSRF
        document.querySelector('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').value = data.csrf_hash;

        // Afficher un message de confirmation temporaire
        let examContent = document.getElementById('exam_content');
        examContent.innerHTML = `
            <div class="confirmation-container">
                <i class="fas fa-check-circle confirmation-icon"></i>
                <h5><?php echo get_phrase('exam_submitted_successfully'); ?></h5>
                <p><?php echo get_phrase('redirecting_to_exam_list'); ?></p>
            </div>
        `;

        // Rediriger automatiquement vers la liste des examens
        setTimeout(() => {
            window.location.href = '<?php echo site_url('student/exam'); ?>';
        }, 1000); // Redirection après 1 seconde
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de la soumission: ' + error.message);
    });
}

// Gérer le clic sur le bouton "Start Exam"
document.getElementById('start-exam-btn').addEventListener('click', function() {
    document.getElementById('start-exam-container').style.display = 'none';
    document.getElementById('questions-container').style.display = 'block';
    startTimer(1);
});
</script>