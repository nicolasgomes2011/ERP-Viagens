<?php
session_start();
require 'config.php'; // Arquivo de conexão com o banco

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cep = $_POST['cep'];
    $numero = $_POST['numero'];
    $endereco = $_POST['endereco'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];

    // Validação básica
    if (empty($nome) || empty($email) || empty($cep) || empty($numero) || empty($endereco) || empty($bairro) || empty($cidade) || empty($estado)) {
        $_SESSION['msg'] = 'Por favor, preencha todos os campos obrigatórios.';
        header('Location: cadastro_cliente.php');
        exit;
    }

    try {
        // Prepara a consulta de inserção no banco de dados
        $stmt = $pdo->prepare("INSERT INTO clientes (nome, email, telefone, cep, numero, endereco, bairro, cidade, estado) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Executa a consulta
        $stmt->execute([$nome, $email, $telefone, $cep, $numero, $endereco, $bairro, $cidade, $estado]);

        // Mensagem de sucesso
        $_SESSION['msg'] = 'Cliente cadastrado com sucesso!';
        header('Location: index.php?error=0&msg=Cliente cadastrado com sucesso!');
        exit;
    } catch (PDOException $e) {
        // Erro na inserção
        $_SESSION['msg'] = 'Erro ao cadastrar cliente: ' . $e->getMessage();
        header('Location: cadastro_cliente.php');
        exit;
    }
}
?>
