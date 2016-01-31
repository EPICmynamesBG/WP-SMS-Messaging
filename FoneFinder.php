<?php

class FoneFinder
{
    /*
     * Constructor
     */
    private $number;
    private $npa;
    private $nxx;
    private $thoublock;

    function __construct($num){
        $this->number = preg_replace("~[^0-9]~", "", $num);
        $this->decompose();
    }

    public function number($num){
        $this->number = preg_replace("~[^0-9]~", "", $num);
        $this->decompose();
    }

    private function decompose(){
        $this->npa = substr($this->number, 0 , 3);
        $this->nxx = substr($this->number,3,3);
        $this->thoublock = substr($this->number, 6, 4);
    }

    /*
     * Find the carrier data of a given cell number
     */
    public function queryNow()
    {
        $href = "http://www.fonefinder.net/findome.php?npa=".$this->npa."&nxx=".$this->nxx."&thoublock=".$this->thoublock."&usaquerytype=Search+by+Number";

        $contents = file_get_contents($href);
        $carrier = $this->getCarrier($contents);
        $gateway = $this->getGateway($carrier);
        $result = array
        (
            'request'   => $href,
            'number'    => $this->number,
            'carrier'   => $carrier,
            'gateway'   => $gateway,
            'email'     => $this->number . '@' . $gateway,
        );
        return $result;
    }

    function getCarrier($contents)
    {
        if (!$contents){
            return false;
        }
        foreach ($this->getFingerprints() as $fingerprint => $carrier) {
            if (strpos($contents, $fingerprint) === FALSE){
                continue;
            }
            return $carrier;
        }
        return false; // unknown carrier
    }

    private function getFingerprints()
    {
        $fingerprints = array
        (
            "<A HREF='http://fonefinder.net/verizon.php'>"  => 'verizon',
            "<A HREF='http://fonefinder.net/att.php'>"      => 'att',
            "<A HREF='http://fonefinder.net/qwest.php'>"    => 'qwest',
            "<A HREF='http://fonefinder.net/sprint.php'>"   => 'sprint',
            "<A HREF='http://fonefinder.net/tmobile.php>"   => 'tmobile',
            "<A HREF='http://fonefinder.net/alltel.php'>"   => 'alltel',
            "<A HREF='http://fonefinder.net/boostmobile.php'>"   => 'boost',
            "<A HREF='hhttp://fonefinder.net/cricket.php'>"   => 'cricket',
            "<A HREF='http://fonefinder.net/metropcs.php'>"   => 'metropcs',
        );
        return $fingerprints;
    }

    private function getGateway($carrier)
    {
        if (!$carrier) return false;
        $txt_gateways = array
        (
            'verizon' => 'vtext.com',
            'att'     => 'txt.att.net',
            'qwest'   => 'qwestmp.com',
            'sprint'  => 'messaging.sprintpcs.com',
            'tmobile'  => 'tmomail.net',
            'alltel' => 'message.alltel.com',
            'boost' => 'myboostmobile.com',
            'cricket' => 'sms.mycricket.com',
            'metropcs' => 'mymetropcs.com',
        );
        return $txt_gateways[$carrier];
    }
}