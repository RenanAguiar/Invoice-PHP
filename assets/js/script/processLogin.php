<?php

include('../config.php');

$cUser = new User();
$response_array = array();

//$email = isset($_POST["email"]) ? (trim($_POST["email"])) : "";
$password = isset($_POST["password"]) ? (trim($_POST["password"])) : "";


$email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL);

if (Util::checkNull($email) == NULL) {
    $response_array['status'] = 'error';
    $response_array['details'] = "Incorrect password/username";
}

if (Util::checkNull($password) == NULL) {
    $response_array['status'] = 'error';
    $response_array['details'] = "Incorrect password/username";
}

if (Util::array_count($response_array) == 0) {
    $cUser->doLogin(email, $password);

    $error = $cUser->error;
    if (Util::array_count($error) == 0) {
        $response_array['status'] = 'success';
    } else {
        $response_array['status'] = 'error';
        $response_array['details'] = $cUser->error;
    }
}

header('Content-type: application/json');
echo json_encode($response_array);