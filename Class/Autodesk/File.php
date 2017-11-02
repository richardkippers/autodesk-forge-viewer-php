<?php
	
	/**
	 * Autodesk Model Derivative class
	 * - oct 2017 Richard Kippers
	 **/
	 
	namespace Autodesk;
	
	class File{
		
		private $AccessToken; //todo: rm
		
		public function __construct($AccessToken){
			$this->AccessToken = $AccessToken;	
		}
		
		public function putFile($bucketKey, $objectName, $filePath){
			/**
			 *	Upload an object. 
			 *	If the specified object name already exists in the bucket, 
			 *	the uploaded content will overwrite the existing content for the bucket name/object name combination.
			 
			 * @parameter $bucketKey : string (required)
			 * @parameter $objectName : string (required)
			 * @parameter $filePath : string (required)
			 * @output : (object)$file
			 **/
			 
			$url = 'https://developer.api.autodesk.com/oss/v2/buckets/' . $bucketKey . '/objects/' . $objectName;
			
			$fh_res = fopen($filePath, 'r');

			
			$headers = [
				'Content-Length: ' . filesize($filePath),
				'Authorization: Bearer ' . $this->AccessToken
			];
	
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_PUT, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_ENCODING, '');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			curl_setopt($ch, CURLOPT_INFILE, $fh_res);
			curl_setopt($ch, CURLOPT_INFILESIZE, filesize($filePath));
			
			$result = curl_exec($ch);
			
			curl_close($ch);
			fclose($fh_res);
			
			$decodedResult = json_decode($result);
			
			/**
			 * Catch errors
			 **/
			
			if((!$result && $decodedResult == null) || !empty($decodedResult->developerMessage) || empty($decodedResult->objectId)){
				return false;	
			}
			
			return $decodedResult;
				
				
		}
		
	}
	
	