<?php 

class ShipstationClient
{
	private Secret $secret; 

	const BASE_URL = "https://ssapi15.shipstation.com";

	public function __construct(Secret $secret)
	{
		$this->secret = $secret;
	}

	public function getResource($url)
	{
		return $this->request($url);
	}

	public function createOrUpdateOrder($order)
	{
		$url = self::BASE_URL . "/orders/createorder";
		return $this->request($url, "post", $order);
	}

	public function request(string $url, string $method = "get", array $data = []) 
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json'
		]); 
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);   
		curl_setopt($ch, CURLOPT_USERPWD, $this->secret->getValue()); 

		if ($method === "post") {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));	
		}

		$result = curl_exec($ch);
		curl_close ($ch);

		return json_decode($result, true);
	}
}