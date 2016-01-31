<?php
include_once('../../../wp-load.php');

global $wpdb;

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = verifyData();
    saveSettings($email);
} else {
    echo json_encode(array("status"=>"error",
                              "message"=>"Bad HTTP method"));
    die();
}

function verifyData(){
    $email = $_POST["email"];
    $matches = array();
    preg_match('/([A-z|0-9|\.]*@[A-z|0-9|\.]*\.[A-z|0-9]{2,3})/', $email, $matches);
    if (sizeof($matches) != 0){
        return $email;
    } else {
        echo json_encode(array("status"=>"error",
                              "message"=>"Invalid email"));
        die();
    }
}

function saveSettings($email){
    global $wpdb;
    $table_name = $wpdb->prefix . "SMS_config";

    $sqlDel = "DELETE FROM `$table_name` WHERE 1";
    $sqlSave = "INSERT INTO $table_name(`from_email`) VALUES ('$email')";

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