<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Asia/Kolkata');

include_once('../includes/crud.php');

$db = new Database();
$db->connect();
include_once('../includes/custom-functions.php');
include_once('../includes/functions.php');
$fn = new functions;


$date = date('Y-m-d');
if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}

if (empty($_POST['app_id'])) {
    $response['success'] = false;
    $response['message'] = "App Id is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);
$app_id = $db->escapeString($_POST['app_id']);

$sql_check = "SELECT * FROM ratings WHERE user_id = $user_id AND app_id = $app_id";
$db->sql($sql_check);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num >= 1){
    $response['success'] = true;
    $response['message'] = "Ratings Listed Successfully";
    $response['data'] = $res;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "Ratings Not found";
    print_r(json_encode($response));

}