<?php

include_once('../../../wp-load.php');

global $wpdb;

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    if ($_POST['type'] == "create"){
        $data = verifyData();
        create($data);
    } else if ($_POST['type'] == "delete"){
        delete();
    } else {
        echo json_encode(array("status"=>"error",
                              "message"=>"Invalid request type."));
        die("Request type error");
    }
} else {
    echo json_encode(array("status"=>"error",
                              "message"=>"Bad HTTP method"));
    die("Incorret HTTP Method");
}

function verifyData(){

    $phone = $_POST["phone"];
    $phone = str_replace("(", "", $phone);
    $phone = str_replace(")", "", $phone);
    $phone = str_replace("-", "", $phone);
    $phone = str_replace("+", "", $phone);
    $phone = str_replace(" ", "", $phone);
    if (strlen($phone) != 10){
        echo json_encode(array("status"=>"error",
                              "message"=>"Phone is not 10 digits"));
        die("Phone number must be 10 digits");
    }
    $result = array("name"=>$_POST["name"],
                   "phone"=>$phone);
    return $result;
}

function create($data){
    global $wpdb;
    $table_name = $wpdb->prefix . "SMS";
    $phone = $data['phone'];
    $name = $data['name'];
    $sqlCheck = "SELECT * FROM `$table_name` WHERE `phone_number`='$phone';";
    $result = $wpdb->query($sqlCheck);
    if ($result != 0){
        echo json_encode(array("status"=>"error",
                              "message"=>"Number already exists"));
    } else {
         $sql = "INSERT INTO $table_name(`id`, `name`, `phone_number`) VALUES (DEFAULT,'$name','$phone')";
        $result = $wpdb->query($sql);
        if ($result == 1){
            echo json_encode(array("status"=>"success"));
        } else {
            echo json_encode(array("status"=>"error",
                                  "message"=>"Query execution error"));
        }
    }

}

function delete(){

//    if ($result2 == 1){
//        echo json_encode(array("status"=>"success"));
//    } else {
//        echo json_encode(array("status"=>"error",
//                              "message"=>"Query execution error"));
//    }
    echo "Delete";
}

?>