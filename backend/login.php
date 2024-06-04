<?php
include 'db.php';

$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
$resultado = $conexao->query($sql);

if ($resultado->num_rows > 0) {
    // Usuário autenticado com sucesso
    $usuario = $resultado->fetch_assoc();
    session_start();
    // Armazenar na sessão
    $_SESSION['email'] = $usuario['email'];
    $_SESSION['nome'] = $usuario['nome'];
    $_SESSION['id'] = $usuario['id'];
    $_SESSION['tipo'] = $usuario['tipo']; 

    echo "Login bem-sucedido"; // Mensagem de sucesso opcional
} else {
    // Credenciais inválidas
    http_response_code(401); // Define o código de status HTTP para 401
    echo "E-mail ou senha inválidos.";
    exit();
}

$conexao->close();
?>
