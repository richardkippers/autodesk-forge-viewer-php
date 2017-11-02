<?php
	
	/**
	 * Autodesk Model Derivative class
	 * - oct 2017 Richard Kippers
	 **/
	 
	namespace Autodesk;
	
	class ModelDerivative{
		
		private $AccessToken; //todo: rm
		private $urnEncoded;
		
		public function __construct($AccessToken,$urnEncoded){
			$this->AccessToken 	= 	$AccessToken;	
			$this->urnEncoded	=	$urnEncoded;
		}
		
		public function designdataJob($output){
			/**
			 * Call job
			 * @parameter $input : array (required)
			 **/

			 
			$url = 'https://developer.api.autodesk.com/modelderivative/v2/designdata/job';
			
			$headers = [
				'Content-Type: application/json',
				'Authorization: Bearer ' . $this->AccessToken
			];
	
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_POST, 4);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_ENCODING, '');
			curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode(array(
				'input'		=> array('urn'	=>	$this->urnEncoded),
				'output'	=> $output
			)));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$result = curl_exec($ch);
			
			curl_close($ch);
			
			$decodedResult = json_decode($result);
			
			//TODO: handle error
			
			return $decodedResult;
			 
		}	
		
		public function getManifest(){
			/**
			 * Get model manifest for jobs
			 **/
			
			$url = 'https://developer.api.autodesk.com/modelderivative/v2/designdata/' . $this->urnEncoded . '/manifest';
			
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
			
			return $decodedResult;
			
				
		}
			
		
	}