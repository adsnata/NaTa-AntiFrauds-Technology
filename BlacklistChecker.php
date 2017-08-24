<?php
namespace IPQualityScore;

class BlacklistChecker {
	private $key = null;
	private $strictness = 0;
	private $user_agent = false;
	private $failure_redirect = null;
	private $success_redirect = null;
	const BASE_API_URL = 'https://ipqualityscore.adsnata.com/api/json/ip/%s/%s';
	
	const TYPE_PROXY = 'proxy';
	const TYPE_VPN = 'vpn';
	const TYPE_TOR = 'tor';
	
	public function SetKey($key = null){
		$this->key = $key;
	}
	
	public function SetStrictness($value = 0){
		$this->strictness = $value;
	}
	
	public function PassUserAgent($value = false){
		$this->user_agent = $value;
	}
	
	public function SetFailureRedirect($value = null){
		$this->failure_redirect = $value;
	}
	
	public function SetSuccessRedirect($value = null){
		$this->success_redirect = $value;
	}

	public function Precheck(){
		if($this->key === null){
			throw new InvalidParameter('No key was passed to BlacklistChecker. Aborting.');
		}
		
		if(!is_numeric($this->strictness)){
			throw new InvalidParameter('Invalid strictness was passed to BlacklistChecker. Aborting.');
		}
		
		if(!is_bool($this->user_agent)){
			throw new InvalidParameter('Invalid pass user agent flag was passed to BlacklistChecker (must be bool). Aborting.');
		}
		
		return true;
	}

	public function CheckIP($ip){
		if($this->Precheck()){
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, sprintf(static::BASE_API_URL, $this->key, urlencode($ip)));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);

			$parameters = array('strictness' => $this->strictness);
			if($this->user_agent && isset($_SERVER['HTTP_USER_AGENT'])){
				$parameters['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
			}

			curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
			$result = curl_exec($curl);
			$data = json_decode($result, true);
			curl_close($curl);
			
			if($data === false){
				die(print_r($result, true));
			} else {
				return $data;
			}
		}
	}
	
	/*
	* If the result of $type is true redirect the user to the failure URL if there is one or exit if not.
	* If the result of $type is false redirect the user to the success URL if there is one or continue execution.
	*/
	public function ForceRedirect($type = 'proxy'){
		$result = $this->CheckIP($this->GetIP());
		if(isset($result[$type])){
			if($result[$type] === true){
				if($this->failure_redirect !== null){
					exit(header(sprintf("Location: %s", $this->failure_redirect)));
				} else {
					exit;	
				}
			} else {
				if($this->success_redirect !== null){
					exit(header(sprintf("Location: %s", $this->success_redirect)));
				}
			}
		} else {
			if(isset($result['errors'])){
				throw new IPQException(implode(' - ', $result['errors']));
			}
			
			throw new IPQException('Force redirect check failed.');
		}
	}
	
	private static function GetIP(){
		return (isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ? $_SERVER["HTTP_CF_CONNECTING_IP"] : $_SERVER["REMOTE_ADDR"]);
	}
}

class IPQException extends \Exception {}
class InvalidParameter extends IPQException {}