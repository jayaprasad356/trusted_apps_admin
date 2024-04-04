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

if (empty($_POST['name'])) {
    $response['success'] = false;
    $response['message'] = "Name is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['email'])) {
    $response['success'] = false;
    $response['message'] = "Email is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['profile'])) {
    $response['success'] = false;
    $response['message'] = "Profile is Empty";
    print_r(json_encode($response));
    return false;
}

$name = $db->escapeString($_POST['name']);
$email = $db->escapeString($_POST['email']);
$profile = $db->escapeString($_POST['profile']);
$datetime = date('Y-m-d H:i:s');

$sql = "SELECT * FROM users WHERE email='$email'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num >= 1) {
    $sql = "UPDATE users SET `name`='$name', `profile`='$profile',`registered_datetime`='$datetime' WHERE email='$email'";
    $db->sql($sql);
    
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $db->sql($sql);
    $res = $db->getResult();

    $response['success'] = true;
    $response['message'] = "Login Successfully";
    $response['data'] = $res;
    print_r(json_encode($response));
} else {
    $sql = "INSERT INTO users (`name`, `email`, `profile`, `registered_datetime`) VALUES ('$name', '$email', '$profile', '$datetime')";
    $db->sql($sql);
    
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $db->sql($sql);
    $res = $db->getResult();

    $response['success'] = true;
    $response['message'] = "Login Successfully";
    $response['data'] = $res;
    print_r(json_encode($response));
}
?>
