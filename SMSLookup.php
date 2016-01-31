<?php

include('./FoneFinder.php');

class SMSLookup
{
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

	private $cc;
    private $number;

	public function __construct()
	{
		$this->cc = "1";
        $this->number = "";
	}

    public function cc($str){
        $this->cc = $str;
    }

    public function number($str){
        $this->number = $str;
    }

	public function lookup()
	{
		$ff = new FoneFinder($this->number);
        $result = $ff->queryNow();
		return $result;
	}

    public function getGateway($carrier){
        if (self::gateways[$carrier] != null){
            $gate = self::gateways[$carrier];
            return $this->number."@".$gate;
        } else {
            return NULL;
        }
    }
}