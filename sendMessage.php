<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once('../../../wp-load.php');
include_once('./SMSLookup.php');

global $wpdb;

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    sendMessage();
} else {
    echo json_encode(array("status"=>"error",
                              "message"=>"Bad HTTP method"));
    die("Incorrect HTTP Method");
}

function getNumbers(){
    global $wpdb;
    $table_name = $wpdb->prefix . "SMS";

    $sql = "SELECT `sms_email` FROM `$table_name` WHERE 1;";
    $result = $wpdb->get_results($sql);
    if(sizeof($result) == 0 || $result == null){
        echo json_encode(array("status"=>"error",
                              "message"=>"No numbers to send the message to."));
        die();
    }
    return $result;
}
//
//function getAccountInfo(){
//    global $wpdb;
//    $table_name = $wpdb->prefix . "SMS_config";
//
//    $sql = "SELECT * FROM `$table_name` WHERE 1;";
//    $result = $wpdb->get_results($sql);
//    if(sizeof($result) == 0 || $result == null){
//        echo json_encode(array("status"=>"error",
//                              "message"=>"No account info saved."));
//        die();
//    }
//    return $result[0];
//}

function sendMessage(){
    $numberArr = getNumbers();
    $resultsArr = array();
    foreach($numberArr as $row){

    }
    echo json_encode($resultsArr);
}


?>