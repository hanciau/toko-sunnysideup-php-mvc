
document.getElementById('image').addEventListener('change', function() {
    var imagePreview = document.getElementById('image-preview');
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
        };
        reader.readAsDataURL(this.files[0]);
    } else {
        imagePreview.style.display = 'none';
    }
});
