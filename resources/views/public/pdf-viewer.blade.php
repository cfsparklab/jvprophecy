<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'PDF Viewer' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #525659;
            overflow: hidden;
        }
        
        #pdf-container {
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        #pdf-toolbar {
            background: #323639;
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
            flex-wrap: wrap;
            gap: 10px;
        }
        
        #pdf-toolbar button {
            background: #474b4f;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background 0.2s;
        }
        
        #pdf-toolbar button:hover {
            background: #5a5e62;
        }
        
        #pdf-toolbar button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        #pdf-toolbar .controls {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        #pdf-toolbar .page-info {
            font-size: 14px;
            padding: 8px 15px;
            background: #474b4f;
            border-radius: 4px;
        }
        
        #pdf-toolbar input[type="number"] {
            width: 60px;
            padding: 6px;
            border: none;
            border-radius: 4px;
            text-align: center;
            font-size: 14px;
        }
        
        #pdf-canvas-container {
            flex: 1;
            overflow: auto;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;
        }
        
        #pdf-canvas {
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            background: white;
            max-width: 100%;
            height: auto;
        }
        
        #loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 18px;
            text-align: center;
        }
        
        .spinner {
            border: 4px solid rgba(255,255,255,0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        #error-message {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #d32f2f;
            color: white;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 90%;
        }
        
        .title-bar {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .title-bar h1 {
            font-size: 16px;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 300px;
        }
        
        @media (max-width: 768px) {
            #pdf-toolbar {
                padding: 8px 10px;
            }
            
            #pdf-toolbar button {
                padding: 6px 10px;
                font-size: 12px;
            }
            
            .title-bar h1 {
                font-size: 14px;
                max-width: 150px;
            }
            
            #pdf-canvas-container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div id="pdf-container">
        <div id="pdf-toolbar">
            <div class="title-bar">
                <button onclick="window.close()" title="Close">
                    ✕
                </button>
                <h1>{{ $title ?? 'PDF Document' }}</h1>
            </div>
            
            <div class="controls">
                <button id="prev-page" title="Previous Page">
                    ◀ Prev
                </button>
                
                <div class="page-info">
                    Page <input type="number" id="page-num" min="1" value="1"> / <span id="page-count">-</span>
                </div>
                
                <button id="next-page" title="Next Page">
                    Next ▶
                </button>
                
                <button id="zoom-out" title="Zoom Out">
                    🔍−
                </button>
                
                <button id="zoom-in" title="Zoom In">
                    🔍+
                </button>
                
                <button id="download-pdf" title="Download PDF">
                    ⬇ Download
                </button>
            </div>
        </div>
        
        <div id="pdf-canvas-container">
            <canvas id="pdf-canvas"></canvas>
        </div>
        
        <div id="loading">
            <div class="spinner"></div>
            Loading PDF...
        </div>
        
        <div id="error-message">
            <h3>Failed to load PDF</h3>
            <p id="error-text"></p>
        </div>
    </div>

    <!-- PDF.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    
    <script>
        // PDF.js configuration
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        
        // PDF URL from backend
        const pdfUrl = '{{ $pdfUrl }}';
        const downloadUrl = '{{ $downloadUrl }}';
        
        let pdfDoc = null;
        let pageNum = 1;
        let pageRendering = false;
        let pageNumPending = null;
        let scale = 1.5;
        
        const canvas = document.getElementById('pdf-canvas');
        const ctx = canvas.getContext('2d');
        const loading = document.getElementById('loading');
        const errorMessage = document.getElementById('error-message');
        const errorText = document.getElementById('error-text');
        
        /**
         * Render the page
         */
        function renderPage(num) {
            pageRendering = true;
            
            pdfDoc.getPage(num).then(function(page) {
                const viewport = page.getViewport({ scale: scale });
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                
                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                
                const renderTask = page.render(renderContext);
                
                renderTask.promise.then(function() {
                    pageRendering = false;
                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
            });
            
            // Update page counters
            document.getElementById('page-num').value = num;
        }
        
        /**
         * Queue page rendering
         */
        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }
        
        /**
         * Previous page
         */
        function onPrevPage() {
            if (pageNum <= 1) {
                return;
            }
            pageNum--;
            queueRenderPage(pageNum);
            updateButtons();
        }
        document.getElementById('prev-page').addEventListener('click', onPrevPage);
        
        /**
         * Next page
         */
        function onNextPage() {
            if (pageNum >= pdfDoc.numPages) {
                return;
            }
            pageNum++;
            queueRenderPage(pageNum);
            updateButtons();
        }
        document.getElementById('next-page').addEventListener('click', onNextPage);
        
        /**
         * Go to specific page
         */
        document.getElementById('page-num').addEventListener('change', function() {
            let num = parseInt(this.value);
            if (num < 1) num = 1;
            if (num > pdfDoc.numPages) num = pdfDoc.numPages;
            pageNum = num;
            this.value = num;
            queueRenderPage(pageNum);
            updateButtons();
        });
        
        /**
         * Zoom in
         */
        document.getElementById('zoom-in').addEventListener('click', function() {
            scale += 0.25;
            if (scale > 3) scale = 3;
            queueRenderPage(pageNum);
        });
        
        /**
         * Zoom out
         */
        document.getElementById('zoom-out').addEventListener('click', function() {
            scale -= 0.25;
            if (scale < 0.5) scale = 0.5;
            queueRenderPage(pageNum);
        });
        
        /**
         * Download PDF
         */
        document.getElementById('download-pdf').addEventListener('click', function() {
            window.location.href = downloadUrl;
        });
        
        /**
         * Update button states
         */
        function updateButtons() {
            document.getElementById('prev-page').disabled = (pageNum <= 1);
            document.getElementById('next-page').disabled = (pageNum >= pdfDoc.numPages);
        }
        
        /**
         * Load and display PDF
         */
        pdfjsLib.getDocument(pdfUrl).promise.then(function(pdfDoc_) {
            pdfDoc = pdfDoc_;
            document.getElementById('page-count').textContent = pdfDoc.numPages;
            
            // Hide loading
            loading.style.display = 'none';
            
            // Initial page render
            renderPage(pageNum);
            updateButtons();
            
        }).catch(function(error) {
            console.error('Error loading PDF:', error);
            loading.style.display = 'none';
            errorMessage.style.display = 'block';
            errorText.textContent = error.message || 'Unable to load PDF file. Please try downloading instead.';
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                onPrevPage();
            } else if (e.key === 'ArrowRight') {
                onNextPage();
            }
        });
    </script>
</body>
</html>

