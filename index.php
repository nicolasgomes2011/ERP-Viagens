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

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
<link rel="icon" type="image/png" href="img/soft_tech_favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP - Pacotes de Viagem</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

<body class="bg-light text-dark">


    <?php
    if (isset($_GET['error'])) {
        $status = $_GET['error'] == 0 ? 'success' : 'error';
        $msg = $_GET['msg'] ?? 'Ocorreu um erro inesperado.';
        echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        if ('$status' === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso!',
                                text: '$msg',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = window.location.origin + window.location.pathname;
                            });
                        } else if ('$status' === 'error') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: '$msg',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = window.location.origin + window.location.pathname;
                            });
                        }
                    });
                </script>";
    }
    ?>
    <!-- Botão de Logout -->
    <form method="POST" class="position-absolute top-0 end-0 m-3">
        <button type="submit" name="logout" class="btn btn-danger d-flex align-items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M6 3a1 1 0 0 0-1 1v1h1V4h6v8H6v-1H5v1a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H6z" />
                <path fill-rule="evenodd"
                    d="M.146 8.354a.5.5 0 0 1 0-.708L3.5 4.293a.5.5 0 1 1 .707.707L1.707 8l2.5 2.5a.5.5 0 0 1-.707.707l-3.354-3.354z" />
            </svg>
            Sair
        </button>
    </form>

    <?php if ($usuarioLogado): ?>
        <div class="position-absolute top-0 end-0 m-5 bg-white px-3 py-2 rounded shadow-sm small">
            Logado como <strong><?= htmlspecialchars($usuarioLogado['nome']) ?></strong>
            (<?= htmlspecialchars($usuarioLogado['cargo']) ?>)
        </div>
    <?php endif; ?>

    <div class="container mt-5">
        <div class="bg-white p-4 shadow rounded mb-4">
            <h2 class="text-center mb-4">Lista de Pacotes de Viagem</h2>

            <?php if (isset($errorMsg)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($errorMsg) ?></div>
            <?php elseif (empty($pacotes)): ?>
                <div class="alert alert-info text-center">Nenhum pacote cadastrado no momento.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th width="40%">Destino</th>
                                <th>Preço</th>
                                <th>Data</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pacotes as $pacote): ?>
                                <tr>
                                    <td><?= htmlspecialchars($pacote['nome']) ?></td>
                                    <td><?= htmlspecialchars($pacote['destino']) ?></td>
                                    <td class="text-success">R$ <?= number_format($pacote['preco'], 2, ',', '.') ?></td>
                                    <td><?= date("d/m/Y", strtotime($pacote['data_partida'])) ?></td>
                                    <td class="text-center">
                                        <a href="adicionar_pacote.php?edit=1&id=<?= $pacote['id'] ?>"
                                            class="text-primary">Editar</a> |
                                        <a href="acao/acao-excluir-pacote.php?id=<?= $pacote['id'] ?>" class="text-danger delete-link" data-url="acao/acao-excluir-pacote.php?id=<?= $pacote['id'] ?>">Excluir</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="adicionar_pacote.php" class="btn btn-primary">+ Adicionar Novo Pacote</a>
            </div>
        </div>

        <div class="bg-white p-4 shadow rounded">
            <h2 class="text-center mb-4">Lista de Clientes</h2>

            <?php if (isset($errorMsgClientes)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($errorMsgClientes) ?></div>
            <?php elseif (empty($clientes)): ?>
                <div class="alert alert-info text-center">Nenhum cliente cadastrado no momento.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clientes as $cliente): ?>
                                <tr>
                                    <td><?= htmlspecialchars($cliente['nome']) ?></td>
                                    <td><?= htmlspecialchars($cliente['email']) ?></td>
                                    <td><?= htmlspecialchars($cliente['telefone']) ?></td>
                                    <td class="text-center">
                                        <a href="cadastro_cliente.php?id=<?= $cliente['id'] ?>&edit=1" class="text-primary">Editar</a> |
                                        <a href="acao/acao-excluir-cliente.php?id=<?= $cliente['id'] ?>" class="text-danger delete-link" data-url="acao/acao-excluir-cliente.php?id=<?= $cliente['id'] ?>">Excluir</a> |
                                        <button onclick="openModal(<?= $cliente['id'] ?>, '<?= $cliente['nome'] ?>')"
                                            class="btn btn-sm btn-success">Atribuir Pacote</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="cadastro_cliente.php" class="btn btn-primary">+ Cadastrar novo cliente</a>
            </div>
        </div>
    </div>

    <!-- Modal Bootstrap -->
    <div id="modal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formAtribuirPacote" action="atribuir_pacote.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Atribuir Pacote a <span id="clienteNome"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="cliente_id" id="clienteId">
                        <div class="mb-3">
                            <label class="form-label">Escolha um pacote:</label>
                            <select name="pacote_id" class="form-select">
                                <?php foreach ($pacotes as $pacote): ?>
                                    <option value="<?= $pacote['id'] ?>"><?= htmlspecialchars($pacote['nome']) ?> - R$
                                        <?= number_format($pacote['preco'], 2, ',', '.') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts Bootstrap + Modal lógica -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let modal = new bootstrap.Modal(document.getElementById('modal'));

        function openModal(clienteId, clienteNome) {
            document.getElementById("clienteId").value = clienteId;
            document.getElementById("clienteNome").innerText = clienteNome;
            modal.show();
        }

        document.getElementById('formAtribuirPacote').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            fetch('atribuir_pacote.php', {
                    method: 'POST',
                    body: formData
                }).then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Pacote atribuído com sucesso!');
                        modal.hide();
                    } else {
                        alert('Erro: ' + data.message);
                    }
                }).catch(err => console.error('Erro:', err));
        });

        document.addEventListener('DOMContentLoaded', function () {
            // Seleciona todos os links de exclusão
            const deleteLinks = document.querySelectorAll('.delete-link');

            deleteLinks.forEach(link => {
                link.addEventListener('click', function (event) {
                    event.preventDefault(); // Impede o redirecionamento imediato

                    const url = this.getAttribute('data-url'); // Obtém a URL do atributo data-url

                    Swal.fire({
                        title: 'Você tem certeza?',
                        text: "Essa ação não poderá ser desfeita!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sim, excluir!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url; // Redireciona para a URL de exclusão
                        }
                    });
                });
            });
        });
    </script>

</body>

</html>