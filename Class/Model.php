<?php
	

	class Model {
		
		private $id, $sourceFileName, $objectKey, $objectId;
		
		private $model_path = 'models/';
		
		public function __construct($id = null){
			
			/**
			 * Constructor
			 * @parameter $id : optional
			 **/
			 
			
			if($id != null){
				//get json file
				$rawData = file_get_contents($this->model_path . $id . '.json');
				
				if(!empty($rawData)){
					$data = json_decode($rawData);
					
					$this->id 				= 	$data->id;
					$this->sourceFileName	=	$data->sourceFileName;
					$this->objectId			=	$data->objectId;
					$this->objectKey		=	$data->objectKey;
				}
				
			}	
			
		}
		
		public function setId($id){
			/**
			 * Set ID
			 * @parameter $id : string (required)
			 **/
			$this->id = $id;
			
		}
		
		public function setSourceFileName($name){
			/**
			 * Set SourceFileName
			 * @parameter $name : string (required)
			 **/
			
			$this->sourceFileName = $name;
		}
		
		public function getSourceFileName(){
			/**
			 * Return SourceFileName
			 **/
			
			return $this->SourceFileName;
		}
		
		public function setObjectKey($objectKey){
			/**
			 * Set ObjectKey
			 * @parameter $objectKey : string
			 **/
			
			$this->objectKey = $objectKey; 
		}
		
		public function setObjectId($objectId){
			/**
			 * Set ObjectId (urn)
			 * @parameter $objectKey : string
			 **/
			
			$this->objectId = $objectId; 
		}
		
		public function getObjectId(){
			/**
			 * Get ObjectId (urn)
			 **/
			
			return $this->objectId;
		}
		
		public function getId(){
			/**
			 * Return ID
			 * @ouput $id
			 **/
			return $this->id;
		}
		
		public function store(){
			/**
			 * Write $this to disk
			 **/
			
			file_put_contents($this->model_path . $this->id . '.json', json_encode(array(
				'id'				=>	$this->id,
				'sourceFileName'	=>	$this->sourceFileName,
				'objectId'			=>	$this->objectId,
				'objectKey'			=>	$this->objectKey
			)));
			
		}
		
	}
	