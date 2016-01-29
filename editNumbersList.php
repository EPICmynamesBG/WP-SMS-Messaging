<?php

include_once('../../../wp-blog-header.php');

global $wpdb;

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    var_dump($_POST);

    if ($_POST['type'] == "create"){
        echo "Create";
    } else if ($_POST['type'] == "delete"){
        echo "Delete";
    } else {
        die("Request type error");
    }
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
    if (strlen($phone) != 10){
        echo("Bad Phone");
        die("Phone number must be 10 digits");
    }
    $result = array("sid"=>$_POST["sid"],
                   "phone"=>$phone);
    return $result;
}

?>