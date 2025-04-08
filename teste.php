<?php
session_save_path("C:/php_sessions"); // Define manualmente o caminho
session_start();

$_SESSION["teste"] = "Funcionando!";
echo "Sessão gravada!";
?>