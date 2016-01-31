<?php

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
    if(sizeof($result) == 0 || $result == null){
        echo json_encode(array("status"=>"error",
                              "message"=>"No numbers to send the message to."));
        die();
    }
    return $result;
}

function getAccountInfo(){
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

function sendMessage(){
    $account = getAccountInfo();
    $url = "https://api.twilio.com/2010-04-01/Accounts/".$account->account_sid."/Messages";

    $numberArr = getNumbers();
    $resultArr = [];
    $auth = $account->account_sid. ":" .$account->account_auth;

    foreach($numberArr as $num){
        $number = $num->phone_number;

        if($account->service_sid != null
           && $account->service_sid != ""){
            $data = array(
                "MessagingServiceSid" => $account->service_sid,
                "To" => "+1".$number,
                "Body" => $_POST['message']
            );
        }
        else if ($account->phone_number != null
                 && $account->phone_number != ""){
            $data = array(
                "From" => $account->phone_number,
                "To" => "+1".$number,
                "Body" => $_POST['message']
            );
        }
        else {
            echo json_encode(array("status"=>"error",
                              "message"=>"No phone or service SID saved."));
            die();
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 3 );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt( $ch, CURLOPT_USERPWD, $auth );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);
        $res = responseProcessor($result);

        array_push($resultArr, $res);
    }
    echo json_encode($resultArr);
}

function responseProcessor($xmlResponse){
    $toStart = strpos($xmlResponse, "<To>");
    $toEnd = strpos($xmlResponse, "</To>", $toStart) - 4;
    $statusStart = strpos($xmlResponse, "<Status>", $toEnd);
    $statusEnd = strpos($xmlResponse, "</Status>", $statusStart) - 8;
    $to = substr($xmlResponse, $toStart + 4, $toEnd - $toStart);
    $status = substr($xmlResponse, $statusStart + 8, $statusEnd - $statusStart);

    $processed = array(
        "To"=> $to,
        "Status"=> $status
    );

    return $processed;
}
?>