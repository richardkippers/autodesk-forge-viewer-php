<?php

	class Process extends Controller {
		
		private $auth;
		
		public function __construct(){
			
			$this->auth = new Autodesk\oAuth(AUTODESK_CLIENT_ID, AUTODESK_CLIENT_SECRET);
			
			if(!$this->auth->obtainAccessToken()){
				die('<strong>Autodesk auth error:</strong> ' . $this->auth->getLatestErrorMessage());
			}
			
		}
	
		public function upload(){
			
			/**
			 * Upload to autodesk
			 **/
			
			if(empty($_FILES['file'])){
				print_r($_FILES);
				die('No file uploaded');
			}
			 
			move_uploaded_file($_FILES['file']['tmp_name'],'tmp/' . $_FILES['file']['name']);
			
			$bucket = new Autodesk\Buckets($this->auth->getAccessToken(), BUCKET_NAME);
			
			if(!$bucket->exists()){
				if(!$bucket->put(BUCKET_NAME, AUTODESK_CLIENT_ID, 'full', 'transient')){
					die('Error while creating bucket');
				}
			}
			
			//upload file to bucket
			$fileH = new Autodesk\File($this->auth->getAccessToken());
			$file = $fileH->putFile(BUCKET_NAME, $_FILES['file']['name'], 'tmp/' . $_FILES['file']['name']);
			
			//remove file from disk
			unlink('tmp/' . $_FILES['file']['name']);
			
			/**
			 * Create new model
			 **/
			
			header('Content-Type: application/json');
			
			
			$model = new Model();
			
			$model->setObjectKey($file->objectKey);
			$model->setObjectId($file->objectId);
			
			
			$model->setId(base64_encode($_FILES['file']['name'] . date('d-m-Y_H:i:s')));
			$model->setSourceFileName($_FILES['file']['name']);
			$model->store();
			
			echo json_encode(array('status'=>'success', 'modelId' => $model->getId()));			
			
		}
		
		public function designDataToSvf(){
			
			/**
			 * Call modelderivative api for designdata job ToSvf
			 * docs: https://developer.autodesk.com/en/docs/model-derivative/v2/tutorials/prepare-file-for-viewer/
			 * 
			 * @parameter $_GET['id'] : string (required)
			 **/

			
			if(!isset($_GET['id'])){
				die('ID not set');
			}
			
			$model = new Model($_GET['id']);
			
			$encoded_urn = preg_replace('/[^a-zA-Z0-9]+/', '', base64_encode($model->getObjectId()));
	
			$ModelDerivative = new Autodesk\ModelDerivative($this->auth->getAccessToken(), $encoded_urn);
			
			$formats = [];
			$formats[] = array(
				'type'	=>	'svf',
				'views'	=>	array('3d', '2d')
			);
		
			$job = $ModelDerivative->designdataJob(array('formats' => $formats));
			
			header('Content-Type: application/json');
			
			if($job){
				echo json_encode(array('status'=>'success', 'response'=>$job));
			} else { 
				echo json_encode(array('status'=>'error'));
			}
			
			 
		}
		
		public function checkDesignToSvfState(){
			/**
			 * Call modelderivative for status from ToSvf job
			 *
			 * @parameter $_GET['id'] : string (required)
			 **/
			
			
			if(!isset($_GET['id'])){
				die('ID not set');
			}
			
			$model = new Model($_GET['id']);
			
			$encoded_urn = preg_replace('/[^a-zA-Z0-9]+/', '', base64_encode($model->getObjectId()));
			
			$ModelDerivative = new Autodesk\ModelDerivative($this->auth->getAccessToken(), $encoded_urn);
			
			$manifest = $ModelDerivative->getManifest();
			
			header('Content-Type: application/json');
			
			echo json_encode($manifest);
			
		}
		
	
	}
	
