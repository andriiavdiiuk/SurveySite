<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/mappers/CategoryMapper.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/mappers/SurveyMapper.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/mappers/QuestionMapper.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/mappers/OfferedAnswerMapper.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/Survey.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/Question.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/OfferedAnswer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/Category.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/models/UserHandler.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
function uploadVideo()
{
    $target_dir = "uploads/videos/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (isset($_POST["fileToUpload"])) {
        $uploadOk = 1;
    }

    if (file_exists($target_file)) {
        $uploadOk = 0;
    } else {
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
    }
    return $target_file;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/UserHandler.php';
$password_or_email_invalid = false;
$password_updated = false;
if (UserHandler::isAdmin() && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["create_survey"])) {
    $surveyMapper = new SurveyMapper();
    $file_url = uploadVideo();
    $is_only_by_url = false;
    if (isset($_POST['access_only_by_url']) && $_POST['access_only_by_url'] == '1') {
        $is_only_by_url = true;
    }
    $category_id = 1;
    if (!isset($_POST['category']) || $_POST['category'] == '-1')
    {
        $category_id = 1;
    }
    else
    {
        $category_id = intval($_POST['category']);
    }
    $surveyId = $surveyMapper->create(new Survey(-1,$category_id, htmlspecialchars($_POST["surveyTitle"]), htmlspecialchars($_POST["surveyThanks"]), htmlspecialchars($file_url), $is_only_by_url));

    $questionMapper = new QuestionMapper();
    $offeredAnswersMapper = new OfferedAnswerMapper();
    $question = new Question();
    $questionId = -1;
    if (isset($_POST['question'])) {
        foreach ($_POST['question'] as $elem) {
            $question = new Question(-1, $surveyId, htmlspecialchars($elem['title']));

            if (isset($elem['is_multiple_choice'])) {
                if ($elem['is_multiple_choice'] == '1') {
                    $question->setIsPlainText(false);
                } else if ($elem['is_multiple_choice'] == '0') {
                    $question->setIsPlainText(true);
                }
                $questionId = $questionMapper->create($question);
                if (isset($elem['option'])) {
                    foreach ($elem['option'] as $option) {
                        $offeredAnswersMapper->create(new OfferedAnswer(-1, $questionId, htmlspecialchars($option)));
                    }
                }
            }
        }
    }
}

if (UserHandler::isAdmin() && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["edit_survey"])) {
    $surveyMapper = new SurveyMapper();
    $surveyId = intval($_POST['survey_id']);
    $file_url = uploadVideo();
    $is_only_by_url = false;
    if (isset($_POST['access_only_by_url']) && $_POST['access_only_by_url'] == '1') {
        $is_only_by_url = true;
    }
    $category_id = 1;
    if (!isset($_POST['category']) || $_POST['category'] == '-1')
    {
        $category_id = 1;
    }
    else
    {
        $category_id = intval($_POST['category']);
    }


    $survey = $surveyMapper->get($surveyId);
    if ($file_url != 'uploads/videos/')
    {
        $survey->setVideoUrl($file_url);
    }
    $survey->setTitle((htmlspecialchars($_POST["surveyTitle"])));
    $survey->setCategoryId($_POST['category']);
    $survey->setSurveyThanksTitle(htmlspecialchars($_POST["surveyThanks"]));
    $survey->setAccessOnlyByUrl($is_only_by_url);
    $surveyMapper->update($survey);

    $questionMapper = new QuestionMapper();
    $offeredAnswersMapper = new OfferedAnswerMapper();
    $question = new Question();
    if (isset($_POST['question'])) {
        foreach ($_POST['question'] as $question_id => $elem) {
            $question_id = intval($question_id);
            $question = new Question($question_id, $surveyId, htmlspecialchars($elem['title']));
            if (isset($elem['is_multiple_choice'])) {
                if ($elem['is_multiple_choice'] == '1') {
                    $question->setIsPlainText(false);
                } else if ($elem['is_multiple_choice'] == '0') {
                    $question->setIsPlainText(true);
                }
                if ($questionMapper->isExist($question_id)) {
                    $questionMapper->update($question);
                } else {
                    $question_id = $questionMapper->create($question);
                }
                if (isset($elem['option'])) {
                    foreach ($elem['option'] as $option_id => $option) {
                        $option_id = intval($option_id);
                        if ($offeredAnswersMapper->isExist($option_id)) {
                            $offeredAnswersMapper->update(new OfferedAnswer($option_id, $question_id, htmlspecialchars($option)));
                        } else {
                            $offeredAnswersMapper->create(new OfferedAnswer(-1, $question_id, htmlspecialchars($option)));
                        }
                    }
                }
            }

        }
    }
}
if (UserHandler::isAdmin() && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["create_category"])) {
    $categoryMapper = new CategoryMapper();
    $categoryMapper->create(new Category(-1, $_POST['categoryTitle'], $_POST['forWhom'], $_POST['youGet']));
}


if (UserHandler::isAdmin() && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["edit_category"])) {
    $categoryMapper = new CategoryMapper();
    $categoryMapper->update(new Category(intval($_POST['category_id']), $_POST['categoryTitle'], $_POST['forWhom'], $_POST['youGet']));
}

if (UserHandler::isAdmin() && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["delete_category"])) {

    if (isset($_POST['category_id']) && intval($_POST['category_id']) != 1) {
        $categoryMapper = new CategoryMapper();
        $categoryMapper->delete(new Category(intval($_POST['category_id'])));
    }
}





if (UserHandler::isAdmin() && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["delete_survey"])) {
    $surveyMapper = new SurveyMapper();
    $surveyId = intval($_POST['survey_id']);
    $surveyMapper->delete(new Survey($surveyId));
}

if (UserHandler::isAdmin() && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["edit_settings"])) {

    $password_or_email_invalid = !UserHandler::update();
    $password_updated = !$password_or_email_invalid;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin page</title>
    <link rel="stylesheet" href="/css/admin.css?v=<?php echo time() ?>" media="all" type="text/css"/>
    <link href="https://fonts.cdnfonts.com/css/crimson-foam" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/verdana" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="js/jQuery.js" defer></script>
    <script src="js/admin.js?v=<?php echo time() ?>" defer></script>

</head>
<body>
<?php

if (!UserHandler::isAdmin()):
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!UserHandler::login('admin.php')) {
            $password_or_email_invalid = true;
        }
    }
    ?>

    <div id="admin_login_container" class="container">
        <h1 class="text-center mb-4">Login</h1>
        <form method="post">
            <div class="text-danger">
                <?php if ($password_or_email_invalid) : ?>
                    <div class="alert alert-danger" role="alert">
                        Invalid email or password
                    </div>
                <?php endif ?>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" name="email" class="form-control" id="exampleInputEmail1"
                       aria-describedby="emailHelp" placeholder="Enter email">
            </div>
            <br>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" name="password" class="form-control" id="SubmitPassword" placeholder="Password">
            </div>
            <br>
            <button type="submit" class="btn btn-primary submit-button">Login</button>
        </form>
    </div>
<?php elseif ($_SESSION["user"]['is_admin']): ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-light p-3">
        <a class="navbar-brand" href="/admin.php">Admin Panel</a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ">
                <li class="nav-item active">
                    <a class="nav-link" href="/admin.php">All surveys<span class="sr-only"></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin.php?create">Create survey</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin.php?create_category">Create category</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin.php?all_categories">All categories</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/admin.php?settings">Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin.php?logout">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <?php if (isset($_GET['create'])) : ?>
        <div class="container">
            <h1 class="mt-4 mb-4">Create Survey</h1>
            <form id="surveyForm" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post"
                  enctype="multipart/form-data">
                <input type='hidden' value='' name='create_survey'>

                <div class="form-group">
                    <label for="surveyTitle">Survey Title:</label>
                    <input type="text" class="form-control" id="surveyTitle" name="surveyTitle" required>
                </div>
                <div class="form-group pt-3">
                    <label for="surveyDescription">Survey Result Label:</label>
                    <textarea class="form-control" id="surveyDescription" name="surveyThanks" rows="4"></textarea>
                </div>
                <div class="form-group pt-3">
                    <label class="form-label" for="customFile">Video:</label>
                    <input type="file" class="form-control" name="fileToUpload" id="fileToUpload" accept="video"/>
                </div>
                <div class="form-check mt-3">
                    <input id="access_only_by_url" type='hidden'
                           name='access_only_by_url' value="0">
                    <input id="access_only_by_url" type='checkbox'
                           name='access_only_by_url' value="1">
                    <label id="use_multiple_choice" class="form-check-label"
                           for="access_only_by_url">
                        Access only by URL
                    </label>
                </div>
                <?php ;
                $categoryMapper = new CategoryMapper();
                $categories = $categoryMapper->getAll();
                if ($categories != null) :
                ?>
                <select  class="form-select mt-3" aria-label="Default select example"  name="category" required>
                    <option value="-1">Choose Category</option>
                    <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category->getCategoryId() ?>" ><?php echo $category->getCategoryText() ?></option>
                    <?php endforeach; ?>
                </select>
                <?php endif;?>
                <div class="form-group pt-3">
                    <label for="questions">Questions:</label>
                    <div id="survey_questions" class="m-5">
                    </div>
                </div>

                <button id="add_question" type="button" class="btn btn-primary">Add Question</button>

                <hr>

                <button type="submit" class="btn btn-success mb-5">Create Survey</button>
            </form>
        </div>

    <?php elseif (isset($_GET['edit'])) : ?>
        <?php
        $surveyMapper = new SurveyMapper();
        $questionMapper = new QuestionMapper();
        $offeredAnswersMapper = new OfferedAnswerMapper();

        $survey = $surveyMapper->get($_GET['edit']);


        ?>
        <div class="container">
            <h1 class="mt-4 mb-4">Edit Survey</h1>
            <div class="alert alert-warning p-2">
                <strong>Warning!</strong> Deleting questions or options will automatically remove them from the database
                without requiring you to press the save button.
            </div>

            <form id="deleteSurveyForm" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <input type='hidden' value='' name='delete_survey'>
                <input type='hidden' value="<?php echo $survey->getSurveyId(); ?>" name='survey_id'>
                <input type="submit" class="btn btn-danger mt-3 mb-3" value="Delete survey"/>
            </form>

            <form id="surveyForm" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post"
                  enctype="multipart/form-data">
                <input type='hidden' value='' name='edit_survey'>
                <input type='hidden' value="<?php echo $survey->getSurveyId(); ?>" name='survey_id'>
                <div class="form-group">
                    <label for="surveyTitle">Survey Title:</label>
                    <input type="text" class="form-control" id="surveyTitle" name="surveyTitle"
                           value="<?php echo $survey->getTitle() ?>" required/>
                </div>
                <div class="form-group pt-3">
                    <label for="surveyDescription">Survey Result Label:</label>
                    <textarea class="form-control" id="surveyDescription" name="surveyThanks"
                              rows="4"><?php echo $survey->getSurveyThanksTitle() ?></textarea>
                </div>
                <div class="form-group pt-3">
                    <label class="form-label" for="customFile">Video:</label>
                    <input type="file" class="form-control" name="fileToUpload" id="fileToUpload" accept="video"/>
                    <?php if ($survey->getVideoUrl() == '') : ?>
                        <span>Current video: None </span>
                    <?php else: ?>
                        <span>Current video:  <?php echo $_SERVER['DOCUMENT_ROOT'] . '/' . $survey->getVideoUrl() ?> </span>
                    <?php endif; ?>
                </div>
                <div class="form-check mt-3">
                    <input id="access_only_by_url" type='hidden'
                           name='access_only_by_url' value="0">
                    <input id="access_only_by_url" type='checkbox'
                           name='access_only_by_url' value="1"
                        <?php if ($survey->isAccessOnlyByUrl()) {
                            echo 'checked';
                        } ?>
                    >
                    <label id="use_multiple_choice" class="form-check-label"
                           for="access_only_by_url">
                        Access only by URL
                    </label>
                </div>
                <?php
                $categoryMapper = new CategoryMapper();
                $categories = $categoryMapper->getAll();
                if ($categories != null) :
                    ?>
                    <select class="form-select mt-3" aria-label="Default select example"  name="category" required>
                        <option value="-1">Choose Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category->getCategoryId(); ?>"  <?php if ($category->getCategoryId() == $survey->getCategoryId()) { echo "selected"; } ?>><?php echo $category->getCategoryText() ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif;?>
                <div class="form-group pt-3">
                    <label for="questions">Questions:</label>
                    <div id="survey_questions" class="m-5">
                        <?php
                        $questions = $questionMapper->getBySurvey($survey->getSurveyId());
                        if ($questions != NULL) :
                            foreach ($questions as $question) :
                                ?>
                                <div class="question_container question"
                                     data-uid="<?php echo $question->getQuestionId(); ?>">
                                    <h3 class="mt-3 mb-3 question_title">Question&nbsp;</h3>
                                    <button type="button" class="btn btn-danger mt-3 mb-3 delete_question">Delete
                                        question
                                    </button>
                                    <div class="form-group">
                                        <label for="surveyTitle">Question Title:</label>
                                        <input type="text" class="form-control" id="surveyTitle"
                                               name="question[<?php echo $question->getQuestionId(); ?>][title]"
                                               value="<?php echo $question->getQuestionText(); ?>"
                                               required>
                                    </div>
                                    <div class="form-check mt-3">
                                        <input type='hidden' value='0'
                                               name='question[<?php echo $question->getQuestionId(); ?>][is_multiple_choice]'>
                                        <input class="form-check-input multiple_checkbox" type="checkbox" value="1"
                                               id="flexCheckIndeterminate"
                                            <?php if (!$question->isPlainText()) {
                                                echo 'checked';
                                            } ?>
                                               name="question[<?php echo $question->getQuestionId(); ?>][is_multiple_choice]">
                                        <label id="use_multiple_choice" class="form-check-label"
                                               for="flexCheckIndeterminate">
                                            Use multiple choices question
                                        </label>
                                    </div>
                                    <div class="form-group mt-3 options_container">
                                        <?php if (!$question->isPlainText()) :

                                            $offeredAnswersMapper = new OfferedAnswerMapper();
                                            $options = $offeredAnswersMapper->getByQuestion($question->getQuestionId());
                                            ?>
                                            <div class="options_group">
                                                <?php if ($options != NULL) : ?>
                                                    <?php foreach ($options as $option): ?>
                                                        <div class="input-group option mb-3">
                                                            <input type="text" class="form-control"
                                                                   placeholder="Enter option text"
                                                                   aria-label="Recipient's username"
                                                                   aria-describedby="button-addon2"
                                                                   data-uid="<?php echo $option->getOfferedAnswerId(); ?>"
                                                                   name="question[<?php echo $question->getQuestionId(); ?>][option][<?php echo $option->getOfferedAnswerId(); ?>]"
                                                                   value="<?php echo $option->getText() ?>"
                                                                   required/>
                                                            <button class="btn btn-outline-secondary delete_option"
                                                                    type="button">Delete
                                                            </button>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                            <button type="button" class="btn btn-primary mt-3 add_option">Add Option
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    <hr>

                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <button id="add_question" type="button" class="btn btn-primary">Add Question</button>

                <hr>

                <button type="submit" class="btn btn-success mb-5">Save changes</button>
            </form>
        </div>
    <?php elseif (isset($_GET['settings'])): ?>
        <div class="container">
            <h1 class="mt-4 mb-4">Edit admin credentials</h1>
            <form class="" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?settings">
                <?php if ($password_or_email_invalid) : ?>
                    <div class="alert alert-danger" role="alert">
                        Invalid password
                    </div>
                <?php endif ?>
                <?php if ($password_updated) : ?>
                    <div class="alert alert-success" role="alert">
                        Password Successfully updated
                    </div>
                <?php endif; ?>
                <input type='hidden' value='' name='edit_settings'>
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" name="email" class="form-control" id="exampleInputEmail1"
                           aria-describedby="emailHelp" placeholder="Enter email"
                           value="<?php echo $_SESSION["user"]['email'] ?>"
                           required>
                </div>
                <br>
                <div class="form-group">
                    <label for="exampleInputPassword1">Current Password</label>
                    <input type="password" name="current_password" class="form-control" id="SubmitPassword"
                           placeholder="Password" required>
                </div>
                <div class="form-group mt-2">
                    <label for="exampleInputPassword1">New Password</label>
                    <input type="password" name="new_password" class="form-control" id="SubmitPassword"
                           placeholder="Password" required>
                </div>
                <br>
                <button type="submit" class="btn btn-success mb-5">Save changes</button>
            </form>
        </div>

    <?php elseif (isset($_GET['logout'])): UserHandler::logout('admin.php') ?>

    <?php elseif (isset($_GET['download'])): ?>
        <?php
        export_to_csv(intval($_GET['download']));
        header('Location: ' . $_SERVER['HTTPS'] . '/admin.php');
        exit
        ?>
    <?php elseif (isset($_GET['create_category'])): ?>
        <div class="container">
            <h1 class="mt-4 mb-4">Create Category</h1>
            <form id="surveyForm" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <input type='hidden' value='' name='create_category'>

                <div class="form-group">
                    <label for="categoryTitle">Category title</label>
                    <input type="text" class="form-control" id="surveyTitle" name="categoryTitle" required>
                </div>

                <div class="form-group">
                    <label for="forWhom">For whom this quiz?</label>
                    <textarea class="form-control" id="surveyTitle" name="forWhom" required></textarea>
                </div>

                <div class="form-group">
                    <label for="youGet">What you get?</label>
                    <textarea class="form-control" id="surveyTitle" name="youGet" required></textarea>
                </div>
                <hr>
                <button type="submit" class="btn btn-success mb-5">Create Category</button>
            </form>
        </div>
    <?php elseif (isset($_GET['all_categories'])): ?>
        <div class="container">
            <h1 class="mt-4 mb-4">All categories</h1>
            <?php
            $categoryMapper = new CategoryMapper();
            $categories = $categoryMapper->getAll();
            $counter = 1;
            if ($categories != null) :?>
                <?php foreach ($categories as $category) : ?>
                    <div><a class="link-opacity-100 mt-4 mb-4"
                            href="/admin.php?edit_category=<?php echo $category->getCategoryId() ?>">
                            <h3><?php echo $counter . ". " . $category->getCategoryText();
                                $counter++ ?></h3>
                        </a>
                        <button type="button" class="btn btn-primary copy_button_category"
                                data-uid="<?php echo $category->getCategoryId(); ?>">Copy URL
                        </button>
                        <hr>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php elseif (isset($_GET['edit_category'])) : ?>
        <?php
        $categoryMapper = new CategoryMapper();
        $category = $categoryMapper->get($_GET['edit_category']);
        if ($category != null) :
            ?>
            <div class="container">
                <h1 class="mt-4 mb-4">Edit Category</h1>
                <?php if ($category->getCategoryId() != 1) : ?>
                <form id="deleteSurveyForm" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                    <input type='hidden' value='' name='delete_category'>
                    <input type='hidden' value="<?php echo $category->getCategoryId(); ?>" name='category_id'>
                    <input type="submit" class="btn btn-danger mt-3 mb-3" value="Delete category"/>
                </form>
                <?php endif;?>
                <form id="surveyForm" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                    <input type='hidden' value='' name='edit_category'>
                    <input type='hidden' value='<?php echo $category->getCategoryId() ?>' name='category_id'>
                    <div class="form-group">
                        <label for="categoryTitle">Category title</label>
                        <input type="text" class="form-control" id="surveyTitle" name="categoryTitle" value="<?php echo $category->getCategoryText() ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="forWhom">For whom this quiz?</label>
                        <textarea class="form-control" id="surveyTitle" name="forWhom" required><?php echo $category->getCategoryWhom() ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="youGet">What you get?</label>
                        <textarea class="form-control" id="surveyTitle" name="youGet" required>
                            <?php echo $category->getCategoryWhatYouGet() ?>
                        </textarea>
                    </div>
                    <hr>

                    <button type="submit" class="btn btn-success mb-5">Save changes</button>
                </form>
            </div>
        <?php endif?>
    <?php else : ?>
        <div class="container">
            <h1 class="mt-4 mb-4">All Surveys</h1>
            <?php
            $surveyMapper = new SurveyMapper();
            $surveys = $surveyMapper->getAll();
            $counter = 1;
            foreach ($surveys as $survey):
                ?>
                <div><a class="link-opacity-100 mt-4 mb-4" href="/admin.php?edit=<?php echo $survey->getSurveyId() ?>">
                        <h3><?php echo $counter . ". " . $survey->getTitle();
                            $counter++ ?></h3>
                    </a>
                    <a class="mb-5 download_csv" href="/admin.php?download=<?php echo $survey->getSurveyId(); ?>">
                        <button type="button" class="btn btn-primary">Download CSV</button>
                    </a>
                    <button type="button" class="btn btn-primary copy_button"
                            data-uid="<?php echo $survey->getSurveyId(); ?>">Copy URL
                    </button>
                    <hr>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>
</body>
</html>