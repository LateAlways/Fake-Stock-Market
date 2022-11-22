<?php
header("content-type: application/json");
if(isset($_GET["token"]) && isset($_GET["id"])) {
    $token = $_GET["token"];
    $id = $_GET["id"];
} else {
    echo json_encode(array("error" => "Parameters token and id are required."));
    exit;
}

$accounts = json_decode(file_get_contents("../../data/accounts.json"), true);
foreach($accounts as $acc) {
    if($acc["token"] == $token) {
        try {
            $acc_data = json_decode(file_get_contents("../../data/userdata/".((string)$acc["id"]).".json"), true);
            foreach($acc_data["actions"] as $action) {
                if($action["id"] == $id) {
                    $symbol = $action["symbol"];
                    $stock_data = json_decode(file_get_contents("https://query1.finance.yahoo.com/v8/finance/chart/".strtoupper($symbol)."?region=US&lang=en-US&includePrePost=false&interval=2m&useYfid=true&range=1d&corsDomain=finance.yahoo.com&.tsrc=finance"), true);
                    $actions = array();
                    foreach($acc_data["actions"] as $ac) {
                        if($ac != $action) {
                            $actions[] = $ac;
                        }
                    }
                    $acc_data["actions"] = $actions;
                    $acc_data["balance"] += $stock_data["chart"]["result"][0]["meta"]["regularMarketPrice"];
                    file_put_contents("../../data/userdata/".((string)$acc["id"]).".json", json_encode($acc_data));
                    echo json_encode(array("success" => true));
                    exit;
                }
            }
            echo json_encode(array("error" => "Action ID is invalid."));
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