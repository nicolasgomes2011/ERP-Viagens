<?php
require '../config.php'; // Arquivo de conexão com o banco

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $nome = $_POST['nome'];
    $local_para_visitar = implode(', ', $_POST['destino']);
    $descricao = $_POST['descricao'];
    $preco = extract_number_with_decimal($_POST['preco']);
    $data_partida = $_POST['data_partida'];
    $data_saida = $_POST['data_saida'];
    $modo = $_POST['submit'] ?? 'register'; // 'edit' ou 'register'

    // Verifica se é uma edição
    $isEdit = $modo === 'edit' && isset($_POST['id']);
    $id = $isEdit ? intval($_POST['id']) : null;

    // Validação básica
    if (empty($nome) || empty($local_para_visitar) || empty($preco) || empty($data_partida) || empty($data_saida)) {
        $_SESSION['msg'] = 'Por favor, preencha todos os campos obrigatórios.';
        $redirect = $isEdit ? "../adicionar_pacote.php?edit=1&id=$id" : "../adicionar_pacote.php?";
        header("Location: $redirect&error=1&msg=Preencha todos os campos obrigatórios!");
        exit;
    }

    // Verifica se um arquivo foi enviado
    if (isset($_FILES['imagem'])) {
        if ($_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['msg'] = 'Erro no upload da imagem: ' . $_FILES['imagem']['error'];
            header('Location: ../adicionar_pacote.php?error=1');
            exit;
        }

        $uploadDir = '../uploads/';
        $fileName = uniqid() . '-' . basename($_FILES['imagem']['name']);
        $uploadFile = $uploadDir . $fileName;

        // Debug: Verifica se o diretório de upload existe e é gravável
        if (!is_dir($uploadDir)) {
            $_SESSION['msg'] = 'Diretório de upload não existe: ' . $uploadDir;
            header('Location: ../adicionar_pacote.php?error=1');
            exit;
        }

        if (!is_writable($uploadDir)) {
            $_SESSION['msg'] = 'Diretório de upload não é gravável: ' . $uploadDir;
            header('Location: ../adicionar_pacote.php?error=1');
            exit;
        }

        // Debug: Verifica o caminho completo do arquivo de upload
        $_SESSION['msg'] = 'Tentando mover o arquivo para: ' . $uploadFile;

        // Move o arquivo para a pasta de uploads
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadFile)) {
            $imagem = $fileName; // Salva o nome do arquivo no banco de dados
        } else {
            $_SESSION['msg'] = 'Erro ao salvar a imagem. Caminho: ' . $uploadFile;
            header('Location: ../adicionar_pacote.php?error=1');
            exit;
        }
    }

    try {
        if ($isEdit) {
            // Atualiza o pacote existente
            $stmt = $pdo->prepare("UPDATE pacotes_viagem 
                                   SET nome = ?, destino = ?, descricao = ?, preco = ?, data_partida = ?, data_saida = ? 
                                   WHERE id = ?");
            $stmt->execute([$nome, $local_para_visitar, $descricao, $preco, $data_partida, $data_saida, $id]);

            $_SESSION['msg'] = 'Pacote editado com sucesso!';
            header("Location: ../index.php?error=0&msg=Pacote editado com sucesso!");
            exit;
        } else {
            // Insere novo pacote
            $stmt = $pdo->prepare("INSERT INTO pacotes_viagem (nome, destino, descricao, preco, data_partida, data_saida, imagem) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $local_para_visitar, $descricao, $preco, $data_partida, $data_saida, $imagem]);

            $_SESSION['msg'] = 'Pacote cadastrado com sucesso!';
            header('Location: ../index.php?error=0&msg=Pacote cadastrado com sucesso!');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['msg'] = 'Erro ao salvar pacote: ' . $e->getMessage();
        dd($e->getMessage());
        $redirect = $isEdit ? "../adicionar_pacote.php?edit=1&id=$id" : "../adicionar_pacote.php?";
        header("Location: $redirect&error=1&msg=Erro ao salvar pacote!");
        exit;
    }
}
?>