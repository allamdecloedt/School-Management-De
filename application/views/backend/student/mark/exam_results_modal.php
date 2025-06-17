<div class="container-fluid">
    <h4><?php echo htmlspecialchars($exam_details['name']); ?> - <?php echo get_phrase('results'); ?></h4>
    <?php if (empty($submitted_answers)): ?>
        <p class="text-danger"><?php echo get_phrase('no_answers_found'); ?></p>
    <?php else: ?>
        <?php foreach ($submitted_answers as $answer): ?>
            <div class="row mb-2">
                <div class="col-lg-12">
                    <div class="card text-left bg-as-important">
                        <div class="card-body answers-body">
                            <h6 class="card-title mb-3">
                                <img class="answer_status_image" 
                                     src="<?php echo $answer['submitted_answer_status'] == 1 ? base_url('assets/frontend/default/img/green-tick.png') : base_url('assets/frontend/default/img/red-cross.png'); ?>" 
                                     alt="" height="15px">
                                <?php echo get_phrase('question'); ?>: <?php echo htmlspecialchars($answer['question_title']); ?>
                            </h6>
                            <p class="card-text">
                                <strong><?php echo get_phrase('correct_answer'); ?>:</strong> 
                                <?php echo htmlspecialchars($answer['correct_answers'] ?: 'Non défini'); ?>
                            </p>
                            <p class="card-text mt-3">
                                <strong><?php echo get_phrase('student_answer'); ?>:</strong> 
                                <?php echo htmlspecialchars($answer['submitted_answers']); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
.card-body {
    background-color: transparent !important;
    color: #000;
}
.card-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #000;
}
.card-text {
    font-size: 1rem;
    color: #000;
}
.card-text strong {
    color: #000;
}
.answer_status_image {
    margin-right: 10px;
    filter: drop-shadow(0 0 5px rgba(0, 255, 0, 0.5));
}
.answer_status_image[src*="red-cross"] {
    filter: drop-shadow(0 0 5px rgba(255, 0, 0, 0.5));
}
.bg-as-important {
    background: #fff;
    border: 1px solid #D9D9D9;
    border-radius: 12px;
}
</style>