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
        // Exclua os serviços associados ao usuário
        $sql_delete_servicos = "DELETE FROM servicos WHERE usuario_id = '$usuario_id'";
        if ($conexao->query($sql_delete_servicos) === TRUE) {
            // Exclua o próprio usuário
            $sql_delete_usuario = "DELETE FROM usuarios WHERE id = '$usuario_id'";
            if ($conexao->query($sql_delete_usuario) === TRUE) {
                // Limpar a sessão e redirecionar para a página de login após a exclusão da conta
                session_unset();
                session_destroy();
                header("Location: login.html");
                exit();
            } else {
                echo "Erro ao excluir conta: " . $conexao->error;
            }
        } else {
            echo "Erro ao excluir conta: " . $conexao->error;
        }
    } else {
        // Código para atualizar o perfil
        $novo_nome = $_POST['nome'];
        $novo_email = $_POST['email'];
        $nova_senha = $_POST['senha'];

        $sql = "UPDATE usuarios SET nome = '$novo_nome', email = '$novo_email'";

        if (!empty($nova_senha)) {
            $sql .= ", senha = '$nova_senha'";
        }

        $sql .= " WHERE id = '$usuario_id'";

        if ($conexao->query($sql) === TRUE) {
            $_SESSION['nome'] = $novo_nome;
            $_SESSION['email'] = $novo_email;
            echo "Perfil atualizado com sucesso.";
        } else {
            echo "Erro ao atualizar perfil: " . $conexao->error;
        }
    }

    $conexao->close();
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Perfil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin: 10px 0 5px;
            font-weight: bold;
        }
        input {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .delete-account-btn {
            background-color: #ff4c4c;
            margin-top: 10px;
        }
        .delete-account-btn:hover {
            background-color: #e04444;
        }
        .back {
            text-align: center;
            margin-top: 20px;
        }
        .back a {
            text-decoration: none;
            color: #4caf50;
        }
        .back a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Gerenciar Perfil</h1>
        <form method="POST" action="">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo $nome_usuario; ?>" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" value="<?php echo $email_usuario; ?>" required>

            <label for="senha">Nova Senha:</label>
            <input type="password" id="senha" name="senha" placeholder="Deixe em branco para manter a senha atual">

            <button type="submit">Atualizar Perfil</button>
        </form>
        <form method="POST" action="">
            <button type="submit" class="delete-account-btn" name="delete_account" onclick="return confirm('Tem certeza de que deseja excluir sua conta?')">Excluir Conta</button>
        </form>
        <div class="back">
            <a href="dashboard.php">Voltar ao Dashboard</a>
        </div>
    </div>
</body>
</html>
