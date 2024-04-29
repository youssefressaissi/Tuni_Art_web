// qrCodeGenerator.js

// Function to generate QR code
function generateQRCode(data) {
    new QRious({
        element: document.getElementById('qrCodeContainer'),
        value: data,
        size: 200 // Adjust size as needed
    });

}

// Event listener for the 'Generate QR Code' button
document.getElementById("generateQR").addEventListener("click", function() {
    var art_title = '{{ art.artTitle|raw }}';
var art_price = '{{ art.artPrice }}';
var type = '{{ art.type }}';
var creation = '{{ art.creation }}';
var description = '{{ art.description }}';
var style = '{{ art.style }}';
var art_views = '{{ art.artViews }}';
var isavailable = '{{ art.isavailable }}';

        // Concatenate your variables to form the data for the QR code
        var qrData = "Title: " + art_title + "\n" +
                      "Price: " + art_price + "\n" +
                     "Type: " + type + "\n" +
                     "Creation: " + creation + "\n" +
                     "Description: " + description + "\n" +
                     "Style: " + style + "\n" +
                     "Views: " + art_views + "\n" +
                     "Is Available: " + isavailable;
    generateQRCode(qrData);
});
