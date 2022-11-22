<?php
header("content-type: application/json");
if(isset($_GET["username"]) && isset($_GET["password"])) {
    $username = $_GET["username"];
    $password = $_GET["password"];
} else {
    echo json_encode(array("error" => "Parameter username and password need to be set."));
    exit;
}
if($username == null || $password == null) {
    echo json_encode(array("error" => "Parameter username and password need to be set."));
    exit;
}

function RandomString($length) {
    $keys = array_merge(range(0,9), range('a', 'z'));

    $key = '';
    for($i=0; $i < $length; $i++) {
        $key .= $keys[mt_rand(0, count($keys) - 1)];
    }
    return $key;
}

$accounts = json_decode(file_get_contents("../../data/accounts.json"), true);
foreach($accounts as $acc) {
    if($acc["username"] == $username) {
        echo json_encode(array("error" => "Username already in use."));
        exit;
    }
}
$hash = password_hash($password, PASSWORD_DEFAULT);

$account = array("id" => count($accounts)+1, "username" => $username, "password" => $hash, "token" => RandomString(200));
$accounts[] = $account;

file_put_contents("../../data/userdata/".((string)$account["id"]).".json", json_encode(array("balance" => 0, "action_increment" => 0, "actions" => array())));

file_put_contents("../../data/accounts.json", json_encode($accounts));
echo json_encode(array("success" => true));
exit;
?>