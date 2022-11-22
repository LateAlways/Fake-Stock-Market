<?php
header("content-type: application/json");
if(isset($_GET["token"])) {
    $token = $_GET["token"];
} else {
    echo json_encode(array("error" => "No token provided."));
    exit;
}
$accounts = json_decode(file_get_contents("../../data/accounts.json"), true);
foreach($accounts as $acc) {
    if($acc["token"] == $token) {
        echo json_encode(array("success" => true, "balance" => json_decode(file_get_contents("../../data/userdata/".((string)$acc["id"]).".json"), true)["balance"]));
        exit;
    }
}
echo json_encode(array("error" => "Invalid token. Try logging in again."));
exit;
?>