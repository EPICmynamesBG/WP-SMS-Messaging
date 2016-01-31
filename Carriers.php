<?php

class Carriers {
    const gateways = array
        (
            'Verizon' => 'vtext.com',
            'ATT'     => 'txt.att.net',
            'Qwest'   => 'qwestmp.com',
            'Sprint'  => 'messaging.sprintpcs.com',
            'T-Mobile'  => 'tmomail.net',
            'Alltel' => 'message.alltel.com',
            'Boost' => 'myboostmobile.com',
            'Cricket' => 'sms.mycricket.com',
            'MetroPCS' => 'mymetropcs.com',
        );

    public function __construct(){
        /* nothing */
    }

    public function getJSON(){
        return json_encode(self::gateways);
    }

}


if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    $car = new Carriers;
    echo $car->getJSON();
} else {
    echo json_encode(array("status"=>"error",
                              "message"=>"Bad HTTP method"));
    die();
}

?>