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
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
</head>
<body>
    <div class="container">
        <div class="logout">
            <a href="editar_perfil.php">Gerenciar Perfil</a>
            <a href="../backend/logout.php">Sair</a>
        </div>
        <h1><i class="fas fa-user"></i> Bem-vindo, <?php echo $nome_usuario; ?></h1>
        <p class="info">Tipo de usuário: <?php echo $tipo_usuario; ?></p>

        <?php if ($tipo_usuario == 'contratante') { ?>
            <div class="users">
                <h2>Prestadores</h2>
                <?php
                $sql = "SELECT usuarios.nome AS usuario_nome, usuarios.email, usuarios.telefone, servicos.titulo, servicos.descricao 
                        FROM usuarios 
                        JOIN servicos ON usuarios.id = servicos.usuario_id 
                        WHERE usuarios.tipo = 'prestador'";
                $resultado = $conexao->query($sql);
                if ($resultado->num_rows > 0) {
                    while($row = $resultado->fetch_assoc()) {
                        echo "<div class='user-item'>";
                        echo "<div>";
                        echo "<h3>" . $row['usuario_nome'] . "</h3>";
                        echo "<p><strong>Email:</strong> " . $row['email'] . "</p>";
                        echo "<p><strong>Título do serviço:</strong> " . $row['titulo'] . "</p>";
                        echo "<div class='descricao-servico'>" . $row['descricao'] . "</div>";
                        echo "</div>";
                        echo "<div class='btn-group'>";
                        echo "<a class='whatsapp-button' href='https://wa.me/55" . $row['telefone'] . "' target='_blank'><i class='fab fa-whatsapp'></i></a>";
                        echo "<button onclick='toggleDescricao(this)'>Ler mais</button>";
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
                <a class="btn-cadastrar-s" href="cadastrar_servico.html">Cadastrar novo serviço</a>
            </div>
        <?php } ?>
    </div>

    <script type="text/javascript" src="js/dashboard.js"></script>
</body>
</html>
