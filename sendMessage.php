<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('../../../wp-load.php');

global $wpdb;

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $settings = getSettings();
    sendMessage($settings);
} else {
    echo json_encode(array("status"=>"error",
                              "message"=>"Bad HTTP method"));
    die("Incorrect HTTP Method");
}

function getMailList(){
    global $wpdb;
    $table_name = $wpdb->prefix . "SMS";

    $sql = "SELECT * FROM `$table_name` WHERE 1;";
    $result = $wpdb->get_results($sql);
    if(sizeof($result) == 0 || $result == null){
        echo json_encode(array("status"=>"error",
                              "message"=>"No numbers to send the message to."));
        die();
    }
    return $result;
}

function getSettings(){
    global $wpdb;
    $table_name = $wpdb->prefix . "SMS_config";

    $sql = "SELECT * FROM `$table_name` WHERE 1;";
    $result = $wpdb->get_results($sql);
    if(sizeof($result) == 0 || $result == null){
        echo json_encode(array("status"=>"error",
                              "message"=>"No account info saved."));
        die();
    }
    return $result[0];
}

function sendMessage($settings){
    $numberArr = getMailList();
    $resultsArr = array();
    $subject = "Practice Reminder";
    $message = $_POST['message'];
    $from = $settings->from_email;

    $headers = "From: ".$from . "\r\n".
        'X-Mailer: PHP/' . phpversion();

    foreach($numberArr as $row){
        $to = $row->sms_email;
        $results = mail("bgroff@bsu.edu", $subject, $message, $headers, "-f$from");
        $temp = array(
            'mailStatus'=> $results,
            'number'=> $row->phone_number,
            'sms_email'=> $row->sms_email,
            'mailContents'=> array(
                'to' => $to,
                'subject' => $subject,
                'message' => $message,
                'headers' => $headers
            )
        );

        array_push($resultsArr, $temp);
    }
    echo json_encode($resultsArr);
}


?>