import { loadImageFile } from './image-helper.js';

document.addEventListener('DOMContentLoaded', () => {
    const fileInput = document.querySelector('.image-upload-input');
    const preview = document.getElementById('preview');
    const previewWrapper = document.querySelector('.sell__image-wrapper');
    const noImageText = document.getElementById('no-image');

    if (!fileInput || !preview || !previewWrapper) return;

    // 最初は枠を非表示にする（CSSの.is-hiddenで対応）
    previewWrapper.classList.add('is-hidden');

    fileInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (!file) return;

        loadImageFile(file, (imageSrc) => {
            preview.src = imageSrc;

            // 出品画面特有の DOM 操作
            previewWrapper.classList.remove('is-hidden');
            preview.classList.remove('is-hidden');

            if (noImageText) {
                noImageText.style.display = 'none';
            }
        });
    });
});
