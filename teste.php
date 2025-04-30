<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define o tamanho da imagem
$largura = 400;
$altura = 200;

// Cria uma imagem em branco
$imagem = imagecreatetruecolor($largura, $altura);

// Define as cores
$corFundo = imagecolorallocate($imagem, 255, 255, 255); // Branco
$corBorda = imagecolorallocate($imagem, 0, 0, 0); // Preto
$corTexto = imagecolorallocate($imagem, 50, 50, 50); // Cinza escuro

// Preenche o fundo
imagefilledrectangle($imagem, 0, 0, $largura, $altura, $corFundo);

// Adiciona uma borda
imagerectangle($imagem, 0, 0, $largura - 1, $altura - 1, $corBorda);

// Define o texto com codificação UTF-8 correta
$texto = utf8_decode("Foto Não Disponível");

// Adiciona o texto à imagem com uma fonte maior e centralizada
$fontSize = 4; // Tamanho da fonte
$x = ($largura - imagefontwidth($fontSize) * strlen($texto)) / 2;
$y = ($altura - imagefontheight($fontSize)) / 2;
imagestring($imagem, $fontSize, $x, $y, $texto, $corTexto);

// Define o cabeçalho para exibir a imagem
header("Content-Type: image/png");

// Gera a imagem
imagepng($imagem);

// Define o caminho para salvar a imagem
$caminhoArquivo = __DIR__ . '/uploads/pacote_nao_disponivel.png';

// Salva a imagem na pasta uploads
if (imagepng($imagem, $caminhoArquivo)) {
    echo "Imagem salva com sucesso em: " . $caminhoArquivo;
} else {
    echo "Erro ao salvar a imagem.";
}

// Libera a memória
imagedestroy($imagem);
?>