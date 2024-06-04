<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id'])) {
    echo "Você precisa estar logado para realizar esta ação.";
    exit();
}

$usuario_id = $_SESSION['id'];
$servico_id = $_GET['id'];

$sql = "DELETE FROM servicos WHERE id = '$servico_id' AND usuario_id = '$usuario_id'";

if ($conexao->query($sql) === TRUE) {
    echo "Serviço apagado com sucesso.";
} else {
    echo "Erro ao apagar o serviço: " . $conexao->error;
}

$conexao->close();
?>
