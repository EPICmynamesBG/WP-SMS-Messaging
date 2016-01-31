<?php
class TextBeltSMS
{
	const TEXTBELT_URL = 'http://textbelt.com/text';

	private $message;
    private $to;

	public function __construct()
	{
		$this->message = "";
        $this->to = "";
	}

    public function to($str){
        $this->to = $str;
    }

    public function message($str){
        $this->message = $str;
    }

	public function send()
	{
		$message = http_build_query([
			'number' => $this->to,
		    'message' => $this->message
		]);
        var_dump($this);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::TEXTBELT_URL );
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec ($ch);
		curl_close ($ch);
		return $response;
	}
}