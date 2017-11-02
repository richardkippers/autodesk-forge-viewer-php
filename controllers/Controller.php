<?php
	
	/**
	 * Parent class for controllers
	 **/
	
	class Controller {
	
		protected function loadView($fileName, $variables = null){
			
			/**
			 * Include template file
			 * @parameter $fileName : string (required)
			 * @parameter $variables : array (optional)
			 **/
			
			if($variables != null){
				
				foreach($variables as $key=>$value){
					${$key}	=	$value;
				}
				
			}
			
			include('views/' . $fileName . '.php');
		}
	
	}