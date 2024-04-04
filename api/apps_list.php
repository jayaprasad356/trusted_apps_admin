<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once('../includes/crud.php');

$db = new Database();
$db->connect();


$sql = "SELECT * FROM apps";
$db->sql($sql);
$res= $db->getResult();
$num = $db->numRows($res);

if ($num >= 1){
    foreach ($res as $row) {
        $temp['id'] = $row['id'];
        $temp['name'] = $row['name'];
        $temp['ratings'] = $row['ratings'];
        $temp['rate_count'] = $row['rate_count'];
        $temp['refer_link'] = $row['refer_link'];
        $temp['logo'] = DOMAIN_URL . $row['logo'];
        $temp['screenshot'] = DOMAIN_URL . $row['screenshot'];
        $temp['plan_details'] = $row['plan_details'];
        $rows[] = $temp;
    }
    $response['success'] = true;
    $response['message'] = "Apps Listed Successfully";
    $response['data'] = $rows;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "Apps Not found";
    print_r(json_encode($response));

}
