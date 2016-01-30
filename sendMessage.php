<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('../../../wp-load.php');

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

    $sql = "SELECT `phone_number` FROM `$table_name` WHERE 1;";
    $result = $wpdb->get_results($sql);
    return $result;
}

function sendMessage(){
    $numberArr = getNumbers();
}

?>