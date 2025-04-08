<?php
require 'config.php'; // Arquivo com conexão ao banco de dados

$usuarioLogado = null;
if (isset($_SESSION['usuario'])) {
    $usuarioLogado = $_SESSION['usuario'] ? $_SESSION['usuario'] : header("Location: login.php");
}

// Consulta pacotes de viagem
try {
    $stmt = $pdo->prepare("SELECT * FROM pacotes_viagem ORDER BY data_partida ASC");
    $stmt->execute();
    $pacotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMsg = "Erro ao buscar pacotes: " . $e->getMessage();
    $pacotes = [];
}

// Consulta clientes
try {
    $stmt = $pdo->prepare("SELECT * FROM clientes ORDER BY nome ASC");
    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMsgClientes = "Erro ao buscar clientes: " . $e->getMessage();
    $clientes = [];
}

// Logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP - Pacotes de Viagem</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800">

    <!-- Botão de Logout -->
    <form method="POST" class="absolute top-4 right-4">
        <button type="submit" name="logout"
            class="px-4 py-2 bg-red-500 text-white rounded-full shadow-md flex items-center gap-2 hover:bg-red-600 transition focus:outline-none focus:ring-2 focus:ring-red-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 11-6 0v-1m6-10V5a3 3 0 00-6 0v1" />
            </svg>
            Sair
        </button>
    </form>

    <?php if ($usuarioLogado): ?>
        <div class="absolute top-4 right-32 bg-white px-4 py-2 rounded-md shadow text-gray-800 text-sm">
            Logado como <strong><?= htmlspecialchars($usuarioLogado['nome']) ?></strong>
            (<?= htmlspecialchars($usuarioLogado['cargo']) ?>)
        </div>
    <?php endif; ?>

    <div class="max-w-4xl mx-auto mt-12 p-6 bg-white shadow-md rounded-lg my-2 mt-12">
        <h1 class="text-2xl font-semibold text-center">Lista de Pacotes de Viagem</h1>

        <?php if (isset($errorMsg)): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-md my-4">
                <strong>Erro!</strong> <?= htmlspecialchars($errorMsg) ?>
            </div>
        <?php elseif (empty($pacotes)): ?>
            <div class="text-center text-gray-600 p-4 rounded-md bg-gray-100">
                Nenhum pacote cadastrado no momento.
            </div>
        <?php else: ?>
            <table class="w-full mt-4 text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="p-2">Nome</th>
                        <th class="p-2">Destino</th>
                        <th class="p-2">Preço</th>
                        <th class="p-2">Data</th>
                        <th class="p-2 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pacotes as $pacote): ?>
                        <tr class="border-b">
                            <td class="p-2"><?= htmlspecialchars($pacote['nome']) ?></td>
                            <td class="p-2"><?= htmlspecialchars($pacote['destino']) ?></td>
                            <td class="p-2 text-green-600">R$ <?= number_format($pacote['preco'], 2, ',', '.') ?></td>
                            <td class="p-2"><?= date("d/m/Y", strtotime($pacote['data_partida'])) ?></td>
                            <td class="p-2 text-center">
                                <a href="editar_pacote.php?id=<?= $pacote['id'] ?>" class="text-blue-600 hover:underline">Editar</a> |
                                <a href="excluir_pacote.php?id=<?= $pacote['id'] ?>" class="text-red-600 hover:underline"
                                    onclick="return confirm('Tem certeza?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="text-center mt-6">
            <a href="adicionar_pacote.php" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                + Adicionar Novo Pacote
            </a>
        </div>

        <h2 class="text-2xl font-semibold text-center mt-12">Lista de Clientes</h2>

        <?php if (isset($errorMsgClientes)): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-md my-4">
                <strong>Erro!</strong> <?= htmlspecialchars($errorMsgClientes) ?>
            </div>
        <?php elseif (empty($clientes)): ?>
            <div class="text-center text-gray-600 p-4 rounded-md bg-gray-100">
                Nenhum cliente cadastrado no momento.
            </div>
        <?php else: ?>
            <table class="w-full mt-4 text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="p-2">Nome</th>
                        <th class="p-2">E-mail</th>
                        <th class="p-2">Telefone</th>
                        <th class="p-2 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr class="border-b">
                            <a href="editar_cliente.php?id=<?= $cliente['id'] ?>" class="text-blue-600 hover:underline">
                                <td class="p-2">
                                    <?= htmlspecialchars($cliente['nome']) ?>
                                </td>
                                <td class="p-2"><?= htmlspecialchars($cliente['email']) ?></td>
                                <td class="p-2"><?= htmlspecialchars($cliente['telefone']) ?></td>
                                <td class="p-2 text-center">
                                    <a href="editar_cliente.php?id=<?= $cliente['id'] ?>" class="text-blue-600 hover:underline">Editar</a> |
                                    <a href="excluir_cliente.php?id=<?= $cliente['id'] ?>" class="text-red-600 hover:underline"
                                        onclick="return confirm('Tem certeza?');">Excluir</a>
                                    <button onclick="openModal(<?= $cliente['id'] ?>, '<?= $cliente['nome'] ?>')" class="text-green-600 hover:underline">
                                        Atribuir Pacote
                                    </button>
                                </td>
                            </a>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="text-center mt-6">
            <a href="cadastro_cliente.php" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                + Cadastrar novo cliente
            </a>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-semibold mb-4">Atribuir Pacote a <span id="clienteNome"></span></h2>
            <form id="formAtribuirPacote" action="atribuir_pacote.php" method="POST">
                <input type="hidden" name="cliente_id" id="clienteId">
                <labwel class="block mb-2">Escolha um pacote:</labwel>
                <select name="pacote_id" class="w-full p-2 border rounded-md">
                    <?php foreach ($pacotes as $pacote): ?>
                        <option value="<?= $pacote['id'] ?>"><?= htmlspecialchars($pacote['nome']) ?> - R$ <?= number_format($pacote['preco'], 2, ',', '.') ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="mt-4 flex justify-end">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500">Cancelar</button>
                    <button type="submit" class="ml-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(clienteId, clienteNome) {
            document.getElementById("clienteId").value = clienteId;
            document.getElementById("clienteNome").innerText = clienteNome;
            document.getElementById("modal").classList.remove("hidden");
        }

        function closeModal() {
            document.getElementById("modal").classList.add("hidden");
        }

        // Enviar dados do formulário via AJAX
        document.getElementById('formAtribuirPacote').addEventListener('submit', function(event) {
            event.preventDefault();

            var formData = new FormData(this);

            fetch('atribuir_pacote.php', {
                method: 'POST',
                body: formData
            }).then(response => response.json()).then(data => {
                if (data.status === 'success') {
                    alert('Pacote atribuído com sucesso!');
                    closeModal();
                } else {
                    alert('Erro: ' + data.message);
                }
            }).catch(error => console.error('Erro:', error));
        });
    </script>


</body>

</html>