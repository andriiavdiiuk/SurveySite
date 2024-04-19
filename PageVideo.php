<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/models/UserHandler.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/mappers/SurveyMapper.php';
if (!UserHandler::isLoggedIn() && isset($_SESSION['user']['completed_quiz'])) {
    header('Location: ' . $_SERVER['HTTPS'] . '/');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/reset.css">
    <link rel="stylesheet" href="/css/PageVideo.css?v=56785">
    <title>PageVideo</title>
</head>
<body>
    <div class="site">
        <main class="main">
            <?php

            $survey_id = intval($_SESSION['user']['completed_quiz']);

            $surveyMapper = new SurveyMapper();
            $survey = $surveyMapper->get($survey_id);
            ?>
            <section class="main__section">
                <div class="section__text_block">
                    <h1 class="section__title">Result</h1>
                    <p class="section__text">
                     <?php echo $survey->getSurveyThanksTitle(); ?>
                    </p>
                    <a href="/" class="section__link"><img src="/assets/iconHome.png" alt="icon"></a>
                </div>
                <div class="section__video_block">
                    <?php if ($survey->getVideoUrl()): ?>
                    <video controls >
                        <source type="video/mp4" src="<?php echo $survey->getVideoUrl() ?>">
                        <source type="video/webm" src="<?php echo $survey->getVideoUrl() ?>">
                        <source type="video/ogg" src="<?php echo $survey->getVideoUrl() ?>">
                    </video>
                    <?php else :?>
                    <img src="/assets/video .png" alt="video">
                    <?php endif ?>
                </div>
                <div class="section__link-2">
                    <a href="/" class="section__link"><img src="/assets/iconHome.png" alt="icon"></a>
                </div>
            </section>
        </main>
    </div>
</body>
</html>