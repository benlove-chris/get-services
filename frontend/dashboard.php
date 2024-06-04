<?php
session_start();
include '../backend/db.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit();
}

$nome_usuario = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Usuário';
$tipo_usuario = isset($_SESSION['tipo']) ? $_SESSION['tipo'] : '';
$usuario_id = isset($_SESSION['id']) ? $_SESSION['id'] : '';

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
        }
        h1 {
            color: #333;
            font-size: 24px;
        }
        .info {
            margin-bottom: 20px;
            color: #666;
        }
        .services, .users {
            margin-top: 20px;
        }
        .service-item, .user-item {
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            margin-bottom: 10px;
            background-color: #fafafa;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s;
        }
        .service-item:hover, .user-item:hover {
            background-color: #f1f1f1;
        }
        .service-item h3, .user-item h3 {
            margin-top: 0;
            margin-bottom: 5px;
            font-size: 18px;
            color: #333;
        }
        .service-item p, .user-item p {
            margin: 5px 0;
            color: #666;
        }
        a {
            text-decoration: none;
            color: #4caf50;
            display: inline-block;
            margin-top: 10px;
        }
        a:hover {
            text-decoration: underline;
        }
        .logout {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .logout a {
            color: #ff4c4c;
            font-size: 14px;
        }
        .btn-group {
            display: flex;
            gap: 10px;
        }
        .btn-group a, .btn-group button {
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            background-color: #4caf50;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-group a:hover, .btn-group button:hover {
            background-color: #45a049;
        }
        .btn-group button.delete {
            background-color: #ff4c4c;
        }
        .btn-group button.delete:hover {
            background-color: #e04444;
        }
        .cta {
            display: block;
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            border-radius: 5px;
            background-color: #4caf50;
            color: white;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .cta:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logout">
            <a href="editar_perfil.php">Gerenciar Perfil</a>
            <a href="../backend/logout.php">Sair</a>
        </div>
        <h1>Bem-vindo, <?php echo $nome_usuario; ?></h1>
        <p class="info">Tipo de usuário: <?php echo $tipo_usuario; ?></p>

        <?php if ($tipo_usuario == 'contratante') { ?>
            <div class="users">
                <h2>Prestadores</h2>
                <?php
                $sql = "SELECT nome, email FROM usuarios WHERE tipo = 'prestador'";
                $resultado = $conexao->query($sql);
                if ($resultado->num_rows > 0) {
                    while($row = $resultado->fetch_assoc()) {
                        echo "<div class='user-item'>";
                        echo "<div>";
                        echo "<h3>" . $row['nome'] . "</h3>";
                        echo "<p><strong>Email:</strong> " . $row['email'] . "</p>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>Nenhum prestador encontrado.</p>";
                }
                ?>
            </div>
        <?php } elseif ($tipo_usuario == 'prestador') { ?>
            <div class="services">
                <h2>Seus Serviços</h2>
                <?php
                $sql = "SELECT id, titulo, descricao, preco FROM servicos WHERE usuario_id = '$usuario_id'";
                $resultado = $conexao->query($sql);
                if ($resultado->num_rows > 0) {
                    while($row = $resultado->fetch_assoc()) {
                        echo "<div class='service-item'>";
                        echo "<div>";
                        echo "<h3>" . $row['titulo'] . "</h3>";
                        echo "<p>" . $row['descricao'] . "</p>";
                        echo "<p><strong>Preço:</strong> R$ " . $row['preco'] . "</p>";
                        echo "</div>";
                        echo "<div class='btn-group'>";
                        echo "<a href='editar_servico.php?id=" . $row["id"] . "'>Editar</a>";
                        echo "<button class='delete' onclick='apagarServico(" . $row["id"] . ")'>Apagar</button>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>Você ainda não cadastrou nenhum serviço.</p>";
                }
                ?>
                <a class="cta" href="cadastrar_servico.html">Cadastrar novo serviço</a>
            </div>
        <?php } ?>
    </div>

    <script>
        function apagarServico(id) {
            if (confirm("Tem certeza de que deseja apagar este serviço?")) {
                fetch(`../backend/apagar_servico.php?id=${id}`, { method: 'GET' })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload();
                })
                .catch(error => console.error('Erro:', error));
            }
        }
    </script>
</body>
</html>
