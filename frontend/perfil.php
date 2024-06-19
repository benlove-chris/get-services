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
                echo json_encode(["status" => "sucesso"]);
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

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 500px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Gerenciar Perfil</h1>
        <form id="profileForm">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" id="nome" name="nome" class="form-control" value="<?php echo htmlspecialchars($nome_usuario); ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email_usuario); ?>" required>
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label">Nova Senha:</label>
                <input type="password" id="senha" name="senha" class="form-control" placeholder="Deixe em branco para manter a senha atual">
            </div>

            <button type="submit" class="btn btn-primary w-100">Atualizar Perfil</button>
            <div class="alert alert-success mt-3 d-none" id="mensagem_sucesso">Perfil atualizado com sucesso.</div>
            <div class="alert alert-danger mt-3 d-none" id="mensagem_erro">Erro ao atualizar perfil.</div>
        </form>

        <form id="deleteAccountForm" class="mt-4">
            <button type="submit" class="btn btn-danger w-100" name="delete_account" onclick="return confirm('Tem certeza de que deseja excluir sua conta?')">Excluir Conta</button>
        </form>

        <div class="text-center mt-4">
            <a href="dashboard.php" class="btn btn-link">Voltar</a>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#profileForm').on('submit', function(e) {
                e.preventDefault();
                $('#mensagem_sucesso').addClass('d-none');
                $('#mensagem_erro').addClass('d-none');

                $.ajax({
                    url: '',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.status === 'sucesso') {
                            $('#mensagem_sucesso').removeClass('d-none').text(res.mensagem);
                        } else {
                            $('#mensagem_erro').removeClass('d-none').text(res.mensagem);
                        }
                    },
                    error: function() {
                        $('#mensagem_erro').removeClass('d-none').text('Erro ao enviar requisição.');
                    }
                });
            });

            $('#deleteAccountForm').on('submit', function(e) {
                e.preventDefault();
                if (confirm('Tem certeza de que deseja excluir sua conta?')) {
                    $.ajax({
                        url: '',
                        type: 'POST',
                        data: { delete_account: true },
                        success: function(response) {
                            var res = JSON.parse(response);
                            if (res.status === 'sucesso') {
                                window.location.href = 'login.html';
                            } else {
                                $('#mensagem_erro').removeClass('d-none').text(res.mensagem);
                            }
                        },
                        error: function() {
                            $('#mensagem_erro').removeClass('d-none').text('Erro ao enviar requisição.');
                        }
                    });
                }
            });

            $('input').on('input', function() {
                $('#mensagem_sucesso').addClass('d-none');
                $('#mensagem_erro').addClass('d-none');
            });
        });
    </script>
</body>
</html>
