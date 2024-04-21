
document.getElementById('chooseImageButton').addEventListener('click', function () {
    var input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.onchange = function (e) {
        var file = e.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function () {
                // Set the value of the input field with the base64-encoded image
                document.getElementById('art_imageId').value = reader.result;
                // Convert data URI to File object
                var imageFile = dataURItoFile(reader.result, file.name);
                // Copy the selected image to the assets/images directory
                copyImageToAssets(imageFile);
            };
            reader.readAsDataURL(file);
        }
    };
    input.click();
});

function copyImageToAssets(file) {
    var formData = new FormData();
    formData.append('image', file);

    fetch('/copy-image', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            console.log('Image copied successfully.');
        } else {
            console.error('Failed to copy image.');
        }
    })
    .catch(error => {
        console.error('Error copying image:', error);
    });
}

    document.getElementById('chooseMusicButton').addEventListener('click', function () {
        var input = document.createElement('input');
        input.type = 'file';
        input.accept = 'audio/*';
        input.onchange = function (e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function () {
                    document.getElementById('art_musicPath').value = reader.result;
                };
                reader.readAsDataURL(file);
            }
        };
        input.click();
    });
// Assuming imageDataURI contains the data URI string
function dataURItoFile(dataURI, filename) {
    // Convert base64 to binary
    var byteString = atob(dataURI.split(',')[1]);

    // Extract MIME type
    var mimeType = dataURI.split(',')[0].split(':')[1].split(';')[0];

    // Create ArrayBuffer and DataView objects for binary data
    var buffer = new ArrayBuffer(byteString.length);
    var array = new Uint8Array(buffer);
    for (var i = 0; i < byteString.length; i++) {
        array[i] = byteString.charCodeAt(i);
    }

    // Create Blob object from ArrayBuffer
    var blob = new Blob([array], { type: mimeType });

    // Create File object
    var file = new File([blob], filename, { type: mimeType });
    
    return file;
}

// Usage
var file = dataURItoFile(imageDataURI, 'image.jpg');
