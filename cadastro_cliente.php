<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente - ERP Viagens</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 py-8 px-4">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-md shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Cadastro de Cliente</h2>
        <form action="processa_cadastro_clientes.php" method="POST">
            <div class="mb-4">
                <label for="nome" class="block text-gray-700">Nome</label>
                <input type="text" id="nome" name="nome" class="w-full px-4 py-2 border rounded-md" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-md" required>
            </div>

            <div class="mb-4">
                <label for="telefone" class="block text-gray-700">Telefone</label>
                <input type="text" id="telefone" name="telefone" class="w-full px-4 py-2 border rounded-md">
            </div>

            <div class="mb-4">
                <label for="endereco" class="block text-gray-700">Endereço</label>
                <textarea id="endereco" name="endereco" class="w-full px-4 py-2 border rounded-md" rows="4"></textarea>
            </div>

            <div class="mt-4 flex justify-between">
                <button type="submit" class="bg-blue-500 text-white py-2 px-6 rounded-md">Cadastrar Cliente</button>
                <a href="index.php" class="text-blue-500">Voltar</a>
            </div>
        </form>
    </div>
</body>
</html>
