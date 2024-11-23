<?php
require_once 'functions.php';

$processedText = "";
$shift = 0;
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['export_format'])) {
        $processedText = $_POST['export_message'];
        $shift = $_POST['export_shift'];
        handleExport($processedText, $shift, $_POST['export_format']);
    }

    if (isset($_FILES['import_file']) && $_FILES['import_file']['error'] === UPLOAD_ERR_OK) {
        $message = handleFileUpload();
    } elseif (!empty($_POST['message'])) {
        $message = trim($_POST['message']);
    }

    if (!empty($message) && isset($_POST['action'])) {
        $action = $_POST['action'];
        $shift = intval($_POST['shift']);
        $processedText = ($action === "encrypt") ? caesarCipher($message, $shift) : caesarCipher($message, -$shift);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encriptación César</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/fontawesome/css/all.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <div class="container main-container">
        <div class="title-section">
            <h1 class="mb-0"><i class="fas fa-lock me-2"></i>Método de Encriptación César</h1>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="message" class="form-label">Mensaje</label>
                <textarea 
                    id="message" 
                    name="message" 
                    class="form-control" 
                    rows="4" 
                    placeholder="Escribe tu mensaje aquí..." 
                    <?php echo isset($_FILES['import_file']) && $_FILES['import_file']['error'] === UPLOAD_ERR_OK ? 'disabled' : ''; ?>
                ><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
            </div>

            <div class="mb-4">
                <label for="importFile" class="form-label">Importar archivo</label>
                <input 
                    id="importFile" 
                    type="file" 
                    name="import_file" 
                    accept=".txt,.json" 
                    class="form-control" 
                    onchange="checkFileImport()"
                >
            </div>

            <div class="mb-4">
                <label for="shift" class="form-label">Desplazamiento</label>
                <input 
                    type="number" 
                    id="shift"
                    name="shift" 
                    class="form-control" 
                    placeholder="Número de desplazamiento" 
                    required 
                    value="<?php echo isset($_POST['shift']) ? htmlspecialchars($_POST['shift'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                >
            </div>

            <div class="d-flex gap-2 mb-4">
                <button type="submit" name="action" value="encrypt" class="btn btn-gradient">
                    <i class="fas fa-lock me-2"></i>Encriptar
                </button>
                <button type="submit" name="action" value="decrypt" class="btn btn-gradient">
                    <i class="fas fa-lock-open me-2"></i>Desencriptar
                </button>
                <button type="button" onclick="clearTextArea()" class="btn btn-secondary">
                    <i class="fas fa-eraser me-2"></i>Limpiar
                </button>
            </div>
        </form>

        <div class="result-section">
            <h3 class="mb-3"><i class="fas fa-clipboard-check me-2"></i>Resultado:</h3>
            <textarea 
                id="resultText" 
                class="form-control mb-3" 
                rows="4" 
                readonly
            ><?php echo !empty($processedText) ? $processedText : (!empty($message) ? "Por favor, ingresa un mensaje válido y un número de desplazamiento." : "No se encontró mensaje para procesar. Por favor, escribe un mensaje o importa un archivo."); ?></textarea>
            <button onclick="copyResult()" class="btn btn-gradient">
                <i class="fas fa-copy me-2"></i>Copiar Resultado
            </button>
        </div>

        <?php if (!empty($processedText)) : ?>
            <div class="mt-4">
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="exportCheckbox" onclick="toggleExportOptions()">
                    <label class="form-check-label" for="exportCheckbox">¿Deseas exportar el resultado?</label>
                </div>

                <div id="exportSection" style="display: none;">
                    <form method="POST" class="d-flex gap-2">
                        <input type="hidden" name="export_message" value="<?php echo htmlspecialchars($processedText, ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="export_shift" value="<?php echo htmlspecialchars($shift, ENT_QUOTES, 'UTF-8'); ?>">
                        <button type="submit" name="export_format" value="txt" class="btn btn-gradient">
                            <i class="fas fa-file-alt me-2"></i>Exportar como TXT
                        </button>
                        <button type="submit" name="export_format" value="json" class="btn btn-gradient">
                            <i class="fas fa-file-code me-2"></i>Exportar como JSON
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="main.js"></script>
</body>
</html>