<?php
	
	/**
	 * Autodesk oAuth class. Using two legged auth.
	 * 
	 * Docs: https://developer.autodesk.com/en/docs/oauth/v2/overview/ 
	 * 
	 **/
	
	namespace Autodesk;
	 
	class oAuth { 
		
		private $CLIENT_KEY, $CLIENT_SECRET, $access_token, $token_type, $expireTime, $latestErrorMesage;
		private $tmp_folder_path = 'tmp/';
		
		
		public function __construct($client_id, $client_secret){
			
			/**
			 * Constructor
			 * @parameter $client_id : app key (required)
			 * @parameter $client_secret : app secret (required)
			 **/
			
			$this->CLIENT_ID 		= $client_id;
			$this->CLIENT_SECRET	= $client_secret;
			
		}
		
		public function obtainAccessToken(){
			
			/**
			 * Obtain autodesk, try first from file
			 **/
			
			$fileToken = $this->getTokensFromFile($this->tmp_folder_path . $this->CLIENT_ID . '.json');
			
			if($fileToken){
				if($fileToken->expireTime > strtotime(date('d-m-Y H:i:s'))){ //if token is valid
					$this->access_token	=	$fileToken->access_token;
					$this->token_type	=	$fileToken->token_type;
					$this->expireTime	=	$fileToken->expireTime;
					return true;
				}
			}
			
			/**
			 * Try from Autodesk online
			 **/
			
			$connectToken =  $this->obtainAccessTokenFromAutodesk();
			
			if(is_string($connectToken)){
				$this->latestErrorMesage =  $connectToken;
				return false;
			}
			
			$this->access_token	=	$connectToken->access_token;
			$this->token_type	=	$connectToken->token_type;
			$this->expireTime	=	$connectToken->expireTime;
			
			//write to file
			return true; //skip
			file_put_contents($this->tmp_folder_path . $this->CLIENT_ID . '.json', json_encode(array(
				'access_token'	=>	$this->access_token,
				'token_type'	=>	$this->token_type,
				'expireTime'	=>	$this->expireTime
			)));
	
			return true;
		}
		
		public function getLatestErrorMessage(){
			/**
			 * Return $latestErrorMesage
			 **/
			
			return $this->latestErrorMesage;
			
		}
		
		public function getAccessToken(){
			/**
			 * Return Accestoken as string for other auth requests
			 **/
			 
			return $this->access_token;


		}
		
		private function obtainAccessTokenFromAutodesk(){
			
			/**
			 * Obtain access token from Autodesk 
			 * using curl
			 **/
			
			$url = 'https://developer.api.autodesk.com/authentication/v1/authenticate';
	
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_POST, 4);
			curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query(array(
				'client_id' 		=> 	$this->CLIENT_ID,
				'client_secret'		=> 	$this->CLIENT_SECRET,
		    	'grant_type'		=>	'client_credentials',
				'scope'				=>	'data:read data:write data:create data:write bucket:update bucket:read bucket:create viewables:read data:search'
			)));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
			$result = curl_exec($ch);
			
			curl_close($ch);
			
			$decodedResult = json_decode($result);
			
			/**
			 * Catch errors
			 **/
			
			if(!$result && $decodedResult == null){
				return 'Error while connecting with Autodesk';
			}
			if(!empty($decodedResult->developerMessage)){
				return $decodedResult->developerMessage;
			}
			
			
			/**
			 * Process result
			 **/
			
			return (object)array(
				'access_token'	=>	$decodedResult->access_token,
				'token_type'	=>	$decodedResult->token_type,
				'expireTime'	=>	$decodedResult->expires_in + strtotime(date('d-m-Y H:i:s'))
			);
			
			
		}
		
		private function getTokensFromFile($filePath){
			
			/**
			 * Read json file and set token values
			 * @parameter $filePath : string (required)
			 **/
			 
			if(!file_exists($filePath)){
				return false;
			}
			
			$decodedResult 		=	json_decode(file_get_contents($filePath));
			
			if(empty($decodedResult)){
				return false;
			}
			
			return (object)array(
				'access_token'	=>	$decodedResult->access_token,
				'token_type'	=>	$decodedResult->token_type,
				'expireTime'	=>	$decodedResult->expireTime
			);
			
		}
		
		
		
	}
	
?>