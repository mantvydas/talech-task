var multiImgPreview = function(input) {
    var imgPreviewPlaceholder = document.getElementById("imgPreview");
    imgPreviewPlaceholder.innerHTML = '';

    if (input.files) {
        var filesAmount = input.files.length;

        for (i = 0; i < filesAmount; i++) {
            var reader = new FileReader();

            reader.onload = function(event) {
                var img = document.createElement( 'img' );
                img.src = event.target.result;
                img.style.height = '100px';
                imgPreviewPlaceholder.appendChild(img);
            }

            reader.readAsDataURL(input.files[i]);
        }
    }

};

window.addEventListener('load', function () {
    document.getElementById("images").onchange = function() {
        multiImgPreview(this);
    };
});
