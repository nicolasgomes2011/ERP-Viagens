<!-- cadastro_form.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h1 class="text-2xl font-bold text-center mb-4">Cadastrar Usuário</h1>
        <form action="acaoCadastrarUsuario.php" method="POST">
            <div class="mb-4">
                <label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
                <input type="text" id="nome" name="nome" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="senha" class="block text-sm font-medium text-gray-700">Senha</label>
                <input type="password" id="senha" name="senha" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4 flex justify-center">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Cadastrar</button>
            </div>
        </form>
    </div>
</body>
</html>
