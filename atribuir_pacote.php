<?php
require 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_id = $_POST['cliente_id'] ?? null;
    $pacote_id = $_POST['pacote_id'] ?? null;

    if (!$cliente_id || !$pacote_id) {
        echo json_encode(['status' => 'error', 'message' => 'Dados inválidos']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO pedidos (cliente_id, pacote_id, data_pedido) VALUES (:cliente_id, :pacote_id, NOW())");
        $stmt->execute([
            ':cliente_id' => $cliente_id,
            ':pacote_id' => $pacote_id
        ]);

        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao salvar pedido: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método inválido']);
}
