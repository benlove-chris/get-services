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
        button {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
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
            <button type="submit">Atualizar</button>
        </form>
    </div>
</body>
</html>
