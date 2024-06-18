<?php
include 'db.php'; // Certifique-se de que o caminho está correto

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha'];
    $tipo = $_POST['tipo'];

    // Hashing da senha
    $senha_hashed = password_hash($senha, PASSWORD_DEFAULT);

    // Prepared statement para evitar SQL Injection
    $stmt = $conexao->prepare("INSERT INTO usuarios (nome, cpf, email, telefone, senha, tipo) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(["status" => "erro", "mensagem" => "Erro na preparação do statement: " . $conexao->error]);
        exit();
    }

    $stmt->bind_param("ssssss", $nome, $cpf, $email, $telefone, $senha_hashed, $tipo);

    if ($stmt->execute()) {
        echo json_encode(["status" => "sucesso"]);
    } else {
        echo json_encode(["status" => "erro", "mensagem" => "Erro ao cadastrar usuário: " . $stmt->error]);
    }

    $stmt->close();
    $conexao->close();
} else {
    echo json_encode(["status" => "erro", "mensagem" => "Método de requisição inválido"]);
}
?>
