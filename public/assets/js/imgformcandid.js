document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('candidature_form_productImgs');
    const imagePreview = document.getElementById('imagePreview');

    imageInput.addEventListener('change', function () {
        imagePreview.innerHTML = ''; // Effacez l'aperçu existant
        const files = imageInput.files;

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.type.match('image.*')) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.className = 'preview-image';
                    img.src = e.target.result;
                    imagePreview.appendChild(img);
                };

                reader.readAsDataURL(file);
            }
        }
    });
});