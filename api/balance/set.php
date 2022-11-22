<?php
header("content-type: application/json");
if(isset($_GET["token"]) && isset($_GET["amount"])) {
    $token = $_GET["token"];
    $amount = $_GET["amount"];
} else {
    echo json_encode(array("error" => "Parameters token and amount are required."));
    exit;
}

$accounts = json_decode(file_get_contents("../../data/accounts.json"), true);
$i = 0;
foreach($accounts as $acc) {
    if($acc["token"] == $token) {
        $b = json_decode(file_get_contents("../../data/userdata/".((string)$acc["id"]).".json"), true);
        try {
            $b["balance"] = (float)$amount;
        } catch(Exception $e) {
            echo json_encode(array("error" => "Amount must be a number."));
            exit;
        }
        file_put_contents("../../data/userdata/".((string)$acc["id"]).".json", json_encode($b));
        echo json_encode(array("success" => true, "balance" => (float)$amount));
        exit;
    }
    $i += 1;
}
echo json_encode(array("error" => "Invalid token. Try logging in again."));
exit;
?>