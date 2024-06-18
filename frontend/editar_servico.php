<?php
session_start();

if (!isset($_SESSION['email']) || $_SESSION['tipo'] != 'prestador') {
    header("Location: login.html");
    exit();
}

include '../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];

    $sql = "UPDATE servicos SET titulo = '$titulo', descricao = '$descricao', preco = '$preco' WHERE id = $id";
    if ($conexao->query($sql) === TRUE) {
        echo "Serviço atualizado com sucesso.";
    } else {
        echo "Erro ao atualizar serviço: " . $conexao->error;
    }

    $conexao->close();
    exit();
} else {
    $id = $_GET['id'];
    $sql = "SELECT * FROM servicos WHERE id = $id";
    $resultado = $conexao->query($sql);
    $servico = $resultado->fetch_assoc();

    if (!$servico) {
        echo "Serviço não encontrado.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Serviço</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 500px;
        }
        h2 {
            color: #333;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            color: #666;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .btn-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-container button, .btn-container a {
            width: 48%;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Editar Serviço</h2>
        <form action="editar_servico.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $servico['id']; ?>">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" value="<?php echo $servico['titulo']; ?>" required>
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" required><?php echo $servico['descricao']; ?></textarea>
            <label for="preco">Preço:</label>
            <input type="text" id="preco" name="preco" value="<?php echo $servico['preco']; ?>" required>
            <div class="btn-container">
                <button type="submit" class="btn btn-primary">Atualizar</button>
                <a href="dashboard.php" class="btn btn-secondary">Voltar</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
