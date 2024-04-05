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

$sql = "SELECT r.id, r.user_id, u.name AS name, u.mobile AS mobile, u.profile AS profile, r.app_id, a.name AS app_name, a.ratings AS app_ratings, a.logo AS app_logo, a.refer_link AS app_refer_link, a.screenshot AS app_screenshot, r.ratings AS user_ratings, r.comments AS user_comments
        FROM ratings r
        INNER JOIN users u ON r.user_id = u.id
        INNER JOIN apps a ON r.app_id = a.id
        ORDER BY r.datetime DESC
        LIMIT 25";

$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num >= 1) {
    foreach ($res as $row) {
        $temp['id'] = $row['id'];
        $temp['name'] = $row['name'];
        $temp['mobile'] = $row['mobile'];
        $temp['user_ratings'] = $row['user_ratings'];
        $temp['user_comments'] = $row['user_comments']; 
        $temp['profile'] = $row['profile'];
        $temp['app_name'] = $row['app_name'];
        $temp['app_ratings'] = $row['app_ratings'];
        $temp['app_logo'] = DOMAIN_URL . $row['app_logo'];
        $temp['app_screenshot'] = DOMAIN_URL . $row['app_screenshot'];
        $temp['app_refer_link'] = $row['app_refer_link'];
        $rows[] = $temp;
    }
    $response['success'] = true;
    $response['message'] = "Ratings Details Listed Successfully";
    $response['data'] = $rows;
    print_r(json_encode($response));
} else {
    $response['success'] = false;
    $response['message'] = "Ratings Not found";
    print_r(json_encode($response));
}
?>
