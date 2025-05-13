<?php
$jsonFile = ($_POST['tipo'] === 'publicacoes') ? 'publicacoes.json' : 'contratos.json';

if (isset($_POST['path']) && file_exists($_POST['path'])) {
    // Apaga o arquivo do disco
    unlink($_POST['path']);

    // Remove do JSON
    if (file_exists($jsonFile)) {
        $data = json_decode(file_get_contents($jsonFile), true);
        $data = array_filter($data, fn($item) => $item['path'] !== $_POST['path']);
        file_put_contents($jsonFile, json_encode(array_values($data), JSON_PRETTY_PRINT));
    }

    echo 'ok';
} else {
    echo 'erro';
}
