<?php
$quiz_questions = $this->lms_model->get_quiz_questions($lesson_details['id']);
$lesson_progress = lesson_progress($lesson_details['id']);
?>
<div id="quiz-body">
    <div id="quiz-header">
        <strong><?php echo get_phrase("quiz_title"); ?></strong> : <?php echo $lesson_details['title']; ?><br>
        <strong><?php echo get_phrase("number_of_questions"); ?></strong> :
        <?php echo count($quiz_questions->result_array()); ?><br>
        <?php if (count($quiz_questions->result_array()) > 0): ?>
            <button type="button" name="button" class="btn start-exam-btn mt-2 text-white"
                onclick="getStarted(1); ">
                <?php echo get_phrase("get_started"); ?>
            </button>
            <button type="button" name="button" class="btn start-exam-btn mt-2 text-white" onclick="check_result(); ">
                <?php echo get_phrase("check_result"); ?>
            </button>
        <?php endif; ?>
    </div>

    <form class="" id="quiz_form" action="" method="post">
        <!-- Champ caché pour le jeton CSRF -->
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
        <input type="hidden" name="overwrite_results" value="1" />
        <?php if (count($quiz_questions->result_array()) > 0): ?>
            <?php foreach ($quiz_questions->result_array() as $key => $quiz_question):
                $options = json_decode($quiz_question['options']);
                ?>
                <input type="hidden" name="lesson_id" value="<?php echo $lesson_details['id']; ?>">
                <div class="hidden" id="question-number-<?php echo $key + 1; ?>">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <!-- Bloc de la question -->
                            <div class="card-body question-body">
                                <h6 class="card-title"><?php echo get_phrase("question") . ' ' . ($key + 1); ?> :
                                    <strong><?php echo $quiz_question['title']; ?></strong>
                                </h6>
                            </div>
                            <!-- Chronomètre -->
                            <div class="timer-container text-center mb-2">
                                Temps restant : <span id="timer<?php echo $key + 1; ?>">00:15</span>
                                <div class="timer-bar" id="timer-bar-<?php echo $key + 1; ?>"></div>
                            </div>
                            <!-- Bloc des options -->
                            <div class="card text-left quiz-card">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item quiz-options-header">
                                        <h5 class="text-capitalize"><?php echo get_phrase("Choose_your_answer"); ?></h5>
                                    </li>
                                    <?php foreach ($options as $key2 => $option): ?>
                                        <li class="list-group-item quiz-options">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="<?php echo $quiz_question['id']; ?>[]" value="<?php echo $key2 + 1; ?>"
                                                    id="quiz-id-<?php echo $quiz_question['id']; ?>-option-id-<?php echo $key2 + 1; ?>"
                                                    onclick="enableNextButton('<?php echo $quiz_question['id']; ?>')">
                                                <label class="form-check-label"
                                                    for="quiz-id-<?php echo $quiz_question['id']; ?>-option-id-<?php echo $key2 + 1; ?>">
                                                    <?php echo $option; ?>
                                                </label>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <!-- Bouton Suivant/Soumettre -->
                            <button type="button" name="button" class="btn start-exam-btn mt-2 text-white"
                                id="next-btn-<?php echo $quiz_question['id']; ?>" 
                                <?php if (count($quiz_questions->result_array()) == $key + 1): ?>onclick="submitQuiz()" 
                                <?php else: ?>onclick="showNextQuestion('<?php echo $key + 2; ?>');" <?php endif; ?>
                                disabled
                                data-question-id="<?php echo $quiz_question['id']; ?>">
                                <?php echo count($quiz_questions->result_array()) == $key + 1 ? get_phrase("check_result") : get_phrase("submit_and_next"); ?>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </form>
</div>
<div id="quiz-result" class="text-left">
</div>

<style>
.course_container {
    background-color: #ffffff !important;
}
.card {
    background-color: #fff !important;
}
.quiz-body {
    padding: 20px;
    color: #000;
    min-height: 100vh;
    font-family: 'Orbitron', sans-serif;
}
#quiz-header {
    color: #000 !important;
    font-size: 1.0rem;
    border: 1px solid #D9D9D9;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    padding: 12px 0;
    margin-bottom: 20px;
    text-align: center;
}
#quiz-header strong {
    color: #000;
    font-size: 1.1rem;
    font-weight: 700;
}
.quiz-card {
    background-color: transparent !important;
}

.card-body {
    color: #000 !important;
    font-size: 1.0rem;
    border: 1px solid #D9D9D9 !important;
    border-radius: 15px !important;
    background-color: #fff !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
}
.quiz-button:disabled {
    background: #4a4a4a;
    cursor: not-allowed;
    box-shadow: none;
}
.timer-container {
    position: relative;
    width: 100%; /* Étend à la largeur du conteneur parent */
    font-size: 0.9rem;
    padding: 4px 10px;
    background: #fff;
    color: #000;
    font-weight: 600;
    margin-top: 50px;
    text-align: center;
}
.timer-bar {
    height: 3px;
    background: linear-gradient(90deg, #DA4437, #04A523);
    border-radius: 2px;
    width: 100%; /* Commence à 100% de la largeur du conteneur parent */
    position: absolute;
    bottom: -2px;
    left: 0;
    animation: timerProgress 15s linear forwards;
}
@keyframes timerProgress {
    0% { width: 100%; }
    100% { width: 0%; }
}
.list-group-item.quiz-options {
    background: #fff !important;
    color: #000 !important;
    padding: 10px 15px !important;
    margin-bottom: 8px !important;
    border-radius: 10px !important;
    border: 1px solid #e0e0e0 !important;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05) !important;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
}
.list-group-item.quiz-options:hover {
    background: #f5f5f5 !important;
    border-color: #2196F3 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1) !important;
}
.list-group-item.quiz-options.selected {
    background: #e3f2fd !important;
    border-color: #2196F3 !important;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1) !important;
}
.list-group-item.quiz-options-header {
    background: #fff !important;
    color: #000 !important;
    padding: 10px 15px !important;
    margin-bottom: 8px !important;
    border-radius: 10px !important;
    border: 1px solid #e0e0e0 !important;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05) !important;
    /* Pas d'effet de survol */
    cursor: default;
}
.form-check-label {
    color: #000;
    margin-left: 10px;
    font-size: 0.95rem;
    width: 100%;
    cursor: pointer;
    line-height: 1.4;
}
.form-check-input {
    margin-top: 0.2rem;
}
.hidden {
    display: none;
}
#quiz-result {
    margin-top: 20px;
}
.start-exam-btn {
    background: #2196F3;
    border: none;
    padding: 6px 6px;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
}
.start-exam-btn:hover {
    background: #2487D5;
    transform: scale(1.05);
}
.confirmation-container {
    text-align: center;
    padding: 25px;
    background: linear-gradient(145deg, #ffffff, #f0f0f0);
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    animation: fadeIn 0.5s ease;
}
.confirmation-icon {
    font-size: 2.5rem;
    color: #04A523;
    margin-bottom: 15px;
    animation: pulseIcon 1.5s infinite;
}
.confirmation-container h5 {
    font-size: 1.6rem;
    font-weight: 700;
    color: #000;
    margin-bottom: 12px;
}
.confirmation-container p {
    font-size: 1.1rem;
    color: #333;
    margin-bottom: 15px;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes pulseIcon {
    0% { transform: scale(1); }
    50% { transform: scale(1.15); }
    100% { transform: scale(1); }
}
@media (max-width: 768px) {
    .list-group-item.quiz-options,
    .list-group-item.quiz-options-header {
        padding: 8px 12px !important;
        margin-bottom: 6px !important;
    }
    .form-check-label {
        font-size: 0.85rem;
        margin-left: 8px;
    }

    .timer-container {
        font-size: 0.8rem;
        padding: 3px 6px;
    }
    .timer-bar {
        height: 2px;
    }
    .start-exam-btn {
        font-size: 0.9rem;
        padding: 5px 5px;
    }
}
</style>

<script>
// Tableau pour stocker les références des timers
let timers = {};
const totalQuestions = <?php echo count($quiz_questions->result_array()); ?>;

// Activer le bouton "Suivant" ou "Soumettre" quand une réponse est sélectionnée
function enableNextButton(questionId) {
    const button = document.getElementById('next-btn-' + questionId);
    button.disabled = false;
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
    let timerSpan = document.getElementById('timer' + index);
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
                submitQuiz();
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
    document.querySelectorAll('#quiz-body > form > div').forEach(function (question) {
        question.classList.add('hidden');
    });
    const nextQuestion = document.getElementById('question-number-' + nextQuestionNumber);
    if (nextQuestion) {
        nextQuestion.classList.remove('hidden');
        startTimer(nextQuestionNumber);
    }
}

// Soumettre le quiz
function submitQuiz() {
    stopAllTimers();
    let form = document.getElementById('quiz_form');
    let submitButton = form.querySelector('button:not([disabled])'); // Find the active submit button
    if (submitButton) {
        submitButton.disabled = true; // Disable to prevent multiple submissions
    }
    // Submit the form synchronously
    form.submit();
}

// Gérer le clic sur le bouton "Get Started"
function getStarted(questionNumber) {
    document.getElementById('quiz-header').style.display = 'none';
    document.getElementById('question-number-' + questionNumber).classList.remove('hidden');
    startTimer(questionNumber);
}

// Gérer le clic sur le bouton "Check Result"
function check_result() {
    fetch('/quiz/results?lesson_id=<?php echo $lesson_details['id']; ?>', {
        method: 'GET',
        headers: {
            'X-CSRF-Token': '<?php echo $this->security->get_csrf_hash(); ?>'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur réseau: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        let quizResult = document.getElementById('quiz-result');
        if (data.error) {
            quizResult.innerHTML = `<p>${data.error}</p>`;
            return;
        }
        // Afficher les résultats
        quizResult.innerHTML = `
            <div class="confirmation-container">
                <h5><?php echo get_phrase('your_quiz_results'); ?></h5>
                <p>Score: ${data.score}%</p>
                <p>Correct Answers: ${data.correct_answers} / ${data.total_questions}</p>
            </div>
        `;
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de la récupération des résultats: ' + error.message);
    });
}
</script>