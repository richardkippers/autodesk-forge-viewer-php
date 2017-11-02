<!DOCTYPE HTML>
<html>
<head>
	<title>Autodesk Forge Uploader</title>
	
	<link href="/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	
</head>
<body>
	
	<div class="container">
		<h1>Revit model converter</h1>
		<div class="form-inline">
			
			<form id="file" class="form-group">
				<input type="file" name="file" style="display:none;">
				<a href="#" class="file-upload btn btn-default">Upload RVT file</a>
			</form>
			
			or
			
			<div class="form-group">
				<input type="text" class="form-control" data-name="model_id" value="" placeholder="Model ID" />
			</div>
			
			<a href="#" class="btn btn-default process">Process</a>
			<a href="#" class="btn btn-default checkState">Check state</a>
			<a href="#" class="btn btn-default openViewer">Open viewer</a>
		</div>
		
		<br><br>
		
		<pre id="log"><strong>Status</strong></pre>    
       	    
	</div>

	<script src="/bower_components/jquery/dist/jquery.min.js"></script>
	<script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	
	<script>
		
		$(document).ready(function(rd){
			
			/**
			 * Triggers 
			 **/
			 
			$(document).on('click', '.file-upload', function(e){
				e.preventDefault();
				
				$('form#file input[name="file"]').click();
			});
			
			$(document).on('change', 'form#file input[name="file"]', function(){
				startUpload();
			});
			
			$(document).on('click', '.process', function(e){
				e.preventDefault();
				callModelProcess();
			});
			
			$(document).on('click', '.checkState', function(e){
				e.preventDefault();
				callModelCheckState();
			});
			
			$(document).on('click', '.openViewer', function(e){
				e.preventDefault();
				window.location = '/view/?id=' + $('[data-name="model_id"]').val();
			});
			
			/**
			 * Functions
			 **/
			
			function startUpload(){	
				
				//start upload
				
				writeLog('Upload started, this may take a while');
				
				var formData = new FormData($('form#file')[0]);
				
				var xhr = new XMLHttpRequest();
				
				xhr.addEventListener('error', function(){
					alert('Error while uploading');
				}, false);
				
				xhr.addEventListener('load', function(response, ){
					
					//try json decoding
					
					try{
						
						var data = JSON.parse(response.target.response);
						
						console.log(data);
						
						if(data.status == 'success'){
							
							writeLog('Upload success');
							
							$('[data-name="model_id"]').val(data.modelId);
							
							writeLog('Model ID: ' + data.modelId);
							
						}
						
					} catch(err){
						
						 writeLog('Upload error: ' +  response.target.responseText);
						
					}
					
					console.log(response);
					
					var data = response.target.response;
					
				}, false);
				
				
				xhr.open('POST', '/process/upload', true);
				xhr.send(formData);
				
			}
		
			
			function callModelProcess(){
				
				//call designdatatosvf job
				
				var modelId = $('[data-name="model_id"]').val();
				
				console.log('call for: ' + modelId);
				
				var url = '/process/designDataToSvf?id=' + modelId;
				console.log(url);
				
				$.ajax({
					url: url,
					method: 'GET',
					dataType: 'json',
					success: function(data){
						
						writeLog('Model convert check status');
						callModelCheckState();
						
					}, error: function(e1){
						console.log(e1);
					}
				});
					
				
			}
			
			function callModelCheckState(){
				
				//check for job state.
				
				var modelId = $('[data-name="model_id"]').val();
				
				console.log('call for: ' + modelId);
				
				var url = '/process/checkDesignToSvfState?id=' + modelId;
				console.log(url);
				
				writeLog('Model convert check status');
				
				$.ajax({
					url: url,
					method: 'GET',
					dataType: 'json',
					success: function(data){
						console.log('success');
						if(data.progress == 'complete'){
							writeLog('Model convert complete');
						} else { 
							console.log(data);
							callModelCheckState(); //try again
						}
					}, error: function(e1){
						console.log(e1);
					}
				});
				
			}
			
			function writeLog(line){
				
				/**
				 * Add string to log
				 **/
				
				$('#log').append('<br>' + line);
				
			}
		
		
		});
		
	</script>
	
</body>
</html>
