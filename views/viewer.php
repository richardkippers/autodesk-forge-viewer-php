<!DOCTYPE HTML>
<html>
<head>
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1, user-scalable=no" />
    <meta charset="utf-8">

    <!-- The Viewer CSS -->
    <link rel="stylesheet" href="https://developer.api.autodesk.com/modelderivative/v2/viewers/style.min.css" type="text/css">

    <!-- Developer CSS -->
    <style>
        body {
            margin: 0;
        }
        #MyViewerDiv {
            width: 100%;
            height: 100%;
            margin: 0;
            background-color: #F0F8FF;
        }
    </style>
</head>
<body>

    <!-- The Viewer will be instantiated here -->
    <div id="MyViewerDiv"></div>

    <!-- The Viewer JS -->
    <script src="https://developer.api.autodesk.com/modelderivative/v2/viewers/three.min.js"></script>
    <script src="https://developer.api.autodesk.com/modelderivative/v2/viewers/viewer3D.min.js"></script>

    <!-- Developer JS -->
    <script>
        var viewer;
        var options = {
            env: 'AutodeskProduction',
            accessToken: '<?php echo $accessToken;?>'
        };
        var documentId = 'urn:<?php echo $encoded_urn;?>';
        Autodesk.Viewing.Initializer(options, function onInitialized(){
            Autodesk.Viewing.Document.load(documentId, onDocumentLoadSuccess, onDocumentLoadFailure);
        });

        /**
        * Autodesk.Viewing.Document.load() success callback.
        * Proceeds with model initialization.
        */
        function onDocumentLoadSuccess(doc) {

            // A document contains references to 3D and 2D viewables.
            var viewables = Autodesk.Viewing.Document.getSubItemsWithProperties(doc.getRootItem(), {'type':'geometry'}, true);
            if (viewables.length === 0) {
                console.error('Document contains no viewables.');
                return;
            }

            // Choose any of the avialble viewables
            var initialViewable = viewables[0];
            var svfUrl = doc.getViewablePath(initialViewable);
            var modelOptions = {
                sharedPropertyDbPath: doc.getPropertyDbPath()
            };

            var viewerDiv = document.getElementById('MyViewerDiv');
            viewer = new Autodesk.Viewing.Private.GuiViewer3D(viewerDiv);
            viewer.start(svfUrl, modelOptions, onLoadModelSuccess, onLoadModelError);
        }

        /**
         * Autodesk.Viewing.Document.load() failuire callback.
         */
        function onDocumentLoadFailure(viewerErrorCode) {
            console.error('onDocumentLoadFailure() - errorCode:' + viewerErrorCode);
        }

        /**
         * viewer.loadModel() success callback.
         * Invoked after the model's SVF has been initially loaded.
         * It may trigger before any geometry has been downloaded and displayed on-screen.
         */
        function onLoadModelSuccess(model) {
            console.log('onLoadModelSuccess()!');
            console.log('Validate model loaded: ' + (viewer.model === model));
            console.log(model);
        }

        /**
         * viewer.loadModel() failure callback.
         * Invoked when there's an error fetching the SVF file.
         */
        function onLoadModelError(viewerErrorCode) {
            console.error('onLoadModelError() - errorCode:' + viewerErrorCode);
        }

    </script>
</body>