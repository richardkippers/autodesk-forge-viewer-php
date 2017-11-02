<?php
	

	class Home extends Controller {
	
		public function index(){
			
			/**
			 * Load index page
			 **/
			 
			$this->loadView('upload');
			 
			
		}
	
	}