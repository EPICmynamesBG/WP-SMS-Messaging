<?php

class MailChimp {

    private $BASE_URL = "https://usX.api.mailchimp.com/3.0";
    private $CAMPAIGNS = "/campaign-folders";
    private $user;
    private $apiKey;

    public function __construct(){
        /* nothing to do */
    }

    public function accountInfo($user, $apiKey){
        $this->user = $user;
        $this->apiKey = $apiKey;
    }

    public function getCampaigns(){
        $ch = curl_init($this->BASE_URL. $this->CAMPAIGNS);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->user.":".$this->apiKey);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}

?>