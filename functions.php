<?php
function caesarCipher($text, $shift) {
    $result = "";
    $shift = $shift % 26;
    foreach (str_split($text) as $char) {
        if (ctype_alpha($char)) {
            $asciiOffset = ctype_upper($char) ? 65 : 97;
            $newChar = chr(($asciiOffset + ((ord($char) - $asciiOffset + $shift + 26) % 26)));
            $result .= $newChar;
        } else {
            $result .= $char;
        }
    }
    return $result;
}

function handleFileUpload() {
    if (isset($_FILES['import_file']) && $_FILES['import_file']['error'] === UPLOAD_ERR_OK) {
        return trim(file_get_contents($_FILES['import_file']['tmp_name']));
    }
    return '';
}

function handleExport($processedText, $shift, $format) {
    if ($format === "txt") {
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="resultado_cesar.txt"');
        echo  $processedText . "\n";
        exit;
    } elseif ($format === "json") {
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="resultado_cesar.json"');
        echo json_encode([
            $processedText,
        ]);
        exit;
    }
}
?>