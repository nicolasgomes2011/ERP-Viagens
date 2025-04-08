<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de Pacote - ERP Viagens</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Select CSS (versão compatível com Bootstrap 5) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Bootstrap Bundle JS (inclui Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Bootstrap Select JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

  <script>
    $(document).ready(function () {
      $('.selectpicker').selectpicker();
    });
  </script>
</head>

<body class="bg-light py-5 px-3">
    <div class="container">
        <div class="mx-auto bg-white p-4 rounded shadow" style="max-width: 720px;">
            <h2 class="h4 mb-4 text-center text-primary">Cadastro de Pacote de Viagem</h2>
            <form action="processa_cadastro_pacote.php" method="POST">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome do Pacote</label>
                    <input type="text" id="nome" name="nome" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="destino" class="form-label">Locais a visitar</label>
                    <select id="destino" name="destino[]" class="form-control selectpicker" multiple data-live-search="true" title="Selecione os locais a visitar">
                        <optgroup label="Inverno">
                            <option value="Snowland">Snowland</option>
                            <option value="Lago Negro">Lago Negro</option>
                            <option value="Rua Coberta">Rua Coberta</option>
                            <option value="Catedral de Pedra">Catedral de Pedra</option>
                        </optgroup>
                        <optgroup label="Verão">
                            <option value="Mini Mundo">Mini Mundo</option>
                            <option value="Parque do Caracol">Parque do Caracol</option>
                            <option value="Aldeia do Papai Noel">Aldeia do Papai Noel</option>
                            <option value="Mundo a Vapor">Mundo a Vapor</option>
                        </optgroup>
                        <optgroup label="Natal">
                            <option value="Natal Luz">Natal Luz</option>
                            <option value="Vila de Natal">Vila de Natal</option>
                            <option value="Parada de Natal">Parada de Natal</option>
                        </optgroup>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea id="descricao" name="descricao" class="form-control" rows="4"></textarea>
                </div>

                <div class="mb-3">
                    <label for="preco" class="form-label">Preço</label>
                    <input type="number" id="preco" name="preco" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="data_partida" class="form-label">Data de Partida</label>
                    <input type="date" id="data_partida" name="data_partida" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="data_chegada" class="form-label">Data de Chegada</label>
                    <input type="date" id="data_chegada" name="data_chegada" class="form-control" required>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-primary">Cadastrar Pacote</button>
                    <a href="index.php" class="btn btn-link">Voltar</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>