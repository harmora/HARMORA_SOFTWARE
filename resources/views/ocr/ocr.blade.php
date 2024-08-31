@extends('layout')

@section('title')
<?= get_label('ocr_test', 'OCR Test') ?>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/home') }}">{{ get_label('home', 'Home') }}</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= get_label('ocr_test', 'OCR Test') ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h1 class="text-center ocr-header text-primary">OCR</h1>
            <p class="text-center ocr-lead text-dark">Upload an image to extract text using OCR.</p>

            <!-- Language Selector -->
            <div class="mb-3">
                <label for="ocrLanguageSelect" class="form-label">Select Language:</label>
                <select id="ocrLanguageSelect" class="form-select">
                    <option value="eng">English</option>
                    <option value="ara">Arabic</option>
                    <option value="fra">French</option>
                </select>
            </div>

            <input type="file" id="ocrFileInput" class="form-control mb-3 ocr-file-input" />
            <div id="ocrSpinnerContainer" class="ocr-spinner-container">
                <div class="ocr-spinner"></div>
                <div class="ocr-loading-message">Extracting text, please wait...</div>
            </div>

            <!-- Styled container for OCR result -->
            <div id="ocrResultContainer" class="ocr-result-container mt-3">
                <p id="ocrResult" class="ocr-result"></p>

            </div>
        </div>
    </div>
</div>
@endsection

<!-- Custom CSS -->
<style>
    .ocr-file-input {
        background-color: #ffffff;
    }
    .ocr-spinner-container {
        display: none; /* Initially hide spinner */
        text-align: center;
        color: red; /* Set spinner color to red */
    }
    .ocr-spinner {
        border: 8px solid rgba(0, 0, 0, 0.1); /* Light grey border */
        border-radius: 50%;
        border-top: 8px solid red; /* Red spinner color */
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
        margin: 0 auto; /* Center the spinner */
    }
    .ocr-loading-message {
        color: red;
        margin-top: 10px;
        font-size: 1.2em;
    }
    .ocr-result-container {
        background-color: #f8f9fa; /* Light grey background for contrast */
        border: 1px solid #ced4da; /* Border for the container */
        border-radius: 0.25rem; /* Rounded corners */
        padding: 1rem; /* Padding for spacing */
        box-shadow: 0 0 0.125rem rgba(0,0,0,0.075); /* Light shadow for depth */
        overflow-wrap: break-word; /* Ensure long text breaks properly */
    }
    .ocr-result {
        white-space: pre-wrap; /* Preserve whitespace formatting */
        word-wrap: break-word; /* Ensure long words are wrapped */
        color: #212529; /* Dark text color for readability */
    }
    body {
        padding-top: 20px;
        background-color: #0080ff;
    }
    .container {
        max-width: 600px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<!-- Bootstrap and Tesseract.js JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tesseract.js/5.1.0/tesseract.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.6.172/pdf.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Tesseract:', Tesseract);

        const fileInput = document.getElementById('ocrFileInput');
        const languageSelect = document.getElementById('ocrLanguageSelect');
        const spinnerContainer = document.getElementById('ocrSpinnerContainer');
        const resultElement = document.getElementById('ocrResult');
        const resultContainer = document.getElementById('ocrResultContainer');

        if (fileInput) {
            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                const language = languageSelect.value;
                if (file) {
                    // Show spinner
                    spinnerContainer.style.display = 'block';

                    if (file.type === 'application/pdf') {
                        // PDF to image conversion
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const pdfData = new Uint8Array(e.target.result);

                            pdfjsLib.getDocument({ data: pdfData }).promise.then(function(pdf) {
                                const numPages = pdf.numPages;
                                let pagePromises = [];

                                for (let i = 1; i <= numPages; i++) {
                                    pagePromises.push(
                                        pdf.getPage(i).then(function(page) {
                                            const viewport = page.getViewport({ scale: 1.5 });
                                            const canvas = document.createElement('canvas');
                                            const context = canvas.getContext('2d');

                                            canvas.width = viewport.width;
                                            canvas.height = viewport.height;

                                            const renderContext = {
                                                canvasContext: context,
                                                viewport: viewport
                                            };
                                            return page.render(renderContext).promise.then(() => {
                                                return new Promise((resolve) => {
                                                    canvas.toBlob(resolve, 'image/png'); // Ensure callback function is used
                                                });
                                            });
                                        })
                                    );
                                }

                                return Promise.all(pagePromises);
                            }).then(function(blobs) {
                                // Process each page blob with Tesseract
                                let textPromises = blobs.map(blob => {
                                    return Tesseract.recognize(
                                        blob,
                                        language,
                                        {
                                            logger: info => console.log(info),
                                            tessedit_ocr_engine_mode: 1,
                                            tessedit_pageseg_mode: 6
                                        }
                                    ).then(({ data: { text } }) => text);
                                });

                                return Promise.all(textPromises);
                            }).then(texts => {
                                // Combine text from all pages
                                resultElement.textContent = texts.join('\n');
                                resultContainer.style.display = 'block';
                            }).catch(error => {
                                console.error('PDF to OCR Error:', error);
                            }).finally(() => {
                                spinnerContainer.style.display = 'none';
                            });
                        };
                        reader.readAsArrayBuffer(file);
                    } else {
                        // Handle other file types (e.g., image files)
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = new Image();
                            img.src = e.target.result;

                            img.onload = function() {
                                const canvas = document.createElement('canvas');
                                const ctx = canvas.getContext('2d');

                                canvas.width = img.width;
                                canvas.height = img.height;
                                ctx.drawImage(img, 0, 0);

                                // Preprocessing can be added here

                                canvas.toBlob(function(blob) {
                                    Tesseract.recognize(
                                        blob,
                                        language,
                                        {
                                            logger: info => console.log(info),
                                            tessedit_ocr_engine_mode: 1,
                                            tessedit_pageseg_mode: 6
                                        }
                                    ).then(({ data: { text } }) => {
                                        resultElement.textContent = text;
                                        resultContainer.style.display = 'block';
                                    }).catch(error => {
                                        console.error('OCR Error:', error);
                                    }).finally(() => {
                                        spinnerContainer.style.display = 'none';
                                    });
                                }, 'image/png'); // Ensure MIME type is specified
                            };
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });
        }
    });
</script>
