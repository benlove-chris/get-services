<?php
include 'db.php'; // Certifique-se de que o caminho está correto

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha'];
    $tipo = $_POST['tipo'];

    

    // Inserção no banco de dados
    $sql = "INSERT INTO usuarios (nome, cpf, email, telefone, senha, tipo) VALUES ('$nome', '$cpf', '$email', '$telefone', '$senha', '$tipo')";

    if ($conexao->query($sql) === TRUE) {
        echo "sucesso";
    } else {
        echo "Erro: " . $sql . "<br>" . $conexao->error;
    }

    $conexao->close();
}
?>
