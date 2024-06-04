<?php
require 'db.php';

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$senha = $_POST['senha'];
$tipo = $_POST['tipo'];

$sql = "INSERT INTO usuarios (nome, cpf, email, telefone, senha, tipo) VALUES ('$nome', '$cpf', '$email', '$telefone', '$senha', '$tipo')";

if ($conexao->query($sql) === TRUE) {
    echo "Usuário cadastrado com sucesso";
    header("Location: ../frontend/login.html");
    exit();
} else {
    echo "Erro ao cadastrar usuário: " . $conexao->error;
}

$conexao->close();
?>
