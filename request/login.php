<?php

    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Headers: *');

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        }

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        }

        exit(0);
    }

    date_default_timezone_set('America/Fortaleza');

    require_once('../classes/class.LoginContr.php');
    $lgnCrt = new LoginContr();

    $user = $lgnCrt->login($_REQUEST);

    if (!$user) {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'incorrect username or password']);
        die;
    }

    $hash_connect = md5($_REQUEST['parceiro'] . date("YmdHis"));
    $lgnCrt->saveUserHash($hash_connect, $_REQUEST['user']);
    $user['api_hash'] = $hash_connect;

    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'login successful', 'data' => $user]);
    die;
?>