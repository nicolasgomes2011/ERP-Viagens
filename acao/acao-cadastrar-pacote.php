<?php
require '../config.php'; // Arquivo de conexão com o banco

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $nome = $_POST['nome'];
    $local_para_visitar = implode(', ', $_POST['destino']);
    $descricao = $_POST['descricao'];
    $preco = extract_number_with_decimal($_POST['preco']);
    $data_partida = $_POST['data_partida'];
    $data_saida = $_POST['data_saida'];
    $modo = $_POST['submit'] ?? 'register'; // 'edit' ou 'register'

    // Verifica se é uma edição
    $isEdit = $modo === 'edit' && isset($_POST['id']);
    $id = $isEdit ? intval($_POST['id']) : null;

    // Validação básica
    if (empty($nome) || empty($local_para_visitar) || empty($preco) || empty($data_partida) || empty($data_saida)) {
        $_SESSION['msg'] = 'Por favor, preencha todos os campos obrigatórios.';
        $redirect = $isEdit ? "../adicionar_pacote.php?edit=1&id=$id" : "../adicionar_pacote.php?";
        header("Location: $redirect&error=1&msg=Preencha todos os campos obrigatórios!");
        exit;
}   

    try {
        if ($isEdit) {
            // Atualiza o pacote existente
            $stmt = $pdo->prepare("UPDATE pacotes_viagem 
                                   SET nome = ?, destino = ?, descricao = ?, preco = ?, data_partida = ?, data_saida = ? 
                                   WHERE id = ?");
            $stmt->execute([$nome, $local_para_visitar, $descricao, $preco, $data_partida, $data_saida, $id]);

            $_SESSION['msg'] = 'Pacote editado com sucesso!';
            header("Location: ../index.php?error=0&msg=Pacote editado com sucesso!");
            exit;
        } else {
            // Insere novo pacote
            $stmt = $pdo->prepare("INSERT INTO pacotes_viagem (nome, destino, descricao, preco, data_partida, data_saida) 
                                   VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $local_para_visitar, $descricao, $preco, $data_partida, $data_saida]);

            $_SESSION['msg'] = 'Pacote cadastrado com sucesso!';
            header('Location: ../index.php?error=0&msg=Pacote cadastrado com sucesso!');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['msg'] = 'Erro ao salvar pacote: ' . $e->getMessage();
        dd($e->getMessage());
        $redirect = $isEdit ? "../adicionar_pacote.php?edit=1&id=$id" : "../adicionar_pacote.php?";
        header("Location: $redirect&error=1&msg=Erro ao salvar pacote!");
        exit;
    }
}
?>
