import { loadImageFile } from "./image-helper.js";

document.addEventListener("DOMContentLoaded", () => {
    const fileInput = document.querySelector(".image-upload-input");
    const preview = document.getElementById("preview");

    if (!fileInput || !preview) return;

    fileInput.addEventListener("change", function (event) {
        const file = event.target.files[0];
        if (!file) return;

        loadImageFile(file, (imageSrc) => {
            preview.src = imageSrc;
            preview.classList.remove("is-hidden");
        });
    });
});
