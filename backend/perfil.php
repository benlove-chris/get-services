<?php
session_start();
include '../backend/db.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit();
}

$nome_usuario = isset($_SESSION['nome']) ? $_SESSION['nome'] : '';
$email_usuario = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$tipo_usuario = isset($_SESSION['tipo']) ? $_SESSION['tipo'] : '';
$usuario_id = isset($_SESSION['id']) ? $_SESSION['id'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_account'])) {
        $stmt_servicos = $conexao->prepare("DELETE FROM servicos WHERE usuario_id = ?");
        $stmt_servicos->bind_param("i", $usuario_id);
        if ($stmt_servicos->execute()) {
            $stmt_usuario = $conexao->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt_usuario->bind_param("i", $usuario_id);
            if ($stmt_usuario->execute()) {
                session_unset();
                session_destroy();
                header("Location: login.html");
                exit();
            } else {
                echo json_encode(["status" => "erro", "mensagem" => "Erro ao excluir conta: " . $stmt_usuario->error]);
                exit();
            }
        } else {
            echo json_encode(["status" => "erro", "mensagem" => "Erro ao excluir conta: " . $stmt_servicos->error]);
            exit();
        }
    } else {
        $novo_nome = $_POST['nome'];
        $novo_email = $_POST['email'];
        $nova_senha = $_POST['senha'];

        $sql = "UPDATE usuarios SET nome = ?, email = ?";
        $params = [$novo_nome, $novo_email];
        $types = "ss";

        if (!empty($nova_senha)) {
            $senha_hashed = password_hash($nova_senha, PASSWORD_DEFAULT);
            $sql .= ", senha = ?";
            $params[] = $senha_hashed;
            $types .= "s";
        }

        $sql .= " WHERE id = ?";
        $params[] = $usuario_id;
        $types .= "i";

        $stmt = $conexao->prepare($sql);
        if (!$stmt) {
            echo json_encode(["status" => "erro", "mensagem" => "Erro na preparação do statement: " . $conexao->error]);
            exit();
        }

        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $_SESSION['nome'] = $novo_nome;
            $_SESSION['email'] = $novo_email;
            echo json_encode(["status" => "sucesso", "mensagem" => "Perfil atualizado com sucesso."]);
        } else {
            echo json_encode(["status" => "erro", "mensagem" => "Erro ao atualizar perfil: " . $stmt->error]);
        }

        $stmt->close();
        $conexao->close();
    }
    exit();
}
?>
