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
if (empty($_POST['comments'])) {
    $response['success'] = false;
    $response['message'] = "Comments is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['ratings'])) {
    $response['success'] = false;
    $response['message'] = "Ratings is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);
$app_id = $db->escapeString($_POST['app_id']);
$comments = $db->escapeString($_POST['comments']);
$ratings = $db->escapeString($_POST['ratings']);

$sql = "SELECT * FROM users WHERE id = $user_id ";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    print_r(json_encode($response));
    return false;
}


$sql = "SELECT * FROM apps WHERE id = $app_id ";
$db->sql($sql);
$apps = $db->getResult();

if (empty($apps)) {
    $response['success'] = false;
    $response['message'] = "Apps not found";
    print_r(json_encode($response));
    return false;
}
$rate_count = $apps[0]['rate_count'];


$sql_check = "SELECT * FROM ratings WHERE user_id = $user_id AND app_id = $app_id";
$db->sql($sql_check);
$res_check_user = $db->getResult();

if (!empty($res_check_user)) {
    $response['success'] = false;
    $response['message'] = "User Already Give their Ratings For this App";
    print_r(json_encode($response));
    return false;
}

$sql = "INSERT INTO ratings (user_id, app_id,ratings,comments) VALUES ('$user_id','$app_id',$ratings,'$comments')";
$db->sql($sql);


// $sql = "SELECT * FROM ratings SET  WHERE app_id = $app_id";
// $db->sql($sql);
// $res = $db->getResult();

// $sql = "UPDATE apps SET rate_count = $rate_count + 1 , ratings = '$avg_ratings' WHERE id=" . $app_id;
// $db->sql($sql);

$response['success'] = true;
$response['message'] = "Rated Successfully";
print_r(json_encode($response));
return false;   

?>