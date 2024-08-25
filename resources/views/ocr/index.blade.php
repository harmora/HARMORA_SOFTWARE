<!-- resources/views/ocr.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= get_label('ocr_test', 'OCR Test') ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            padding-top: 20px;
            background-color: #0080ff;
        }
        .container {
            max-width: 600px;
        }
        .header {
            margin-bottom: 20px;
        }
        #result {
            white-space: pre-wrap; /* Preserve whitespace formatting */
            word-wrap: break-word; /* Ensure long words are wrapped */
        }
        .spinner-container {
            display: none; /* Initially hide spinner */
            text-align: center;
            color: red; /* Set spinner color to red */
        }
        .spinner-border {
            border-color: red !important; /* Ensure spinner color is red */
        }
        .loading-message {
            color: red;
            margin-top: 10px;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header text-center">
            <h1 style="color: rgb(255, 255, 255);"><?= get_label('ocr_test_title', 'OCR FINAL TEST') ?></h1>
            <p style="color: rgb(255, 255, 255);" class="lead"><?= get_label('ocr_test_description', 'Upload an image to extract text using OCR.') ?></p>
        </div>
        <div class="card">
            <div class="card-body">
                <input type="file" id="fileInput" class="form-control mb-3" />
                <div id="spinnerContainer" class="spinner-container">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="loading-message"><?= get_label('ocr_test_loading', 'Extracting text, please wait...') ?></div>
                </div>
                <p id="result" class="mt-3"></p>
            </div>
        </div>
    </div>

    <!-- Bootstrap and Tesseract.js JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tesseract.js/5.1.0/tesseract.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.getElementById('fileInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                // Show spinner
                document.getElementById('spinnerContainer').style.display = 'block';

                Tesseract.recognize(
                    file,
                    'eng',
                    {
                        logger: info => console.log(info),
                        tessedit_ocr_engine_mode: 1, // LSTM engine
                        tessedit_pageseg_mode: 6 // Single block of text
                    }
                ).then(({ data: { text } }) => {
                    document.getElementById('result').textContent = text;
                }).catch(error => {
                    console.error(error);
                }).finally(() => {
                    // Hide spinner
                    document.getElementById('spinnerContainer').style.display = 'none';
                });
            }
        });
    </script>
</body>
</html>
