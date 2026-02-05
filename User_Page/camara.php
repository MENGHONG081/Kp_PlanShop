
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Photo Upload</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="max-w-md w-full bg-white p-6 rounded-xl shadow-lg">

    <h1 class="text-2xl font-bold mb-4 text-center">Upload Payment Photo</h1>

    <?php if (isset($message)): ?>
        <div class="mb-4 p-3 text-center rounded-md <?php echo strpos($message,'successfully')!==false?'bg-green-100 text-green-700':'bg-red-100 text-red-700'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form action="Payment.php?order=<?= htmlspecialchars($_GET['order'] ?? '') ?>" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">

        <!-- Video Preview -->
        <video id="video" autoplay playsinline class="w-full h-64 rounded-xl bg-gray-200 shadow-md"></video>

        <!-- Hidden Canvas -->
        <canvas id="canvas" class="hidden"></canvas>

        <!-- Hidden File Input -->
        <input type="file" name="image" id="imgInput" accept="image/*" class="hidden" required>

        <!-- Buttons -->
        <button type="button"
                onclick="startCamera()"
                class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-md transition">
            📸 Open Camera
        </button>

        <button type="button"
                onclick="capturePhoto()"
                class="w-full py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold shadow-md transition">
            ✅ Take Photo
        </button>
        
        <button type="submit"
                class="w-full py-3 rounded-xl bg-black hover:bg-gray-900 text-white font-semibold shadow-md transition">
            💾 Submit Photo
        </button>

    </form>
</div>

<script>
let cameraStream;

function startCamera() {
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                cameraStream = stream;
                const video = document.getElementById('video');
                video.srcObject = stream;
            })
            .catch(err => alert('Camera not available: ' + err));
    } else {
        alert('Camera API not supported in this browser.');
    }
}

function capturePhoto() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const input = document.getElementById('imgInput');

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    canvas.getContext('2d').drawImage(video, 0, 0);

    canvas.toBlob(blob => {
        const file = new File([blob], "payment_photo.jpg", { type: "image/jpeg" });

        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;

        // Stop camera after capture
        if (cameraStream) {
            cameraStream.getTracks().forEach(track => track.stop());
        }
    }, "image/jpeg");
}
</script>

</body>
</html>
