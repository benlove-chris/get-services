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
    <!-- Bootstrap 4 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f0f2f5;
            font-family: Arial, sans-serif;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
        h1 {
            color: #333;
        }
        .info {
            font-size: 1.2em;
            color: #555;
        }
        .users h2, .services h2 {
            color: #007bff;
            margin-bottom: 20px;
        }
        .user-item, .service-item {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fafafa;
        }
        .descricao-servico {
            color: #666;
            margin-top: 10px;
            display: none; /* Initially hide descriptions */
        }
        .btn-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        .whatsapp-button {
            color: #25d366;
            font-size: 1.5em;
        }
        .btn-cadastrar-s {
            display: block;
            margin-top: 20px;
            text-align: center;
        }
        .user-item h3, .service-item h3 {
            color: #333;
        }
        .user-item p, .service-item p {
            margin: 5px 0;
        }
        .user-item p strong, .service-item p strong {
            color: #007bff;
        }
        .btn-primary, .btn-danger, .btn-secondary, .btn-success {
            margin: 0 5px;
        }
        .toggle-description-btn {
            cursor: pointer;
        }
        .btn-primary:hover, .btn-danger:hover, .btn-secondary:hover, .btn-success:hover {
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dashboard</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user"></i> <?php echo $nome_usuario; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="editar_perfil.php">Gerenciar Perfil</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../backend/logout.php">Sair</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between mb-4">
            <div>
                <h1><i class="fas fa-user"></i> Bem-vindo, <?php echo $nome_usuario; ?></h1>
                <p class="info">Tipo de usuário: <?php echo $tipo_usuario; ?></p>
            </div>
        </div>

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
                        echo "<span class='toggle-description-btn' onclick='toggleDescricao(this)'><i class='fas fa-eye'></i></span>";
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
                        echo "<a class='btn btn-primary' href='editar_servico.php?id=" . $row["id"] . "'>Editar</a>";
                        echo "<button class='btn btn-danger' onclick='apagarServico(" . $row["id"] . ")'>Apagar</button>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>Você ainda não cadastrou nenhum serviço.</p>";
                }
                ?>
                <a class="btn btn-success btn-cadastrar-s" href="cadastrar_servico.html">Cadastrar novo serviço</a>
            </div>
        <?php } ?>
    </div>

    <!-- Bootstrap 4 JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        function toggleDescricao(element) {
            var descricao = element.closest('.user-item, .service-item').querySelector('.descricao-servico');
            descricao.style.display = descricao.style.display === 'none' ? 'block' : 'none';
            var icon = element.querySelector('i');
            if (descricao.style.display === 'none') {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>
</html>
