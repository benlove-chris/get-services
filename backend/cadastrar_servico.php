<?php

session_start();
include 'db.php';
/*
// Imprimir dados da sessão para depuração
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
*/
if (!isset($_SESSION['email'])) {
    echo "Sessão de email não definida.";
    header("Location: ../frontend/login.html");
   exit();
}

if ($_SESSION['tipo'] != 'prestador') {
    echo "Tipo de usuário não é prestador.";
    header("Location: ../frontend/login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_SESSION['id'];
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];

    $sql = "INSERT INTO servicos (usuario_id, titulo, descricao, preco) VALUES ('$usuario_id', '$titulo', '$descricao', '$preco')";
    if ($conexao->query($sql) === TRUE) {
        echo "Serviço cadastrado com sucesso.";
    } else {
        echo "Erro ao cadastrar serviço: " . $conexao->error;
    }

    $conexao->close();
}
?>