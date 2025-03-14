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
    <title><?php echo $nome_usuario; ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Helvetica Neue', Arial, sans-serif;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            margin-top: 30px;
        }
        h1 {
            color: #444444;
            font-size: 2.2em;
            margin-bottom: 20px;
        }
        .info {
            font-size: 1.1em;
            color: #666666;
            margin-bottom: 20px;
        }
        .users h2, .services h2 {
            color: #007bff;
            font-size: 1.8em;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .user-item, .service-item {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #dddddd;
            border-radius: 10px;
            background-color: #fafafa;
            transition: box-shadow 0.3s ease-in-out;
        }
        .user-item:hover, .service-item:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .descricao-servico {
            color: #666666;
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
            background-color: #28a745; /* Verde */
            color: white;
            padding: 8px 20px; /* Diminui o padding */
            border-radius: 5px;
            transition: background-color 0.3s ease-in-out;
        }
        .btn-cadastrar-s:hover {
            background-color: #218838; /* Verde mais escuro no hover */
            text-decoration: none;
        }
        .user-item h3, .service-item h3 {
            color: #333333;
            font-size: 1.5em;
            margin-bottom: 10px;
        }
        .user-item p, .service-item p {
            margin: 5px 0;
            color: #555555;
        }
        .user-item p strong, .service-item p strong {
            color: #007bff; /* Azul */
        }
        .btn-primary, .btn-danger, .btn-secondary, .btn-success {
            margin: 0 3px; /* Diminui a margem entre os botões */
            font-size: 0.9em; /* Diminui o tamanho da fonte dos botões */
            padding: 5px 10px; /* Diminui o padding dos botões */
        }
        .toggle-description-btn {
            cursor: pointer;
        }
        .btn-primary:hover, .btn-danger:hover, .btn-secondary:hover, .btn-success:hover {
            color: #fff;
        }
        /* Navbar Customization */
        .navbar {
            background-color: #007bff; /* Azul */
        }
        .navbar-brand {
            color: white;
        }
        .navbar-brand:hover {
            color: #ffffff;
        }
        .navbar-nav .nav-link {
            color: white;
        }
        .navbar-nav .nav-link:hover {
            color: #f8f9fa;
        }
        .dropdown-menu {
            background-color: #007bff; /* Azul */
        }
        .dropdown-item {
            color: white;
        }
        .dropdown-item:hover {
            background-color: #0056b3; /* Azul mais escuro */
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">GetService</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user"></i> <?php echo $nome_usuario; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="perfil.php">Gerenciar Perfil</a>
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
                <h1>Bem-vindo, <?php echo $nome_usuario; ?></h1>
                <p class="info">
                <?php 
                if ($tipo_usuario === 'contratante') {
                    echo 'Encontre os melhores profissionais do mercado para resolver o seu problema';
                } elseif ($tipo_usuario === 'prestador') {
                    echo 'Os clientes estão à sua espera';
                } else {
                    echo 'Tipo de usuário desconhecido.';
                }
                ?>
                </p>
            </div>
        </div>

        <?php if ($tipo_usuario == 'contratante') { ?>
            <div class="users">
                <h2>Prestadores disponíveis</h2>
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
                <h2>Meus serviços</h2>
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

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="js/dashboard.js"></script>
</body>
</html>
