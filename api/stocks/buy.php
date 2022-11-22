<?php
header("content-type: application/json");
if(isset($_GET["token"]) && isset($_GET["symbol"]) && isset($_GET["amount"])) {
    $token = $_GET["token"];
    $symbol = $_GET["symbol"];
    $amount = $_GET["amount"];
} else {
    echo json_encode(array("error" => "Parameters token, symbol and amount are required."));
    exit;
}

$accounts = json_decode(file_get_contents("../../data/accounts.json"), true);
foreach($accounts as $acc) {
    if($acc["token"] == $token) {
        try {
            $acc_data = json_decode(file_get_contents("../../data/userdata/".((string)$acc["id"]).".json"), true);
            $stock_data = json_decode(file_get_contents("https://query1.finance.yahoo.com/v8/finance/chart/".strtoupper($symbol)."?region=US&lang=en-US&includePrePost=false&interval=2m&useYfid=true&range=1d&corsDomain=finance.yahoo.com&.tsrc=finance"), true);
            $acc_data["balance"] -= $stock_data["chart"]["result"][0]["meta"]["regularMarketPrice"];
            for ($x = 0; $x <= (int) $amount; $x++) {
                $acc_data["actions"][] = array("id" => $acc_data["action_increment"]+1, "symbol" => $symbol, "price_bought" => $stock_data["chart"]["result"][0]["meta"]["regularMarketPrice"]);
            }
            $acc_data["action_increment"] += 1;
            file_put_contents("../../data/userdata/".((string)$acc["id"]).".json", json_encode($acc_data));
            echo json_encode(array("success" => true, "action_id" => $acc_data["actions"][count($acc_data["actions"])-1]["id"]));
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