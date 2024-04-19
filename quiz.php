<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/models/UserHandler.php";
require_once  $_SERVER['DOCUMENT_ROOT'] . "/src/core/config.php";
require_once  $_SERVER['DOCUMENT_ROOT'] . "/functions.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/models/UserAnswer.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/models/mappers/UserAnswerMapper.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/mappers/SurveyMapper.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/mappers/QuestionMapper.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/mappers/OfferedAnswerMapper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answers'])) {


    $userAnswerMapper = new UserAnswerMapper();
    foreach ($_POST['answers'] as $question_id => $question) {
        $question_id = intval($question_id);
        if (isset($question['is_plain_text']) && $question['is_plain_text'] == '0') {
            if (isset($question['answer'])) {
                if ($question['answer'] == "")
                {
                    $userAnswerMapper->create(new UserAnswer(-1, $question_id, $_SESSION['user']['user_id'], -1, ''));
                }
                else
                {
                    $option_id = intval($question['answer']);
                    $userAnswerMapper->create(new UserAnswer(-1, $question_id, $_SESSION['user']['user_id'], $option_id, ''));
                }
             }
        } else{
            $userAnswerMapper->create(new UserAnswer(-1, $question_id, $_SESSION['user']['user_id'], -1, $question));
        }
    }
    $_SESSION['user']['completed_quiz'] = intval($_POST['survey_id']);

    send_message();

    header('Location: ' . $_SERVER['HTTPS'] . 'AlertThank.php');

}

if (!isset($_GET['quiz']) && !is_numeric($_GET['quiz'])) {
    header('Location: ' . $_SERVER['HTTPS'] . '/');
}
if (!UserHandler::isLoggedIn()) {
    header('Location: ' . $_SERVER['HTTPS'] . 'contact.php?survey=' . $_GET['quiz']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="/css/quiz.css?v=<?php echo time() ?>">
    <link href="https://fonts.cdnfonts.com/css/crimson-foam" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/verdana" rel="stylesheet">
</head>

<body>
<div>
    <img src="/assets/quiz.png" alt="BGphoto">
    <div id="forms_container" class="container">
        <form id="surveyForm" action="" method="post">
            <div class="home-icon">
                <a href="/"><img src="/assets/icons/home.svg" alt=""></a>
            </div>
            <?php
            $surveyMapper = new SurveyMapper();
            $questionMapper = new QuestionMapper();
            $offeredAnswersMapper = new OfferedAnswerMapper();

            $surveyId = intval($_GET['quiz']);

            echo '<input type="hidden" name="survey_id" value="' . $surveyId . '"/>';
            $questions = $questionMapper->getBySurvey($surveyId);
            if ($questions == NULL) {
                header('Location: ' . $_SERVER['HTTPS'] . '/');
            }
            $is_first = true;
            $questions_count = count($questions);
            $counter = 1;
            foreach ($questions as $question) :
                ?>
                <div id="form<?php echo $counter ?>" class="form" style="<?php if (!$is_first) {
                    echo 'display: none;';
                } ?>">
                    <p id="questionNumber"><?php echo $counter++ . "/" . $questions_count ?></p>
                    <h1><?php echo $question->getQuestionText() ?></h1>
                    <input type="hidden" name="answers[<?php echo $question->getQuestionId(); ?>][is_plain_text]"
                           value="<?php echo $question->isPlainText() ? '1' : '0' ?>"/>
                    <?php if (!$question->isPlainText()): ?>
                        <div class="answer-group">
                            <?php
                            $options = $offeredAnswersMapper->getByQuestion($question->getQuestionId());
                            $option_counter = 1;
                            if ($options != NULL) :
                                foreach ($options as $option) :
                                    ?>
                                    <div class="group group<?php echo $option_counter++ ?>">
                                        <input type="radio"
                                               class="display_none"
                                               name=answers[<?php echo $question->getQuestionId(); ?>][answer]"
                                               value="" checked>
                                        <input type="radio" id="<?php echo $option->getOfferedAnswerId(); ?>"
                                               name=answers[<?php echo $question->getQuestionId(); ?>][answer]"
                                               value="<?php echo $option->getOfferedAnswerId(); ?>">
                                        <label for="<?php echo $option->getOfferedAnswerId(); ?>"><?php echo $option->getText(); ?></label><br>
                                    </div>
                                <?php endforeach ?>
                            <?php endif ?>
                        </div>
                    <?php else: ?>
                        <input type="text" name="answers[<?php echo $question->getQuestionId(); ?>]"
                               placeholder="Enter">
                    <?php endif; ?>
                </div>
                <?php $is_first = false;endforeach; ?>
            <div class="button-group">
                <button type="button" class="btn return">Return</button>
                <button type="button" class="save hidden">Save</button>
                <button id="next_button" type="button" class="btn next">Next</button>
                <button id="save_button" type="submit" class="save  btn-save display_none">Save</button>
            </div>
        </form>
    </div>
</div>
<script src="/js/jQuery.js"></script>
<script src="/js/quiz.js?v=<?php echo time() ?>"></script>
<script src="/js/script.js"></script>
</body>

</html>