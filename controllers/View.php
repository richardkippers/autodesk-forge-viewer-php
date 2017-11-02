<?php
	

	class View extends Controller {
	
		public function index(){
			
			/**
			 * Load index page
			 **/
			
			if(!isset($_GET['id'])){
				die('ID not set');
			}
			
			$model = new Model($_GET['id']);
			
			$auth = new Autodesk\oAuth(AUTODESK_CLIENT_ID, AUTODESK_CLIENT_SECRET);
			
			if(!$auth->obtainAccessToken()){
				die('<strong>Autodesk auth error:</strong> ' . $this->auth->getLatestErrorMessage());
			}
			
			$encoded_urn = preg_replace('/[^a-zA-Z0-9]+/', '', base64_encode($model->getObjectId()));
			
			$this->loadView('viewer', array(
				'accessToken'	=>	$auth->getAccessToken(),
				'encoded_urn'	=>	$encoded_urn
			));
			 
			
		}
	
	}