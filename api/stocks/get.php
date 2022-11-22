<?php
header("content-type: application/json");
if(isset($_GET["token"])) {
    $token = $_GET["token"];
} else {
    echo json_encode(array("error" => "Parameter token is required."));
    exit;
}

$accounts = json_decode(file_get_contents("../../data/accounts.json"), true);
foreach($accounts as $acc) {
    if($acc["token"] == $token) {
        try {
            $acc_data = json_decode(file_get_contents("../../data/userdata/".((string)$acc["id"]).".json"), true);
            echo json_encode(array("actions" => $acc_data["actions"]));
            exit;
        } catch(Exception $e) {
            echo json_encode(array("error" => "Something went wrong.".$e));
            exit;
        }
    }
}
echo json_encode(array("error" => "Invalid token. Try logging in again."));
exit;
?>