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
$referred_by = isset($_POST['referred_by']) ? $db->escapeString($_POST['referred_by']) : '';
$datetime = date('Y-m-d H:i:s');

$sql = "SELECT * FROM users WHERE email='$email'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

     function generateRandomString($length) {
        // Define an array containing digits and alphabets
        $characters = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
    
        // Shuffle the array to make the selection random
        shuffle($characters);
    
        // Select random characters from the shuffled array
        $random_string = implode('', array_slice($characters, 0, $length));
    
        return $random_string;
    }
    $refer_code = generateRandomString(6);

if ($num >= 1) {
    $sql = "UPDATE users SET `name`='$name', `profile`='$profile',`registered_datetime`='$datetime', `refer_code`='$refer_code',`referred_by`='$referred_by' WHERE email='$email'";
    $db->sql($sql);
    
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $db->sql($sql);
    $res = $db->getResult();

    $response['success'] = true;
    $response['message'] = "Login Successfully";
    $response['data'] = $res;
    print_r(json_encode($response));
} else {
    $sql = "INSERT INTO users (`name`, `email`, `profile`, `registered_datetime`,`referred_by`, `refer_code`) VALUES ('$name', '$email', '$profile', '$datetime', '$referred_by', '$refer_code')";
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
