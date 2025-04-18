<?php
// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $nome = $_POST['nome'];
    $destino = $_POST['destino'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $data_partida = $_POST['data_partida'];
    $data_chegada = $_POST['data_chegada'];

    // Validação básica
    if (empty($nome) || empty($destino) || empty($preco) || empty($data_partida) || empty($data_chegada)) {
        $_SESSION['msg'] = 'Por favor, preencha todos os campos obrigatórios.';
        header('Location: cadastro_pacote.php');
        exit;
    }

    try {
        // Prepara a consulta de inserção no banco de dados
        $stmt = $pdo->prepare("INSERT INTO pacotes_viagem (nome, destino, descricao, preco, data_partida) 
                               VALUES (?, ?, ?, ?, ?)");
        
        // Executa a consulta
        $stmt->execute([$nome, $destino, $descricao, $preco, $data_partida]);

        // Mensagem de sucesso
        $_SESSION['msg'] = 'Pacote cadastrado com sucesso!';
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        // Erro na inserção
        $_SESSION['msg'] = 'Erro ao cadastrar pacote: ' . $e->getMessage();
        header('Location: cadastro_pacote.php');
        exit;
    }
}
?>
