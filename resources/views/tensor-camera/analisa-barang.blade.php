@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Analisa Asset IT</h1>
    
    <div class="row">
        <!-- Camera/Upload Section -->
        <div class="mb-4 col-md-6">
            <div class="card">
                <div class="text-white card-header bg-primary">
                    <h5 class="mb-0 card-title">Input Image</h5>
                </div>
                <div class="card-body">
                    <!-- Tab Navigation -->
                    <ul class="mb-3 nav nav-tabs" id="inputMethodTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="camera-tab" data-bs-toggle="tab" data-bs-target="#camera-content" type="button" role="tab" aria-controls="camera-content" aria-selected="true">
                                <i class="bi bi-camera me-1"></i> Camera
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload-content" type="button" role="tab" aria-controls="upload-content" aria-selected="false">
                                <i class="bi bi-upload me-1"></i> Upload Image
                            </button>
                        </li>
                    </ul>
                    
                    <!-- Tab Content -->
                    <div class="tab-content" id="inputMethodTabContent">
                        <!-- Camera Tab -->
                        <div class="tab-pane fade show active" id="camera-content" role="tabpanel" aria-labelledby="camera-tab">
                            <div class="position-relative">
                                <video id="webcam" class="rounded w-100" style="height: 300px; background-color: #000;" autoplay playsinline></video>
                                <canvas id="canvas" style="display: none;"></canvas>
                                <div id="cameraErrorMessage" class="mt-2 alert alert-warning d-none">
                                    <i class="bi bi-exclamation-triangle me-2"></i> 
                                    Camera access failed. Please try uploading an image instead.
                                </div>
                            </div>
                            <div class="mt-3 text-center">
                                <button id="captureBtn" class="btn btn-primary">
                                    <i class="bi bi-camera me-2"></i>Capture Photo
                                </button>
                            </div>
                        </div>
                        
                        <!-- Upload Tab -->
                        <div class="tab-pane fade" id="upload-content" role="tabpanel" aria-labelledby="upload-tab">
                            <div class="mb-3">
                                <label for="imageUpload" class="form-label">Select an image file</label>
                                <input type="file" class="form-control" id="imageUpload" accept="image/*">
                                <div class="form-text">Supported formats: JPG, PNG, GIF</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Loading Indicator -->
                    <div id="loadingIndicator" class="top-0 position-absolute start-0 w-100 h-100 d-none" 
                         style="background-color: rgba(0,0,0,0.5); border-radius: 0.25rem; z-index: 1000;">
                        <div class="text-center text-white position-absolute top-50 start-50 translate-middle">
                            <div class="spinner-border" role="status"></div>
                            <p class="mt-2">Processing image...</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h6>Source Image:</h6>
                        <div class="p-2 border rounded d-flex justify-content-center align-items-center bg-light" style="height: 200px;">
                            <p id="noImageText" class="m-0 text-muted">No image selected yet</p>
                            <img id="sourceImage" class="mh-100 mw-100" style="display: none;" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="col-md-6">
            <div class="card">
                <div class="text-white card-header bg-primary">
                    <h5 class="mb-0 card-title">Product Details</h5>
                </div>
                <div class="card-body">
                    <form id="productForm">
                        <div class="mb-3">
                            <label for="productName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="productName" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="productCategory" class="form-label">Category</label>
                            <input type="text" class="form-control" id="productCategory" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="productPrice" class="form-label">Price</label>
                            <input type="text" class="form-control" id="productPrice" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="productDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="productDescription" rows="4" readonly></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="matchConfidence" class="form-label">Match Confidence</label>
                            <div class="progress">
                                <div id="confidenceBar" class="progress-bar bg-success" role="progressbar" style="width: 0%" 
                                     aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span id="confidenceValue" class="mt-1 small d-inline-block">0%</span>
                        </div>
                        <div class="mt-4 text-center">
                            <button type="button" id="resetBtn" class="btn btn-secondary">
                                Reset Form
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- For debugging -->
    <div class="mt-4 row" id="debugSection" style="display: none;">
        <div class="col-12">
            <div class="card">
                <div class="text-white card-header bg-info">
                    <h5 class="mb-0 card-title">Debug Information</h5>
                </div>
                <div class="card-body">
                    <pre id="debugInfo" class="p-3 bg-light" style="max-height: 300px; overflow-y: auto;"></pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- TensorFlow.js -->
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@3.9.0/dist/tf.min.js"></script>
<!-- MobileNet model -->
<script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/mobilenet@2.1.0/dist/mobilenet.min.js"></script>

<script>
    // Configuration
    const USE_BACKEND_FOR_VISION_API = true; // Set to true to use backend proxy for Vision API
    const VISION_API_PROXY_URL = '/api/vision-analyze'; // Your backend endpoint for the Vision API
    const ENABLE_DEBUG = false; // Set to true for additional debug info
    
    // Predefined product database
    const productDatabase = [
        {
            id: 1,
            name: "Smartphone XYZ",
            category: "Electronics",
            price: "Rp 2.500.000",
            description: "Latest model smartphone with 6.5-inch display, 128GB storage, and triple camera setup.",
            keywords: ["phone", "mobile phone", "cell phone", "smartphone", "cellphone", "telephone", "mobile device", "handheld device"]
        },
        {
            id: 2,
            name: "Classic Notebook",
            category: "Stationery",
            price: "Rp 45.000",
            description: "A5 size notebook with 200 pages, hardcover, and bookmark ribbon.",
            keywords: ["notebook", "book", "notepad", "diary", "journal", "stationery", "paper product"]
        },
        {
            id: 3,
            name: "Wireless Headphones",
            category: "Audio",
            price: "Rp 750.000",
            description: "Over-ear wireless headphones with noise cancellation and 20-hour battery life.",
            keywords: ["headphone", "headphones", "headset", "earphone", "earphones", "audio device", "audio equipment"]
        },
        {
            id: 4,
            name: "Office Chair",
            category: "Furniture",
            price: "Rp 1.200.000",
            description: "Ergonomic office chair with adjustable height, armrests, and lumbar support.",
            keywords: ["chair", "seat", "office chair", "computer chair", "desk chair", "furniture"]
        },
        {
            id: 5,
            name: "Coffee Mug",
            category: "Kitchenware",
            price: "Rp 35.000",
            description: "Ceramic coffee mug with 350ml capacity and heat-resistant handle.",
            keywords: ["mug", "cup", "coffee cup", "coffee mug", "tea cup", "drinkware", "kitchenware"]
        },
        {
            id: 6,
            name: "Desktop Computer",
            category: "Electronics",
            price: "Rp 8.500.000",
            description: "High-performance desktop computer with Intel i7 processor, 16GB RAM, and 512GB SSD.",
            keywords: ["computer", "desktop", "pc", "desktop computer", "personal computer", "workstation"]
        },
        {
            id: 7,
            name: "Office Keyboard",
            category: "Computer Accessories",
            price: "Rp 350.000",
            description: "Ergonomic wired keyboard with multimedia keys and palm rest.",
            keywords: ["keyboard", "computer keyboard", "typing device", "input device", "peripheral"]
        },
        {
            id: 8,
            name: "Computer Mouse",
            category: "Computer Accessories",
            price: "Rp 175.000",
            description: "Wireless optical mouse with adjustable DPI and ergonomic design.",
            keywords: ["mouse", "computer mouse", "pointing device", "wireless mouse", "input device", "peripheral"]
        },
        {
            id: 9,
            name: "LCD Monitor",
            category: "Electronics",
            price: "Rp 1.800.000",
            description: "24-inch LCD monitor with Full HD resolution and adjustable stand.",
            keywords: ["monitor", "screen", "display", "computer monitor", "lcd", "led display", "computer screen"]
        },
        {
            id: 10,
            name: "Network Router",
            category: "Networking",
            price: "Rp 450.000",
            description: "Dual-band wireless router with Gigabit Ethernet ports and parental controls.",
            keywords: ["router", "wifi router", "network device", "wireless router", "networking equipment"]
        }
    ];

    // Variables for camera and model
    let webcam;
    let model;
    let isModelLoading = false;

    // DOM elements
    const webcamElement = document.getElementById('webcam');
    const canvasElement = document.getElementById('canvas');
    const captureBtn = document.getElementById('captureBtn');
    const resetBtn = document.getElementById('resetBtn');
    const sourceImage = document.getElementById('sourceImage');
    const noImageText = document.getElementById('noImageText');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const cameraErrorMessage = document.getElementById('cameraErrorMessage');
    const imageUpload = document.getElementById('imageUpload');
    const debugSection = document.getElementById('debugSection');
    const debugInfo = document.getElementById('debugInfo');
    
    // Initialize debugging
    if (ENABLE_DEBUG) {
        debugSection.style.display = 'block';
    }
    
    function logDebug(message, data) {
        if (ENABLE_DEBUG) {
            let output = typeof message === 'string' ? message : JSON.stringify(message, null, 2);
            
            if (data) {
                output += "\n" + JSON.stringify(data, null, 2);
            }
            
            debugInfo.textContent += output + "\n\n";
            debugInfo.scrollTop = debugInfo.scrollHeight;
        }
        console.log(message, data || '');
    }
    
    // Initialize the application
    async function init() {
        try {
            // Load MobileNet model (as fallback)
            loadingIndicator.classList.remove('d-none');
            captureBtn.disabled = true;
            
            logDebug('Loading MobileNet model...');
            model = await mobilenet.load();
            
            logDebug('Model loaded successfully');
            loadingIndicator.classList.add('d-none');
            
            // Then try to initialize webcam
            await initWebcam();
        } catch (error) {
            logDebug('Error loading model:', error);
            loadingIndicator.classList.add('d-none');
            alert('Failed to load the image recognition model. Please check your internet connection and try again.');
        }
    }
    
    // Initialize webcam
    async function initWebcam() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'environment' },
                audio: false
            });
            webcamElement.srcObject = stream;
            webcam = stream;
            captureBtn.disabled = false;
        } catch (error) {
            logDebug('Error initializing camera:', error);
            cameraErrorMessage.classList.remove('d-none');
            // Automatically switch to upload tab if camera fails
            document.getElementById('upload-tab').click();
        }
    }

    // Capture image from webcam
    function captureImage() {
        if (!webcam) {
            alert('Camera not available. Please try uploading an image instead.');
            document.getElementById('upload-tab').click();
            return;
        }
        
        const context = canvasElement.getContext('2d');
        const width = webcamElement.videoWidth;
        const height = webcamElement.videoHeight;
        
        if (!width || !height) {
            logDebug('Video dimensions not available');
            alert('Could not capture image. Please try again or upload an image instead.');
            return;
        }
        
        // Set canvas dimensions to match video
        canvasElement.width = width;
        canvasElement.height = height;
        
        try {
            // Draw video frame to canvas
            context.drawImage(webcamElement, 0, 0, width, height);
            
            // Display captured image
            const imageDataURL = canvasElement.toDataURL('image/jpeg', 0.8);
            sourceImage.src = imageDataURL;
            sourceImage.style.display = 'block';
            noImageText.style.display = 'none';
            
            // Process the captured image
            processImage(imageDataURL);
        } catch (error) {
            logDebug('Error capturing image:', error);
            alert('Failed to capture image. Please try again or upload an image instead.');
        }
    }
    
    // Handle file upload
    function handleImageUpload(event) {
        const file = event.target.files[0];
        if (!file) return;
        
        if (!file.type.match('image.*')) {
            alert('Please select an image file');
            return;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            sourceImage.src = e.target.result;
            sourceImage.onload = function() {
                sourceImage.style.display = 'block';
                noImageText.style.display = 'none';
                
                // Process the uploaded image
                processImage(e.target.result);
            };
        };
        
        reader.readAsDataURL(file);
    }
    
    // Process image - decide whether to use backend or direct API call
    function processImage(imageDataUrl) {
        if (USE_BACKEND_FOR_VISION_API) {
            processImageWithBackend(imageDataUrl);
        } else {
            processImageWithTensorFlow(sourceImage);
        }
    }

    // Process image using backend proxy for Google Vision API
    async function processImageWithBackend(imageDataUrl) {
        loadingIndicator.classList.remove('d-none');
        
        try {
            // Extract base64 data from the data URL (remove the prefix)
            const base64Image = imageDataUrl.split(',')[1];
            
            // Prepare request to backend
            const requestBody = {
                image: base64Image
            };
            
            logDebug('Sending image to backend for Vision API processing...');
            
            // Make API request to your Laravel backend
            const response = await fetch(VISION_API_PROXY_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(requestBody)
            });
            
            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`Backend API error: ${response.status} ${errorText}`);
            }
            
            const data = await response.json();
            logDebug('Vision API response from backend:', data);
            
            if (!data || data.error) {
                throw new Error(data.error || 'Empty response from backend');
            }
            
            // Extract labels, objects, and text from response
            const labels = data.labels || [];
            const objects = data.objects || [];
            const textAnnotations = data.text || [];
            
            // Combine all detected features for product matching
            const detectedFeatures = [
                ...labels.map(label => ({ 
                    name: label.description.toLowerCase(), 
                    score: label.score 
                })),
                ...objects.map(obj => ({ 
                    name: obj.name.toLowerCase(), 
                    score: obj.score 
                }))
            ];
            
            // Add any detected text that might be relevant (e.g., brand names)
            if (textAnnotations.length > 0) {
                const allText = textAnnotations[0].description.toLowerCase();
                const textWords = allText.split(/\s+/).filter(word => word.length > 3);
                textWords.forEach(word => {
                    detectedFeatures.push({ 
                        name: word, 
                        score: 0.7  // Assign a default confidence score
                    });
                });
            }
            
            logDebug('Combined detected features:', detectedFeatures);
            
            // Find matching product
            const matchedProduct = findMatchingProduct(detectedFeatures);
            
            // Update form with product details
            updateProductForm(matchedProduct, detectedFeatures);
            
        } catch (error) {
            logDebug('Error with backend Vision API:', error);
            
            // Fall back to TensorFlow.js model
            alert('Backend Vision API processing failed. Falling back to local image recognition.');
            processImageWithTensorFlow(sourceImage);
        } finally {
            loadingIndicator.classList.add('d-none');
        }
    }
    
    // Process image using TensorFlow.js (fallback method)
    async function processImageWithTensorFlow(imgElement) {
        if (!model) {
            logDebug('Model not loaded yet');
            return;
        }
        
        loadingIndicator.classList.remove('d-none');
        
        try {
            // Classify the image
            const predictions = await model.classify(imgElement);
            logDebug('TensorFlow predictions:', predictions);
            
            // Convert TensorFlow predictions to our standard format
            const detectedFeatures = predictions.map(p => ({
                name: p.className.toLowerCase(),
                score: p.probability
            }));
            
            // Find matching product
            const matchedProduct = findMatchingProduct(detectedFeatures);
            
            // Update form with product details
            updateProductForm(matchedProduct, detectedFeatures);
        } catch (error) {
            logDebug('Error processing image with TensorFlow:', error);
            alert('Failed to process image. Please try with a different image.');
        } finally {
            loadingIndicator.classList.add('d-none');
        }
    }

    // Find matching product from detected features
    function findMatchingProduct(detectedFeatures) {
        // Score each product based on keyword matches
        const productScores = productDatabase.map(product => {
            let totalScore = 0;
            let bestMatchScore = 0;
            let matchCount = 0;
            
            // Check each detected feature against product keywords
            for (const feature of detectedFeatures) {
                for (const keyword of product.keywords) {
                    if (feature.name.includes(keyword) || keyword.includes(feature.name)) {
                        const matchScore = feature.score;
                        totalScore += matchScore;
                        bestMatchScore = Math.max(bestMatchScore, matchScore);
                        matchCount++;
                        break; // Move to next feature once we find a match
                    }
                }
            }
            
            // Calculate an overall confidence score
            // We consider both the best match score and the number of matching features
            const matchDiversity = Math.min(matchCount / 3, 1); // Cap at 1.0
            const overallScore = bestMatchScore * 0.7 + matchDiversity * 0.3;
            
            return {
                product: product,
                score: overallScore,
                matchCount: matchCount
            };
        });
        
        // Sort products by score
        productScores.sort((a, b) => b.score - a.score);
        
        logDebug('Product scores:', productScores);
        
        // Return the best match if it has a minimum threshold of confidence
        if (productScores.length > 0 && productScores[0].score > 0.1) {
            return {
                ...productScores[0].product,
                confidence: productScores[0].score,
                matchCount: productScores[0].matchCount
            };
        }
        
        return null;
    }

    // Update form with product details
    function updateProductForm(product, detectedFeatures) {
        const nameInput = document.getElementById('productName');
        const categoryInput = document.getElementById('productCategory');
        const priceInput = document.getElementById('productPrice');
        const descriptionInput = document.getElementById('productDescription');
        const confidenceBar = document.getElementById('confidenceBar');
        const confidenceValue = document.getElementById('confidenceValue');
        
        if (product) {
            nameInput.value = product.name;
            categoryInput.value = product.category;
            priceInput.value = product.price;
            
            // Format description to include match details
            let enhancedDescription = product.description + "\n\n";
            enhancedDescription += `Match confidence: ${Math.round(product.confidence * 100)}%\n`;
            enhancedDescription += `Matched ${product.matchCount} keyword(s)`;
            
            descriptionInput.value = enhancedDescription;
            
            // Update confidence bar
            const confidencePercent = Math.round(product.confidence * 100);
            confidenceBar.style.width = `${confidencePercent}%`;
            confidenceBar.setAttribute('aria-valuenow', confidencePercent);
            confidenceValue.textContent = `${confidencePercent}%`;
            
            // Set color based on confidence level
            if (confidencePercent >= 70) {
                confidenceBar.className = 'progress-bar bg-success';
            } else if (confidencePercent >= 40) {
                confidenceBar.className = 'progress-bar bg-warning';
            } else {
                confidenceBar.className = 'progress-bar bg-danger';
            }
        } else {
            // No match found
            nameInput.value = "No matching product found";
            categoryInput.value = "";
            priceInput.value = "";
            
            // Show top detections in description
            if (detectedFeatures && detectedFeatures.length > 0) {
                let detectionText = "Top detections:\n";
                detectedFeatures.slice(0, 5).forEach((feature, index) => {
                    detectionText += `${index + 1}. ${feature.name} (${Math.round(feature.score * 100)}%)\n`;
                });
                descriptionInput.value = detectionText + "\n\nTry capturing the image again or try with a different product.";
            } else {
                descriptionInput.value = "No features detected. Try capturing the image again with better lighting or try with a different product.";
            }
            
            // Reset confidence bar
            confidenceBar.style.width = "0%";
            confidenceBar.setAttribute('aria-valuenow', 0);
            confidenceValue.textContent = "0%";
            confidenceBar.className = 'progress-bar bg-secondary';
        }
    }

    // Reset form and captured image
    function resetForm() {
        document.getElementById('productName').value = "";
        document.getElementById('productCategory').value = "";
        document.getElementById('productPrice').value = "";
        document.getElementById('productDescription').value = "";
        
        // Reset confidence bar
        document.getElementById('confidenceBar').style.width = "0%";
        document.getElementById('confidenceBar').setAttribute('aria-valuenow', 0);
        document.getElementById('confidenceValue').textContent = "0%";
        document.getElementById('confidenceBar').className = 'progress-bar bg-secondary';
        
        // Reset captured image
        sourceImage.style.display = 'none';
        noImageText.style.display = 'block';
        
        // Reset file input
        if (imageUpload) {
            imageUpload.value = '';
        }
        
        // Clear debug info
        if (ENABLE_DEBUG) {
            debugInfo.textContent = '';
        }
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', () => {
        // Initialize application
        init();
        
        // Setup event listeners
        captureBtn.addEventListener('click', captureImage);
        resetBtn.addEventListener('click', resetForm);
        
        if (imageUpload) {
            imageUpload.addEventListener('change', handleImageUpload);
        }
    });
</script>
@endsection