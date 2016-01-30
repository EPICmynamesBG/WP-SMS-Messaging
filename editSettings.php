<?php
include_once('../../../wp-load.php');

global $wpdb;

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $data = verifyData();
    saveSettings($data);
} else {
    echo json_encode(array("status"=>"error",
                              "message"=>"Bad HTTP method"));
    die("Incorret HTTP Method");
}

function verifyData(){
    if (strlen($_POST["account_sid"]) != 34){
        echo json_encode(array("status"=>"error",
                              "message"=>"AccountSID must be 34 characters"));
        die();
    }
    if (strlen($_POST["account_auth"]) != 32){
        echo json_encode(array("status"=>"error",
                              "message"=>"AccountAuth must be 32 characters"));
        die();
    }
    if (strlen($_POST["service_sid"]) != 34){
        echo json_encode(array("status"=>"error",
                              "message"=>"ServiceSID must be 34 characters"));
        die();
    };
    $phone = $_POST["phone"];
    $phone = str_replace("(", "", $phone);
    $phone = str_replace(")", "", $phone);
    $phone = str_replace("-", "", $phone);
    $phone = str_replace("+", "", $phone);
    $phone = str_replace(" ", "", $phone);
    if (strlen($phone) != 10){
        echo json_encode(array("status"=>"error",
                              "message"=>"Phone number must be 10 digits"));
        die("Phone number must be 10 digits");
    }
    $result = array("account_sid"=>$_POST["account_sid"],
                    "account_auth"=>$_POST["account_auth"],
                    "service_sid"=>$_POST["service_sid"],
                   "phone"=>$phone);
    return $result;
}

function saveSettings($data){
    global $wpdb;
    $table_name = $wpdb->prefix . "SMS_config";
    $phone = $data['phone'];
    $accSID = $data['account_sid'];
    $accAuth = $data['account_auth'];
    $servSID = $data['service_sid'];

    $sqlDel = "DELETE FROM `$table_name` WHERE 1";
    $sqlSave = "INSERT INTO $table_name(`account_sid`, `account_auth`, `service_sid`, `phone_number`) VALUES ('$accSID','$accAuth','$servSID','$phone')";

    $result = $wpdb->query($sqlDel);

    $result2 = $wpdb->query($sqlSave);
    if ($result2 == 1){
        echo json_encode(array("status"=>"success"));
    } else {
        echo json_encode(array("status"=>"error",
                              "message"=>"Query execution error. Settings could not be saved."));
    }
}

?>