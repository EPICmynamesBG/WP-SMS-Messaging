<?php

include_once('../../../wp-load.php');
include('./SMSLookup.php');

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
        die();
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    getCurrentList();
} else {
    echo json_encode(array("status"=>"error",
                              "message"=>"Bad HTTP method"));
    die();
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
        die();
    }
    $result = array("name"=>$_POST["name"],
                   "phone"=>$phone,
                   "carrier"=>$_POST["carrier"]);
    return $result;
}

function create($data){
    global $wpdb;
    $table_name = $wpdb->prefix . "SMS";
    $phone = $data['phone'];
    $name = $data['name'];
    $carrier = $data['carrier'];
    $lookup = new SMSLookup();
    $lookup->number($phone);
    if ($carrier == null || $carrier == "" || $carrier == "None"){
        $result = $lookup->lookup();
        $carrier = $result['carrier'];
        $smsEmail = $result['email'];
    } else {
        $smsEmail = $lookup->getGateway($carrier);
    }

    $sqlCheck = "SELECT * FROM `$table_name` WHERE `phone_number`='$phone';";

    $result = $wpdb->query($sqlCheck);
    if ($result != 0){
        echo json_encode(array("status"=>"error",
                              "message"=>"Number already exists"));
    } else {
         $sql = "INSERT INTO $table_name(`id`, `name`, `phone_number`, `carrier`, `sms_email`) VALUES (DEFAULT,'$name','$phone','$carrier','$smsEmail')";
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
    global $wpdb;
    $table_name = $wpdb->prefix . "SMS";
    $ids = $_POST['ids'];
    $len = count($ids);
    $builder = "";
    for ($i = 0; $i < $len; $i++){
        $id = $ids[$i];
        $builder = $builder . "`id`='$id'";
        if ($i != ($len - 1)){
            $builder = $builder . " OR ";
        }
    }
    $sql = "DELETE FROM `wp_SMS` WHERE $builder;";
    $result = $wpdb->query($sql);
    if ($result >= 1){
        echo json_encode(array("status"=>"success"));
    } else {
        echo json_encode(array("status"=>"error",
                              "message"=>"Query execution error. Unable to delete numbers."));
    }
}

function getCurrentList(){
        global $wpdb;
        $table = $wpdb->prefix . "SMS";

        $sql = "SELECT * FROM $table;";
        $result = $wpdb->get_results($sql);
        $returnArr = array();
        if (count($result) == 0){
            array_push($returnArr, "<li>Looks like there's no one here yet. Add someone above!</li>");
        } else {
            foreach($result as $value) {
                $name = $value->name;
                $phone = $value->phone_number;
                $id = $value->id;
                $carrier = $value->carrier;
                $pArr = str_split($phone);
                $phone = "(".$pArr[0].$pArr[1].$pArr[2].")-".$pArr[3].$pArr[4].$pArr[5]."-".$pArr[6].$pArr[7].$pArr[8].$pArr[9];

                array_push($returnArr, '<li><input type="checkbox" name="checked" form="numbers_form" value="'.$id.'" />'.$name.': '.$phone.' ('.$carrier.')</li>');
            }
        }
        echo json_encode($returnArr);
    }

?>