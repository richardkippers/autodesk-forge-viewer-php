<?php
	
	/**
	 * Autodesk Bucket class.
	 * Create, list or upload buckets.
	 * Docs: https://developer.autodesk.com/en/docs/data/v2/reference/http/buckets-GET/
	 * - oct 2017 Richard Kippers
	 **/
	 
	namespace Autodesk;
	
	class Buckets{
		
		private $AccessToken; //todo: rm
		private $bucketKey;
		private $items;
		
		
		public function __construct($AccessToken, $bucketKey){
			
			/**
			 * Initizlize class
			 * @parameter $AccessToken : string (required)
			 **/
			$this->AccessToken = $AccessToken;
			$this->bucketKey = $bucketKey;
		
		}
		
		public function exists(){
			
			/**
			 * Returns true if bucket exists
			 * @parameter $AccessToken : string (required)
			 * TODO: add pagination
			 **/
			
			$url = 'https://developer.api.autodesk.com/oss/v2/buckets';
			
			$headers = [
				'Accept: application/json',
				'Content-Type: application/json',
				'Authorization: Bearer ' . $this->AccessToken
			];
			
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_ENCODING, '');
			
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

			foreach($decodedResult->items as $item){
				if($item->bucketKey == $this->bucketKey){
					return true;
				}
			}
			
			return false;
			
		}
		
		public function listObjects(){
			
			/**
			 * List all objects in bucket
			 **/
			
			$url = 'https://developer.api.autodesk.com/oss/v2/buckets/' . $this->bucketKey . '/objects';

			$headers = [
				'Accept: application/json',
				'Content-Type: application/json',
				'Authorization: Bearer ' . $this->AccessToken
			];
			
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_ENCODING, '');
			
			$result = curl_exec($ch);
			
			curl_close($ch);
			
			$decodedResult = json_decode($result);
			
			/**
			 * Catch errors
			 **/
			if((!$result && $decodedResult == null) || !empty($decodedResult->developerMessage) || empty($decodedResult->items)){
				return false;	
			}
			
			return $decodedResult->items;					
			 
			
		}
		
		
		public function put($bucketKey, $authId, $access, $policyKey){
			
			/**
			 * Creates a bucket. Buckets are arbitrary spaces that are created by applications and are used to store objects for later retrieval. 
			 * A bucket is owned by the application that creates it. HTTP POST to Autodesk
			 * 
			 * @parameter $bucketKey : string (required)
			 * @parameter $authId : string (required) (The application key to grant access to)
			 * @parameter $access : string (required)
			 * @parameter $policyKey : string (required) (https://developer.autodesk.com/en/docs/data/v2/overview/retention-policy/)
			 * @output: boolean
			 **/
			 
			$url = 'https://developer.api.autodesk.com/oss/v2/buckets';

			$headers = [
				'Accept: application/json',
				'Content-Type: application/json',
				'Authorization: Bearer ' . $this->AccessToken
			];
	
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_POST, 4);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_ENCODING, '');
			curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode(array(
				'bucketKey'		=>	$bucketKey,
				'authId'		=>	$authId,
				'access'		=>	$access,
				'policyKey'		=>	$policyKey
			)));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
			$result = curl_exec($ch);
			
			curl_close($ch);
			
			$decodedResult = json_decode($result);
			
			/**
			 * Catch errors
			 **/

			if((!$result && $decodedResult == null) || !empty($decodedResult->developerMessage) || empty($decodedResult->bucketKey)){
				return false;	
			}
			
			return true;
			
		}
			

		
	}