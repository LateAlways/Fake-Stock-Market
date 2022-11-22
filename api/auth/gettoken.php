<?php
header("content-type: application/json");
if(isset($_GET["username"]) && isset($_GET["password"])) {
    $username = $_GET["username"];
    $password = $_GET["password"];
} else {
    echo json_encode(array("error" => "Parameter username and password need to be set."));
    exit;
}

$accounts = json_decode(file_get_contents("../../data/accounts.json"), true);
foreach($accounts as $acc) {
    if($acc["username"] == $username && password_verify($password, $acc["password"])) {
        echo json_encode(array("success" => true, "token" => $acc["token"]));
        exit;
    } elseif($acc["username"] == $username) {
        echo json_encode(array("error" => "Invalid username or password."));
        exit;
    }
}
echo json_encode(array("error" => "Account not found."));
exit;
?>