<?php
include_once('../../../wp-blog-header.php');

global $wpdb;

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $data = verifyData();
    saveSettings($data);
} else {
    die("Incorret HTTP Method");
}

function verifyData(){
    if (strlen($_POST["sid"]) != 34){
        echo("Bad SID");
        die("SID is not 34 characters");
    }
    $phone = $_POST["phone"];
    $phone = str_replace("(", "", $phone);
    $phone = str_replace(")", "", $phone);
    $phone = str_replace("-", "", $phone);
    $phone = str_replace("+", "", $phone);
    $phone = str_replace(" ", "", $phone);
    if (strlen($phone) != 10){
        echo("Bad Phone");
        die("Phone number must be 10 digits");
    }
    $result = array("sid"=>$_POST["sid"],
                   "phone"=>$phone);
    return $result;
}

function saveSettings($data){
    global $wpdb;
    $table_name = $wpdb->prefix . "SMS_config";
    $phone = $data['phone'];
    $sid = $data['sid'];

    $sqlDel = "DELETE FROM `$table_name` WHERE 1";
    $sqlSave = "INSERT INTO $table_name(`id`, `phone_number`, `sid`) VALUES (DEFAULT,'$phone','$sid')";

    $result = $wpdb->query($sqlDel);

    $result2 = $wpdb->query($sqlSave);
    if ($result2 == 1){
        echo json_encode(array("status"=>"success"));
    } else {
        echo json_encode(array("status"=>"error"));
    }
}

?>