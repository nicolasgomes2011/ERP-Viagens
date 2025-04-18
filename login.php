<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        
        $_SESSION['usuario'] = $usuario;
        header("Location: index.php");
        exit;
    } else {
        $erro = "<p class='text-red-500 text-sm text-center'>Email ou senha incorretos.</p>";
    }
}


?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
<link rel="icon" type="image/png" href="img/soft_tech_favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ERP SoftTech</title>
    <link rel="icon" type="image/png" href="img/soft_tech_favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .logo{
            width: 50%;
        }
    </style>
</head>

<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <div class="flex justify-center mb-6">
            <img src="img/Soft_Tech_Sem_Fundo.png" alt="Logo" class="logo">
        </div>

        <?php
        session_start();
        require 'config.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            $senha = $_POST['senha'];

            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch();

            if ($usuario && password_verify($senha, $usuario['senha'])) {
                header("Location: index.php");
                exit;
            } else {
                echo "<p class='text-red-500 text-sm text-center'>Email ou senha incorretos.</p>";
            }
        }
        ?>
        <form method="post">
            <div class="mb-4">
                <label class="block text-gray-600 text-sm mb-2">Email</label>
                <input type="email" name="email" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                <label class="block text-gray-600 text-sm mb-2">Senha</label>
                <input type="password" name="senha" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex flex-col space-y-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded w-full">Entrar</button>
                <a href="cadastrar_usuarios.php" class="bg-gray-500 hover:bg-gray-600 text-white p-2 rounded w-full text-center">Cadastrar</a>
            </div>
        </form>


        
    </div>
</body>

</html>