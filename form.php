<?php

require 'Database.php';
require 'FormHandler.php';

$host = "localhost";
$user = "root";
$pass = "";
$db = "form";

$db = new Database($host, $user, $pass, $db);

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';

$formHandler = new FormHandler($db, $name, $email, $phone);

$validationResult = $formHandler->validate();
if (!$validationResult['success']) {
    echo json_encode($validationResult);
    exit;
}

$recentSubmissionResult = $formHandler->isRecentSubmission();
if (!$recentSubmissionResult['success']) {
    echo json_encode($recentSubmissionResult);
    exit;
}

$submitResult = $formHandler->submit();
echo json_encode($submitResult);
exit;
?>