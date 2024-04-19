<?php
require_once $_SERVER['DOCUMENT_ROOT']."/src/models/UserHandler.php";
require_once  $_SERVER['DOCUMENT_ROOT'].'/src/models/User.php';
require_once  $_SERVER['DOCUMENT_ROOT'].'/src/models/mappers/UserMapper.php';
if (UserHandler::isLoggedIn())
{
    $survey_id = '';
    if (isset($_GET['survey']))
    {
        $survey_id = $_GET['survey'];
    }
    header('Location: '.$_SERVER['HTTPS'].'/quiz.php?quiz='.$survey_id);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="/css/contact.css?">
    <link href="https://fonts.cdnfonts.com/css/crimson-foam" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/verdana" rel="stylesheet">
</head>

<body>
<img src="/assets/photoBackground.png" alt="">
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        {
            $mapper = new UserMapper();
            $userId = -1;
            if ($mapper->isExistByEmail($_POST['email']))
            {
                $userId = $mapper->getByEmail($_POST['email'])->getUserId();
            }
            else
            {
                $userId = $mapper->create(new User(0,$_POST['email']));
            }
            $userData = [
                "user_id" => $userId,
                "email" => $_POST['email'],
                "is_admin" => false,
            ];
            $_SESSION["user"] = $userData;
            $survey_id = '';
            if (isset($_GET['survey']))
            {
                $survey_id = $_GET['survey'];
            }
            header('Location: '.$_SERVER['HTTPS'].'/quiz.php?quiz='.$survey_id);
        }
    }
?>
<form action="" method="post" name="contact_form">
    <h1>to continue the quiz enter email</h1>
    <h2>We’ll use this to start saving your answers</h2>
    <div class="container">
        <input type="email" name="email" placeholder="Email">
        <button type="submit">Continue</button>
    </div>
</form>
<script src="/js/script.js">
</script>
</body>

</html>