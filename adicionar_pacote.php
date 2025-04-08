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
        $(document).ready(function() {
            $('.selectpicker').selectpicker();

            $('#preco').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                value = (value / 100).toFixed(2) + '';
                value = value.replace('.', ',');
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                $(this).val('R$ ' + value);
            });
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
                        <option value="Snowland">Snowland (esqui e neve indoor)</option>
                        <option value="Lago Negro">Lago Negro (passeios românticos e pedalinho)</option>
                        <option value="Rua Coberta">Rua Coberta (cafés e eventos de inverno)</option>
                        <option value="Vinícolas de Gramado">Vinícolas de Gramado (degustação de vinhos)</option>
                        <option value="Catedral de Pedra">Catedral de Pedra (clima europeu, linda à noite)</option>
                        </optgroup>

                        <optgroup label="Verão">
                        <option value="Parque do Caracol">Parque do Caracol (trilhas e cachoeira)</option>
                        <option value="Mini Mundo">Mini Mundo (maquetes ao ar livre)</option>
                        <option value="Mundo a Vapor">Mundo a Vapor (atração educativa e divertida)</option>
                        <option value="Lago Joaquina Rita Bier">Lago Joaquina Rita Bier (caminhadas e pedalinho)</option>
                        <option value="Parque da Ferradura">Parque da Ferradura (natureza e mirantes)</option>
                        </optgroup>

                        <optgroup label="Natal">
                        <option value="Natal Luz">Natal Luz (evento principal com desfiles e shows)</option>
                        <option value="Vila de Natal">Vila de Natal (feiras natalinas e artesanato)</option>
                        <option value="Parada de Natal">Parada de Natal (desfile gratuito no centro)</option>
                        <option value="Iluminação da cidade">Iluminação da cidade (ruas e praças enfeitadas)</option>
                        <option value="Espetáculo do Lago">Espetáculo do Lago (show pago com fogos e música)</option>
                        </optgroup>

                        <optgroup label="Outono e Primavera">
                        <option value="Le Jardin Parque de Lavanda">Le Jardin Parque de Lavanda (flores e paisagens)</option>
                        <option value="Jardim do Amor">Jardim do Amor (cenários românticos)</option>
                        <option value="Belvedere Vale do Quilombo">Belvedere Vale do Quilombo (mirante com vista)</option>
                        <option value="Pórtico de Gramado">Pórtico de Gramado (entrada icônica da cidade)</option>
                        </optgroup>

                    </select>
                </div>


                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea id="descricao" name="descricao" class="form-control" rows="4"></textarea>
                </div>

                <div class="mb-3">
                    <label for="preco" class="form-label">Preço</label>
                    <input type="text" id="preco" name="preco" class="form-control" required>
                </div>


                <div class="row">
                    <div class="mb-3 w-50">
                        <label for="data_chegada" class="form-label">Data de Chegada</label>
                        <input type="date" id="data_chegada" name="data_chegada" class="form-control" required>
                    </div>

                    <div class="mb-3 w-50">
                        <label for="data_saida" class="form-label">Data de Saída</label>
                        <input type="date" id="data_saida" name="data_saida" class="form-control" required>
                    </div>
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