<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image & Video</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .preview-container {
            position: relative;
            margin-top: 1rem;
            display: none;
        }
        .preview-media {
            width: 100%;
            max-height: 400px;
            object-fit: contain;
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .remove-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            font-size: 1.2rem;
            cursor: pointer;
        }
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h2 class="text-center mb-5 fw-bold text-primary">Upload Image & Video</h2>

            <form action="/PLANT_PROJECT/User_Page/index1.php" method="POST" enctype="multipart/form-data">

                <!-- VIDEO UPLOAD CARD -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-4 text-success">Upload Video</h4>

                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="videoInput" name="video" accept="video/*">
                            <button class="btn btn-outline-danger" type="button" id="videoCancelBtn">Cancel</button>
                        </div>

                        <div class="preview-container" id="videoPreviewContainer">
                            <button type="button" class="remove-btn" id="videoRemoveBtn">&times;</button>
                            <video id="videoPreview" class="preview-media" controls></video>
                        </div>
                    </div>
                </div>
                <!-- VIDEO UPLOAD CARD1 -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-4 text-success">Upload Video</h4>

                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="videoInput" name="video" accept="video/*">
                            <button class="btn btn-outline-danger" type="button" id="videoCancelBtn">Cancel</button>
                        </div>

                        <div class="preview-container" id="videoPreviewContainer">
                            <button type="button" class="remove-btn" id="videoRemoveBtn">&times;</button>
                            <video id="videoPreview" class="preview-media" controls></video>
                        </div>
                    </div>
                </div>
                <!-- VIDEO UPLOAD CARD 2 -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-4 text-success">Upload Video</h4>

                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="videoInput" name="video" accept="video/*">
                            <button class="btn btn-outline-danger" type="button" id="videoCancelBtn">Cancel</button>
                        </div>

                        <div class="preview-container" id="videoPreviewContainer">
                            <button type="button" class="remove-btn" id="videoRemoveBtn">&times;</button>
                            <video id="videoPreview" class="preview-media" controls></video>
                        </div>
                    </div>
                </div>
                <!-- VIDEO UPLOAD CARD 3 -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-4 text-success">Upload Video</h4>

                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="videoInput" name="video" accept="video/*">
                            <button class="btn btn-outline-danger" type="button" id="videoCancelBtn">Cancel</button>
                        </div>

                        <div class="preview-container" id="videoPreviewContainer">
                            <button type="button" class="remove-btn" id="videoRemoveBtn">&times;</button>
                            <video id="videoPreview" class="preview-media" controls></video>
                        </div>
                    </div>
                </div>
                <!-- IMAGE UPLOAD CARD -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-4 text-info">Upload Image</h4>

                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="imageInput" name="image" accept="image/*">
                            <button class="btn btn-outline-danger" type="button" id="imageCancelBtn">Cancel</button>
                        </div>

                        <div class="preview-container" id="imagePreviewContainer">
                            <button type="button" class="remove-btn" id="imageRemoveBtn">&times;</button>
                            <img id="imagePreview" class="preview-media" alt="Image preview">
                        </div>
                    </div>
                </div>

                <!-- SUBMIT BUTTON -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg px-5">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
    // Video Preview
    const videoInput = document.getElementById('videoInput');
    const videoPreview = document.getElementById('videoPreview');
    const videoPreviewContainer = document.getElementById('videoPreviewContainer');
    const videoCancelBtn = document.getElementById('videoCancelBtn');
    const videoRemoveBtn = document.getElementById('videoRemoveBtn');

    videoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const url = URL.createObjectURL(file);
            videoPreview.src = url;
            videoPreviewContainer.style.display = 'block';
        }
    });

    function clearVideo() {
        videoInput.value = '';
        videoPreview.src = '';
        videoPreviewContainer.style.display = 'none';
    }

    videoCancelBtn.addEventListener('click', clearVideo);
    videoRemoveBtn.addEventListener('click', clearVideo);

    // Image Preview
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const imageCancelBtn = document.getElementById('imageCancelBtn');
    const imageRemoveBtn = document.getElementById('imageRemoveBtn');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const url = URL.createObjectURL(file);
            imagePreview.src = url;
            imagePreviewContainer.style.display = 'block';
        }
    });

    function clearImage() {
        imageInput.value = '';
        imagePreview.src = '';
        imagePreviewContainer.style.display = 'none';
    }

    imageCancelBtn.addEventListener('click', clearImage);
    imageRemoveBtn.addEventListener('click', clearImage);
</script>

</body>
</html>