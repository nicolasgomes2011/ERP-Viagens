<?php
// Inclui a configuração do banco de dados
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pegando os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Validando o email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email inválido.";
        exit;
    }

    // Verificando se o email já existe
    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "O email já está cadastrado.";
        exit;
    }

    // Criptografando a senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Inserindo usuário no banco
    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':senha', $senha_hash, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $_SESSION['usuario'] = [
            'nome' => $nome,
            'email' => $email
        ];

        header('Location: index.php');
        exit;
        
    } else {
        echo "Erro ao cadastrar usuário.";
    }
}
?>
