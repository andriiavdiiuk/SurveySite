<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Back Bone</title>
    <link rel="stylesheet" href="/css/styles.css">
    <link href="https://fonts.cdnfonts.com/css/crimson-foam" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/verdana" rel="stylesheet">
</head>

<body>
<div class="container">
    <div class="left-side">
        <h1>Back Bone</h1>
        <div class="question-section">
            <?php
            $is_exist = false;

            require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/mappers/SurveyMapper.php';
            require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/mappers/CategoryMapper.php';
            $surveyMapper = new SurveyMapper();
            $surveys = $surveyMapper->getAll();



            $categoryMapper = new CategoryMapper();
            $category = null;

            if (isset($_GET['category']) && is_numeric($_GET['category'])) {

                $category = $categoryMapper->get(intval($_GET['category']));
            }

            ?>

            <h2>Are you?</h2>
            <h3>
                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat.
            </h3>
            <?php if ($category == null) : ?>
            <?php if ($surveys != null) : ?>
            <div class="btn-group">
                <?php foreach ($surveys as $survey) : ?>
                    <?php if (!$survey->isAccessOnlyByUrl()) : ?>
                        <a href="quiz.php?quiz=<?php echo $survey->getSurveyId() ?>">
                            <button><?php echo $survey->getTitle() ?></button>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <?php else:
            $surveys = $surveyMapper->getByCategory($category->getCategoryId());
            ?>
            <div class="btn-group">
                <?php foreach ($surveys as $survey) : ?>
                    <?php if (!$survey->isAccessOnlyByUrl()) : ?>
                        <a href="quiz.php?quiz=<?php echo $survey->getSurveyId() ?>">
                            <button><?php echo $survey->getTitle() ?></button>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="right-side">
        <img src="/assets/MainPhoto.png" alt="">
    </div>
</div>
<script src="/js/script.js"></script>
</body>

</html>