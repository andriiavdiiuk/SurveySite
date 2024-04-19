<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/PHPMailer/src/Exception.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/PHPMailer/src/PHPMailer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/PHPMailer/src/SMTP.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/core/config.php";

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/models/UserAnswer.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/models/mappers/UserAnswerMapper.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/models/mappers/OfferedAnswerMapper.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/models/mappers/QuestionMapper.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/mappers/UserMapper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['functionname'])) {
    if ($_POST['functionname'] == 'delete_option') {
        if (isset($_POST['id'])) {
            $id = intval($_POST['id']);
            $offeredAnswersMapper = new OfferedAnswerMapper();
            $offeredAnswersMapper->delete(new OfferedAnswer($id));
        }
        exit;
    }
    if ($_POST['functionname'] == 'delete_question') {
        if (isset($_POST['id'])) {
            $id = intval($_POST['id']);
            $questionMapper = new QuestionMapper();
            $questionMapper->delete(new Question($id));
        }
        exit;
    }

}


function export_to_csv(int $survey_id)
{

    $surveyMapper = new SurveyMapper();
    $questionMapper = new QuestionMapper();
    $offeredAnswersMapper = new OfferedAnswerMapper();
    $userAnswersMapper = new UserAnswerMapper();
    $userMapper = new UserMapper();
    $survey = $surveyMapper->get($survey_id);


    $output_array = array(
        'User Email',
    );

    if ($survey != null) {
        $file = 'uploads/' . $survey->getTitle() . '.csv';
        $fp = fopen($file, 'w');
        $questions = $questionMapper->getBySurvey($survey_id);
        foreach ($questions as $question) {
            if ($question != null) {
                $output_array[] = htmlspecialchars_decode($question->getQuestionText());
            }
        }
        fputcsv($fp, $output_array);

        $counter = 0;
        $question_counter = 0;
        $data = array();
        if ($survey != null) {
            $questions = $questionMapper->getBySurvey($survey_id);
            foreach ($questions as $question) {
                $question_counter++;
                if ($question != null) {
                    $userAnswers = $userAnswersMapper->getByQuestion($question->getQuestionId());
                    if ($userAnswers != null) {
                        foreach ($userAnswers as $answer) {
                            $user = $userMapper->get($answer->getUserId());
                            if ($user != null) {
                                if ($question->isPlainText()) {
                                    $data[$user->getEmail()][$question->getQuestionId()][] = htmlspecialchars_decode($answer->getUserAnswerText());
                                } else {
                                    $offeredAnswer = $offeredAnswersMapper->get($answer->getOfferedAnswerId());
                                    if ($offeredAnswer != null) {
                                        $data[$user->getEmail()][$question->getQuestionId()][] = htmlspecialchars_decode($offeredAnswer->getText());
                                    } else {
                                        $data[$user->getEmail()][$question->getQuestionId()][] = '';
                                    }
                                }
                            }
                            $counter++;
                        }
                    }
                }
            }
        }
    }
    $result = [];
    foreach ($data as $user => $elem) {

        $dataArrays = array();
        foreach ($elem as $userData) {

            foreach ($userData as $index => $values) {
                if (!isset($dataArrays[$index])) {
                    $dataArrays[$index] = array();
                    $dataArrays[$index][] = $user;
                }

                $dataArrays[$index][] = $values;
            }
        }
        $result[] = $dataArrays;
    }


    foreach ($result as $userRow) {
        foreach ($userRow as $row) {
            fputcsv($fp, $row);
        }
    }
    fclose($fp);

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    unlink($file);
    exit;
}

function send_message()
{
    if (SEND_MESSAGE) {
        try {
            $questionMapper = new QuestionMapper();
            $offeredAnswerMapper = new OfferedAnswerMapper();
            $user_email = $_SESSION['user']['email'];
            $mail_body = 'User: ' . $user_email . '<br>';

            $counter = 1;
            foreach ($_POST['answers'] as $question_id => $question) {
                $question_id = intval($question_id);
                $question_db = $questionMapper->get($question_id);
                $mail_body .= 'Question ' . $counter . '<br>';
                $mail_body .= $question_db->getQuestionText() . '<br>';
                if (isset($question['is_plain_text']) && $question['is_plain_text'] == '0') {
                    if (isset($question['answer'])) {
                        $option_id = intval($question['answer']);
                        $option = $offeredAnswerMapper->get($option_id);
                        if ($option != null) {
                            $mail_body .= 'User answer: ' . $option->getText() . '<br>';
                        } else {
                            $mail_body .= 'User has not provided an answer<br>';
                        }

                    } else {
                        $mail_body .= 'User has not provided an answer<br>';
                    }
                } else if ($question != '') {
                    $mail_body .= 'User answer: ' . $question . '<br>';
                } else {
                    $mail_body .= 'User has not provided an answer<br>';
                }
                $mail_body .= '<br>';
                $counter++;
            }

            $mail = new PHPMailer();
            $mail->SMTPDebug = false;
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_ENCRYPTION;
            $mail->Port = SMTP_PORT;

            $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
            $mail->addAddress(SMTP_TO, SMTP_TO_NAME);

            $mail->isHTML(true);
            $mail->Subject = 'User quiz result: ' . $user_email;
            $mail->Body = $mail_body;

            $mail->send();
        } catch (Exception $e) {
        }
    }
}


?>