
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Payment Scanner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom animation for the scanning line */
        @keyframes scan {
            0% { top: 0%; }
            100% { top: 100%; }
        }
        .scan-line {
            animation: scan 2s linear infinite;
            background: linear-gradient(to bottom, transparent, #3b82f6, transparent);
        }
    </style>
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md bg-gray-800 rounded-3xl overflow-hidden shadow-2xl border border-gray-700">
        <div class="p-6 text-center">
            <h2 class="text-xl font-bold text-white">KHQR AI Scanner</h2>
            <p class="text-gray-400 text-sm">Align the payment receipt within the box</p>
        </div>

        <div class="relative aspect-[3/4] bg-black overflow-hidden">
            <video id="webcam" class="absolute inset-0 w-full h-full object-cover" autoplay playsinline></video>
            <canvas id="captureCanvas" class="hidden"></canvas>

            <div class="absolute inset-0 flex items-center justify-center">
                <div class="absolute inset-0 bg-black/40"></div>
                
                <div class="relative w-64 h-64 border-2 border-blue-500 rounded-xl shadow-[0_0_20px_rgba(59,130,246,0.5)] bg-transparent z-10">
                    <div class="absolute -top-1 -left-1 w-6 h-6 border-t-4 border-l-4 border-blue-400 rounded-tl-md"></div>
                    <div class="absolute -top-1 -right-1 w-6 h-6 border-t-4 border-r-4 border-blue-400 rounded-tr-md"></div>
                    <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-4 border-l-4 border-blue-400 rounded-bl-md"></div>
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 border-b-4 border-r-4 border-blue-400 rounded-br-md"></div>
                    
                    <div class="scan-line absolute left-0 right-0 h-1 z-20 shadow-lg"></div>
                </div>
            </div>

            <div id="loader" class="hidden absolute inset-0 z-50 bg-black/70 flex flex-col items-center justify-center">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-500 border-t-transparent"></div>
                <p class="text-white mt-4 font-medium italic">Gemini AI is verifying...</p>
            </div>
        </div>

        <div class="p-8 flex flex-col items-center">
            <button onclick="takeSnapshot()" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 rounded-2xl transition-all active:scale-95 flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Verify Payment
            </button>
            <p id="status" class="mt-4 text-xs text-gray-500 text-center uppercase tracking-widest">System Ready</p>
        </div>
    </div>

    <form id="paymentForm" action="upload.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="order_id" value="">
        <input type="hidden" name="amount" value="">
        <input type="hidden" name="order_date" value="<?= date('Y-m-d H:i:s') ?>">
        <input type="file" name="image" id="imageInput" class="hidden">
    </form>

    <script>
        const video = document.getElementById('webcam');
        const canvas = document.getElementById('captureCanvas');
        const loader = document.getElementById('loader');

        // Start Camera
        navigator.mediaDevices.getUserMedia({ 
            video: { facingMode: "environment", width: { ideal: 1280 }, height: { ideal: 720 } } 
        })
        .then(stream => { video.srcObject = stream; })
        .catch(err => alert("Camera blocked or not found."));

        function takeSnapshot() {
            loader.classList.remove('hidden'); // Show loading spinner
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0);
            
            canvas.toBlob((blob) => {
                const file = new File([blob], "receipt.jpg", { type: "image/jpeg" });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                document.getElementById('imageInput').files = dataTransfer.files;
                document.getElementById('paymentForm').submit();
            }, 'image/jpeg', 0.85);
        }
    </script>
</body>
</html>