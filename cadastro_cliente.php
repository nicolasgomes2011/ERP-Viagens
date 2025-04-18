<?php
require_once "config.php";

$edit = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
$cliente = [];

if ($edit == 1 && !empty($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $errorMsg = "Erro ao buscar cliente: " . $e->getMessage();
        $cliente = [];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
<link rel="icon" type="image/png" href="img/soft_tech_favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $edit == 1 ? 'Editar Cliente' : 'Cadastro de Cliente' ?> - ERP Viagens</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100 py-8 px-4">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-md shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800 mb-6"><?= $edit == 1 ? 'Editar Cliente' : 'Cadastro de Cliente' ?></h2>
        <form action="processa_cadastro_clientes.php" method="POST">
            <input type="hidden" name="id" value="<?= $cliente['id'] ?? '' ?>">
            <input type="hidden" name="edit" value="<?= $edit ?>">

            <div class="mb-4">
                <label for="nome" class="block text-gray-700">Nome</label>
                <input type="text" id="nome" name="nome" class="w-full px-4 py-2 border rounded-md" required value="<?= htmlspecialchars($cliente['nome'] ?? '') ?>">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-md" required value="<?= htmlspecialchars($cliente['email'] ?? '') ?>">
            </div>

            <div class="mb-4">
                <label for="telefone" class="block text-gray-700">Telefone</label>
                <input type="text" id="telefone" name="telefone" class="w-full px-4 py-2 border rounded-md" value="<?= htmlspecialchars($cliente['telefone'] ?? '') ?>">
            </div>

            <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="cep" class="block text-gray-700">CEP</label>
                    <input type="text" id="cep" name="cep" class="w-full px-4 py-2 border rounded-md" required value="<?= htmlspecialchars($cliente['cep'] ?? '') ?>">
                </div>
                <div>
                    <label for="numero" class="block text-gray-700">Número</label>
                    <input type="text" id="numero" name="numero" class="w-full px-4 py-2 border rounded-md" required value="<?= htmlspecialchars($cliente['numero'] ?? '') ?>">
                </div>
            </div>

            <div class="mb-4">
                <label for="endereco" class="block text-gray-700">Endereço</label>
                <input type="text" id="endereco" name="endereco" class="w-full px-4 py-2 border rounded-md" required value="<?= htmlspecialchars($cliente['endereco'] ?? '') ?>">
            </div>

            <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="bairro" class="block text-gray-700">Bairro</label>
                    <input type="text" id="bairro" name="bairro" class="w-full px-4 py-2 border rounded-md" required value="<?= htmlspecialchars($cliente['bairro'] ?? '') ?>">
                </div>
                <div>
                    <label for="cidade" class="block text-gray-700">Cidade</label>
                    <input type="text" id="cidade" name="cidade" class="w-full px-4 py-2 border rounded-md" required value="<?= htmlspecialchars($cliente['cidade'] ?? '') ?>">
                </div>
            </div>

            <div class="mb-4">
                <label for="estado" class="block text-gray-700">Estado</label>
                <input type="text" id="estado" name="estado" class="w-full px-4 py-2 border rounded-md" required value="<?= htmlspecialchars($cliente['estado'] ?? '') ?>">
            </div>

            <div class="mt-4 flex justify-between">
                <button type="submit" class="bg-blue-500 text-white py-2 px-6 rounded-md"><?= $edit == 1 ? 'Salvar Alterações' : 'Cadastrar Cliente' ?></button>
                <a href="index.php" class="text-blue-500">Voltar</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('cep').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 8) value = value.slice(0, 8);
            e.target.value = value.replace(/(\d{5})(\d)/, '$1-$2');
        });

        document.getElementById('numero').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });

        document.getElementById('cep').addEventListener('blur', function() {
            const cep = this.value.replace(/\D/g, '');
            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('endereco').value = data.logradouro || '';
                            document.getElementById('bairro').value = data.bairro || '';
                            document.getElementById('cidade').value = data.localidade || '';
                            document.getElementById('estado').value = data.uf || '';
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro',
                                text: 'CEP não encontrado.'
                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: 'Erro ao buscar o CEP.'
                        });
                    });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção',
                    text: 'CEP inválido.'
                });
            }
        });

        document.getElementById('telefone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });
    </script>
</body>

</html>