// VIDEO
const videoInput = document.getElementById("videoInput");
const videoPreview = document.getElementById("videoPreview");
const videoUploadBtn = document.getElementById("videoUploadBtn");
const videoCancelBtn = document.getElementById("videoCancelBtn");

videoUploadBtn.onclick = () => {
    if (videoUploadBtn.innerText === "Change") {
        videoInput.value = "";
        videoPreview.src = "";
        videoPreview.hidden = true;
        videoUploadBtn.innerText = "Upload";
    }
    videoInput.click();
};

videoInput.onchange = () => {
    const file = videoInput.files[0];
    if (file) {
        videoPreview.src = URL.createObjectURL(file);
        videoPreview.hidden = false;
        videoUploadBtn.innerText = "Change";
    }
};

videoCancelBtn.onclick = () => {
    videoInput.value = "";
    videoPreview.src = "";
    videoPreview.hidden = true;
    videoUploadBtn.innerText = "Upload";
};

// IMAGE
const imageInput = document.getElementById("imageInput");
const imagePreview = document.getElementById("imagePreview");
const imageUploadBtn = document.getElementById("imageUploadBtn");
const imageCancelBtn = document.getElementById("imageCancelBtn");

imageUploadBtn.onclick = () => {
    if (imageUploadBtn.innerText === "Change") {
        imageInput.value = "";
        imagePreview.src = "";
        imagePreview.hidden = true;
        imageUploadBtn.innerText = "Upload";
    }
    imageInput.click();
};

imageInput.onchange = () => {
    const file = imageInput.files[0];
    if (file) {
        imagePreview.src = URL.createObjectURL(file);
        imagePreview.hidden = false;
        imageUploadBtn.innerText = "Change";
    }
};

imageCancelBtn.onclick = () => {
    imageInput.value = "";
    imagePreview.src = "";
    imagePreview.hidden = true;
    imageUploadBtn.innerText = "Upload";
};
