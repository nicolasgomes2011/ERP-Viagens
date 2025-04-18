<?php
require '../config.php'; // Arquivo de conexão com o banco

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM pacotes_viagem WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $erro = 0;
            $msgErro = "Pacote excluído com sucesso.";
        } else {
            $erro = 1;
            $msgErro = "Nenhum pacote encontrado com o ID fornecido.";
        }
    } catch (PDOException $e) {
        $erro = 1;
        $msgErro = "Erro ao excluir o pacote: " . $e->getMessage();
    }
} else {
    $erro = 1;
    $msgErro = "ID inválido.";
}

// Redireciona para index.php com as variáveis na query string
header("Location: ../index.php?erro=$erro&msgErro=" . urlencode($msgErro));
exit;