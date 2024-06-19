<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Preparar declaração para evitar SQL Injection
    $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE email = ?");
    if (!$stmt) {
        echo json_encode(["status" => "erro", "mensagem" => "Erro na preparação do statement: " . $conexao->error]);
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // Usuário encontrado
        $usuario = $resultado->fetch_assoc();

        if (password_verify($senha, $usuario['senha'])) {
            // Senha correta, iniciar sessão
            session_start();
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['tipo'] = $usuario['tipo'];

            echo json_encode(["status" => "sucesso"]);
        } else {
            // Senha incorreta
            echo json_encode(["status" => "erro", "mensagem" => "E-mail ou senha inválidos."]);
        }
    } else {
        // Usuário não encontrado
        echo json_encode(["status" => "erro", "mensagem" => "E-mail ou senha inválidos."]);
    }

    $stmt->close();
    $conexao->close();
} else {
    echo json_encode(["status" => "erro", "mensagem" => "Método de requisição inválido"]);
}
?>
