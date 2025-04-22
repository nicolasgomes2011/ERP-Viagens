<?php
require_once "config.php";
if ($edit == 1 && !empty($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM pacotes_viagem WHERE id = :id ORDER BY data_partida ASC");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $pacotes = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $errorMsg = "Erro ao buscar pacotes: " . $e->getMessage();
        $pacotes = [];
    }
}

if (!empty($pacotes['destino'])) {
    $destinosSelecionados = explode(',', $pacotes['destino']);
} else {
    $destinosSelecionados = [];
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
<link rel="icon" type="image/png" href="img/soft_tech_favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Pacote - ERP Viagens</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Select CSS (versão compatível com Bootstrap 5) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/css/bootstrap-select.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-light py-5 px-3">
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
    <div class="container">
        <div class="mx-auto bg-white p-4 rounded shadow" style="max-width: 720px;">
            <h2 class="h4 mb-4 text-center text-primary">
                <?= $edit == 1 ? 'Editar Pacote de Viagem' : 'Cadastro de Pacote de Viagem' ?>
            </h2>
            <form action="acao/acao-cadastrar-pacote.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="imagem" class="form-label">Foto do Pacote</label>
                    <input type="file" id="imagem" name="imagem" class="form-control" accept="image/*">
                </div>
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome do Pacote</label>
                    <input type="text" value="<?= $pacotes['nome'] ?? '' ?>" id="nome" name="nome" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="destino" class="form-label">Locais a visitar</label>
                    <select id="destino" name="destino[]" class="form-control selectpicker" multiple data-live-search="true" title="Selecione os locais a visitar">
                        <optgroup label="Inverno">
                            <option value="Snowland" <?= in_array("Snowland", $destinosSelecionados) ? 'selected' : '' ?>>Snowland</option>
                            <option value="Lago Negro" <?= in_array("Lago Negro", $destinosSelecionados) ? 'selected' : '' ?>>Lago Negro</option>
                            <option value="Rua Coberta" <?= in_array("Rua Coberta", $destinosSelecionados) ? 'selected' : '' ?>>Rua Coberta</option>
                            <option value="Vinícolas de Gramado" <?= in_array("Vinícolas de Gramado", $destinosSelecionados) ? 'selected' : '' ?>>Vinícolas de Gramado</option>
                            <option value="Catedral de Pedra" <?= in_array("Catedral de Pedra", $destinosSelecionados) ? 'selected' : '' ?>>Catedral de Pedra</option>
                        </optgroup>
                        <optgroup label="Verão">
                            <option value="Parque do Caracol" <?= in_array("Parque do Caracol", $destinosSelecionados) ? 'selected' : '' ?>>Parque do Caracol</option>
                            <option value="Mini Mundo" <?= in_array("Mini Mundo", $destinosSelecionados) ? 'selected' : '' ?>>Mini Mundo</option>
                            <option value="Mundo a Vapor" <?= in_array("Mundo a Vapor", $destinosSelecionados) ? 'selected' : '' ?>>Mundo a Vapor</option>
                            <option value="Lago Joaquina Rita Bier" <?= in_array("Lago Joaquina Rita Bier", $destinosSelecionados) ? 'selected' : '' ?>>Lago Joaquina Rita Bier</option>
                            <option value="Parque da Ferradura" <?= in_array("Parque da Ferradura", $destinosSelecionados) ? 'selected' : '' ?>>Parque da Ferradura</option>
                        </optgroup>
                        <optgroup label="Natal">
                            <option value="Natal Luz" <?= in_array("Natal Luz", $destinosSelecionados) ? 'selected' : '' ?>>Natal Luz</option>
                            <option value="Vila de Natal" <?= in_array("Vila de Natal", $destinosSelecionados) ? 'selected' : '' ?>>Vila de Natal</option>
                            <option value="Parada de Natal" <?= in_array("Parada de Natal", $destinosSelecionados) ? 'selected' : '' ?>>Parada de Natal</option>
                            <option value="Iluminação da cidade" <?= in_array("Iluminação da cidade", $destinosSelecionados) ? 'selected' : '' ?>>Iluminação da cidade</option>
                            <option value="Espetáculo do Lago" <?= in_array("Espetáculo do Lago", $destinosSelecionados) ? 'selected' : '' ?>>Espetáculo do Lago</option>
                        </optgroup>
                        <optgroup label="Outono e Primavera">
                            <option value="Le Jardin Parque de Lavanda" <?= in_array("Le Jardin Parque de Lavanda", $destinosSelecionados) ? 'selected' : '' ?>>Le Jardin Parque de Lavanda</option>
                            <option value="Jardim do Amor" <?= in_array("Jardim do Amor", $destinosSelecionados) ? 'selected' : '' ?>>Jardim do Amor</option>
                            <option value="Belvedere Vale do Quilombo" <?= in_array("Belvedere Vale do Quilombo", $destinosSelecionados) ? 'selected' : '' ?>>Belvedere Vale do Quilombo</option>
                            <option value="Pórtico de Gramado" <?= in_array("Pórtico de Gramado", $destinosSelecionados) ? 'selected' : '' ?>>Pórtico de Gramado</option>
                        </optgroup>
                    </select>
                </div>


                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea id="descricao" name="descricao" class="form-control" rows="4"><?= $pacotes['descricao'] ?? '' ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="preco" class="form-label">Preço</label>
                    <input type="text" id="preco" name="preco" class="form-control" required value="<?= isset($pacotes['preco']) ? 'R$ ' . number_format($pacotes['preco'], 2, ',', '.') : '' ?>">

                </div>


                <div class="row">
                    <div class="mb-3 w-50">
                        <label for="data_chegada" class="form-label">Data de Chegada</label>
                        <input type="date" id="data_partida" name="data_partida" class="form-control" required value="<?= $pacotes['data_partida'] ?? '' ?>">
                    </div>

                    <div class="mb-3 w-50">
                        <label for="data_saida" class="form-label">Data de Saída</label>
                        <input type="date" id="data_saida" name="data_saida" class="form-control" required value="<?= $pacotes['data_saida'] ?? '' ?>">
                    </div>
                </div>


                <div class="d-flex justify-content-between mt-4">
                    <?php if ($edit == 1): ?>
                        <input type="hidden" name="id" value="<?= $pacotes['id'] ?>">
                        <button type="submit" name="submit" value="edit" class="btn btn-primary">Editar Pacote</button>
                    <?php else: ?>
                        <button type="submit" name="submit" value="register" class="btn btn-primary">Cadastrar Pacote</button>
                    <?php endif; ?>
                    <a href="index.php" class="btn btn-link">Voltar</a>
                </div>
            </form>
        </div>
    </div>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!-- Bootstrap Bundle JS (inclui Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Select (CDN oficial mais confiável e compatível) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/js/bootstrap-select.min.js"></script>


    <script>
        $(document).ready(function() {
            $('.selectpicker').selectpicker();
        });

        $(document).ready(function() {
            $('#preco').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                value = (value / 100).toFixed(2) + '';
                value = value.replace('.', ',');
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                $(this).val('R$ ' + value);
            });
        });
    </script>
</body>

</html>