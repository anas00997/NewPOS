const imageUploadContainer = document.getElementById("imageUploadContainer");
const inputFile = document.getElementById("thumbnailInput");
const thumbnailPreview = document.getElementById("thumbnailPreview");
let uploadText = document.querySelector(".upload-text");

if (imageUploadContainer && inputFile && thumbnailPreview) {
    if (!uploadText) {
        uploadText = document.querySelector(".upload-content");
    }

    imageUploadContainer.addEventListener("click", function () {
        inputFile.click();
    });

    inputFile.addEventListener("change", function () {
        const reader = new FileReader();
        reader.addEventListener("load", function () {
            thumbnailPreview.src = reader.result;
            thumbnailPreview.classList.remove("d-none");
            if (uploadText) {
                uploadText.classList.add("d-none");
            }
        });
        if (this.files[0]) {
            reader.readAsDataURL(this.files[0]);
        }
    });
}
