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
include_once('../includes/custom-functions.php');
include_once('../includes/functions.php');

$db = new Database();
$db->connect();
$fn = new functions;

$date = date('Y-m-d');
$datetime = date('Y-m-d H:i:s'); 

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

$sql = "SELECT COUNT(*) AS count FROM share_apps WHERE user_id = '$user_id' AND app_id = '$app_id' AND DATE(datetime) = '$date'";
$db->sql($sql);
$res_check_plan = $db->getResult();
$user_num_times = $res_check_plan[0]['count'];

$share_limit_per_day = 1;

if ($user_num_times >= $share_limit_per_day) {
    $response['success'] = false;
    $response['message'] = "Today Share Limit Is Over";
    print_r(json_encode($response));
    return false;
}

$sql = "INSERT INTO share_apps (`user_id`, `app_id`, `datetime`) VALUES ('$user_id', '$app_id', '$datetime')";
$db->sql($sql);

$sql = "SELECT COUNT(app_id) AS count FROM share_apps WHERE user_id = '$user_id' AND DATE(datetime) = '$date'";
$db->sql($sql);
$res_check_plan = $db->getResult();
$shared_apps_count = $res_check_plan[0]['count'];

$sql = "SELECT COUNT(*) AS count FROM apps";
$db->sql($sql);
$res_check_plan = $db->getResult();
$total_apps_count = $res_check_plan[0]['count'];

if ($shared_apps_count == $total_apps_count) {
    
    $amount = 2;
    $sql = "UPDATE users SET balance = balance + $amount, today_income = today_income + $amount, total_income = total_income + $amount WHERE id = '$user_id'";
    $db->sql($sql);
    
    $sql = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$user_id', '$amount', '$datetime', 'daily_income')";
    $db->sql($sql);
}

$pending_apps_count = $total_apps_count - $shared_apps_count;

$response['success'] = true;
$response['total_apps_count'] = $total_apps_count;
$response['shared_apps_count'] = $shared_apps_count;
$response['pending_apps_count'] = $pending_apps_count;
$response['message'] = "Shared Successfully";
print_r(json_encode($response));
?>
