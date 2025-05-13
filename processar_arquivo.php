<?php
$uploadDir = 'uploads/';
$jsonFile = 'publicacoes.json'; // Arquivo que vai registrar os PDFs

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_FILES['file']['error'] == UPLOAD_ERR_OK) {
    $tempFile = $_FILES['file']['tmp_name'];
    $fileName = basename($_FILES['file']['name']);

    $fileType = mime_content_type($tempFile);
    if ($fileType == 'application/pdf') {
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($tempFile, $uploadPath)) {
            // Salvar no JSON
            $data = [];
            if (file_exists($jsonFile)) {
                $jsonContent = file_get_contents($jsonFile);
                $data = json_decode($jsonContent, true);
            }

            $data[] = [
                'name' => $fileName,
                'path' => $uploadPath,
                'timestamp' => date('Y-m-d H:i:s')
            ];

            file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));

            echo json_encode(['success' => true, 'message' => 'Arquivo salvo.', 'file' => $uploadPath]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao mover o arquivo.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Tipo de arquivo inválido.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Erro no envio.']);
}
?>
<?php
$uploadDir = 'uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && isset($_POST['destino'])) {
    $file = $_FILES['file'];
    $destino = $_POST['destino']; // 'publicacoes' ou 'contratos'
    
    // Define para onde salvar os dados
    $jsonFile = ($destino === 'publicacoes') ? 'publicacoes.json' : 'contratos.json';
    $redirect = ($destino === 'publicacoes') ? 'publicacoeslegais.html' : 'contratos.html';

    if ($file['error'] === UPLOAD_ERR_OK) {
        $tmpName = $file['tmp_name'];
        $fileName = basename($file['name']);
        $uploadPath = $uploadDir . $fileName;

        if (mime_content_type($tmpName) === 'application/pdf') {
            if (move_uploaded_file($tmpName, $uploadPath)) {
                // Salva o nome no arquivo JSON
                $data = file_exists($jsonFile) ? json_decode(file_get_contents($jsonFile), true) : [];
                $data[] = ['name' => $fileName, 'path' => $uploadPath];
                file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
                header("Location: $redirect");
                exit();
            }
        }
    }
}

// Se algo der errado:
echo "Erro no envio do arquivo.";
